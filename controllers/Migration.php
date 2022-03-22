<?php
// Direct access is not allowed, get out bro.
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends MX_Controller
{
	public function __construct()
	{
		// Main Call.
		parent::__construct();
		$this->load->model('migration_model');
		$this->load->database();
		$this->load->library('parser');

		if (!ini_get('date.timezone'))
			date_default_timezone_set($this->config->item('timezone'));
		// If maintenance is on, you don't belong here.
		if (!$this->wowgeneral->getMaintenance())
			redirect(base_url('maintenance'), 'refresh');
		// If you're not logged in, you don't belong here.
		if (!$this->wowauth->isLogged())
			redirect(base_url('login'), 'refresh');

	}

	public function verify()
	{

		$accountID = $this->session->userdata('session_uuid'); // Account ID
		$realmID = $this->input->post('realm_id'); // Realm ID
		$characterData = $this->input->post('character_data'); // Character Data


	//	if($realmID == 0)
	//		echo "No realm has been selected!";
	//	elseif($characterData == 0)
	//		echo "No character Data!";

			echo $this->migration_model->formatData($accountID, $realmID, $characterData);

	}

	public function submit()
	{

		$accountID = $this->session->userdata('session_uuid'); // Account ID
		$realmID = $this->input->post('realm_id'); // Realm ID
		$characterData = $this->input->post('character_data'); // Character Data



			if($realmID == NULL)
				echo "No realm has been selected!";
			elseif($characterData == NULL)
				echo "No character Data!";
		echo $this->migration_model->submitData($accountID, $realmID, $characterData);
	}

}
