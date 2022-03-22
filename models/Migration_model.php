<?php
class Migration_model extends CI_Model
{
	/**
	 * Migration_model constructor.
	 */
	public function __construct()
	{
		parent::__construct();

	}
	function randomApplicationUUID()
	{
		$UUID = mt_rand(10000000,99999999);
		return $UUID;
	}
	function _DECRYPT($STRING)
	{
		return strrev(base64_decode(strrev(strrev(base64_decode(strrev($STRING))))));
	}

	function CheckItemCount($count)
	{
		$count = $count < 1 ? 1 : $count;
		$count = 1000 < $count ? 1000 : $count;
		return $count;
	}
	public function _GetChangedItem($REALMID, $ID)
	{
		if( $this->_CheckWrongOrNoItem($REALMID, $ID) )
		{
			return -1;
		}
		return $ID;
		/*foreach( $this->config->item("replace_items") as $key => $value )
        {
            if( $REALMID == $key )
            {
                foreach( {$this->config->item("replace_items")}[$key] as $i => $value )
                {
                    if( $ID == $i )
                    {
                        return $value["replace"];
                    }

                    return $ID;
                }
            }
            else
            {
                return $ID;
            }

        }*/
	}


	function _CheckWrongOrNoItem($REALMID, $ID)
	{
		foreach( $this->config->item("ignore_items") as $key => $value )
		{
			if( $key == $REALMID )
			{
				if( in_array($ID, $value) )
				{
					return true;
				}

				return false;
			}

			return false;
		}
	}
    function getIconName($item)
    {

        $DisplayID = $this->db->select('displayid')->where('entry', $item)->get('data_wotlk_items')->row('displayid');
        if(empty($DisplayID))
            return "inv_misc_questionmark";
        else
            return strtolower($this->db->select('iconname')->where('id', $DisplayID)->get('data_wotlk_itemdisplayinfo')->row('iconname'));


    }
	public function moneyConversor($amount)
	{
		$gold = substr($amount, 0, -4);
		$silver = substr($amount, -4, -2);
		$copper = substr($amount, -2);

		if ($gold == 0)
			$gold = 0;

		if ($silver == 0)
			$silver = 0;

		if ($copper == 0)
			$copper = 0;

		$money = array(
			'gold' => $gold,
			'silver' => $silver,
			'copper' => $copper
		);

		return $money;
	}
	function GetRaceID($race)
	{
		switch( $race )
		{
			case "HUMAN":
				return 1;
			case "ORC":
				return 2;
			case "DWARF":
				return 3;
			case "NIGHTELF":
				return 4;
			case "SCOURGE":
				return 5;
			case "TAUREN":
				return 6;
			case "GNOME":
				return 7;
			case "TROLL":
				return 8;
			case "BLOODELF":
				return 10;
			case "DRAENEI":
				return 11;
			default:
				exit( "error" );
		}
	}
	function GetClassID($class)
	{
		switch( $class )
		{
			case "WARRIOR":
				return 1;
			case "PALADIN":
				return 2;
			case "HUNTER":
				return 3;
			case "ROGUE":
				return 4;
			case "PRIEST":
				return 5;
			case "DEATHKNIGHT":
				return 6;
			case "SHAMAN":
				return 7;
			case "MAGE":
				return 8;
			case "WARLOCK":
				return 9;
			case "DRUID":
				return 11;
			default:
				exit();
		}
	}


	public function retrieveStatusString($statusID)
	{
		switch($statusID)
		{
			case 0:
				echo "Waiting to be reviewed";
				break;
			case 1:
				echo "Needs more information";
				break;
			case 2:
				echo "Accepted";
				break;
			case 3:
				echo "Invalid";
				break;
			case 4:
				echo "Closed";
				break;
			default:
				exit();
		}
	}

	public function retrieveUserAppData($accountID)
	{
			$data = $this->db->select('*')->where('account', $accountID)->get('migration_applications');
			if($data->num_rows() > 0)
				return $data;
			else
				return false;
	}



	public function formatData($accountID, $realmID, $characterData)
	{
		$this->DecodedDump = $this->_DECRYPT($characterData); // Decrypt character's dump.
		$this->Realm = $realmID; // Realm ID
		$this->AccountID = $accountID; // Account ID

		// Begin the magic!
		$this->json 				= json_decode(stripslashes($this->DecodedDump), true); 											// Actual readable JSON data of the character.
		$this->ClientBuild 			= $this->json["ginf"]["clientbuild"]; 															// Client's build. serves no actual purpose, left overs.
		$this->cLocale        		= trim(strtoupper($this->json["ginf"]["locale"]));												// Client's locale. serves no actual purpose, left overs.
		$this->CharacterName 		= mb_convert_case(mb_strtolower($this->json["uinf"]["name"], "UTF-8"), MB_CASE_TITLE, "UTF-8"); // Character's name. Lowercase everything and then do capital letter.
		$this->CharGender     		= ($this->json["uinf"]["gender"] == 3) ? 1 : 0;													// Character's gender. Ternary operator because WoW dev's were stupid and used odd values.
		$this->Gold 				= $this->json["uinf"]["money"]; 																// Gold. Pure, sweet, yellow gold.
		$this->Level 				= $this->json["uinf"]["level"]; 																// Character's level
		$this->CharacterRaceID  	= $this->GetRaceID(strtoupper($this->json["uinf"]["race"])); 									// Character's race by ID
		$this->CharacterRaceName   	= mb_convert_case(mb_strtolower($this->json["uinf"]["race"], "UTF-8"), MB_CASE_TITLE, "UTF-8"); // Character's race by Name
		$this->CharacterClassID    	= $this->GetClassID(strtoupper($this->json["uinf"]["class"]));									// Character's class by ID
		$this->CharacterClassName  	= mb_convert_case(mb_strtolower($this->json["uinf"]["class"], "UTF-8"), MB_CASE_TITLE, "UTF-8");// Character's class by Name
		$this->CharSpecCount  		= $this->json["uinf"]["specs"];
		$this->CharTotalKills 		= $this->json["uinf"]["kills"];





		// Attach icon names to each of the array item.
		foreach($this->json["inventory"] as $key => $csm)
			$this->json["inventory"][$key]['Icon'] = $this->getIconName($this->json["inventory"][$key]['I']);


		$data = array(
			'name' => $this->CharacterName,
			'level' => $this->Level,
			'classID' => $this->CharacterClassID,
			'className' => $this->CharacterClassName,
			'raceID' => $this->CharacterRaceID,
			'raceName' => $this->CharacterRaceName,
			'specCount' => $this->CharSpecCount,
			'totalKills' => $this->CharTotalKills,
			'gold' => $this->moneyConversor($this->Gold)['gold'], // Gold Value
			'silver' => $this->moneyConversor($this->Gold)['silver'], // Silver Value
			'copper' => $this->moneyConversor($this->Gold)['copper'], // Copper Value
			'items' => $this->json["inventory"],
			'reputations' => $this->json["rep"],
			'currency' => $this->json["currency"]
		);
//		// DEBUG
//		echo '<pre>';
//		print_r(array_values(array_filter(array_map('trim', $this->json["currency"]), 'strlen')));
//		echo '</pre>';



		$this->parser->parse('preview_character', $data);
	}

	public function submitData($accountID, $realmID, $characterData)
	{
		$this->AccountID = $accountID; 																								// Account ID
		$this->UUID = $this->randomApplicationUUID();																				// Generate random 8 digit UUID.
		$this->DecodedDump = $this->_DECRYPT($characterData); 																		// Decrypt character's dump.
		$this->json 				= json_decode(stripslashes($this->DecodedDump), true); 											// Actual readable JSON data of the character.
		$this->Realm = $realmID; 																									// Realm ID
		$this->ClientBuild 			= $this->json["ginf"]["clientbuild"]; 															// Client's build. serves no actual purpose, left overs.
		$this->cLocale        		= trim(strtoupper($this->json["ginf"]["locale"]));												// Client's locale. serves no actual purpose, left overs.
		$this->CharacterName 		= mb_convert_case(mb_strtolower($this->json["uinf"]["name"], "UTF-8"), MB_CASE_TITLE, "UTF-8"); // Character's name. Lowercase everything and then do capital letter.
		$this->oldRealm = $this->json["ginf"]["realm"];																				// Character's old realm name.
		$this->oldRealmlist = $this->json["ginf"]["realmlist"];																	// Character's old realmlist.
		$this->Gold 				= $this->json["uinf"]["money"]; 																// Gold. Pure, sweet, yellow gold.
		$this->CharacterRaceID  	= $this->GetRaceID(strtoupper($this->json["uinf"]["race"])); 									// Character's race by ID
		$this->CharacterClassID    	= $this->GetClassID(strtoupper($this->json["uinf"]["class"]));									// Character's class by ID

		$data = array(
			"uuid" => $this->UUID,
			"account" => $accountID,
			"status" => 0,
			"dump" => $characterData,
			"old_realm" => $this->oldRealm,
			"gold" => $this->Gold,
			"race" => $this->CharacterRaceID,
			"class" => $this->CharacterClassID,
			"old_realmlist" => $this->oldRealmlist,
			"character_oldname" => $this->CharacterName,
			"transfer_to_realm" => $this->Realm,
			"date_created" => time(),
			"client_build" => $this->ClientBuild
		); // Insert GUID, GM ,timestamp and item rows
		$this->db->insert("migration_applications", $data); // Send it awaaay

		$data = array(
			'name' => $this->CharacterName
		);
		$this->parser->parse('preview_character', $data);
	}
}
