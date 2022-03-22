<div class="uk-card uk-card-body uk-width-1-1@m uk-align-center uk-text-center@m">
	<h1 class="uk-light">{name}</h1>
	<p>Level {level} {raceName} {className}</p>
</div>

<p class="uk-text-center">PLEASE READ ALL DATA CAREFULLY!<br>

Below is displayed all data that our tool has gathered. It’s important to note that our tool doesn’t gather secondary items (enchants, gems..).<br>
If your application is successful, we’ll set your character’s name to temporary one.<br>At next character login, you’ll be prompted to change your character’s name to old one, or set another one.<br>
It’s up to you!
</p>
<br>
<div class='uk-grid-divider uk-text-center' uk-grid>
<div class='uk-width-1-2@m '>
	<div class='uk-card uk-card-body uk-light'>
		<div class="uk-card-header">Currencies</div>
		<table class="uk-table uk-table-small uk-table-divider">
			<thead>
			<tr>
				<th></th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			{currency}
			<tr>
				<td>{N}</td>
				<td>{C}</td>
			</tr>
			{/currency}
			</tbody>
		</table>
	</div>
</div>
<div class='uk-width-expand@m'>
	<div class='uk-card uk-card-body uk-light uk-margin-left uk-margin-right'>
		<h3 class="uk-card-title">Reputations</h3>
		<table class="uk-table uk-table-small uk-table-divider">
			<thead>
			<tr>
				<th></th>
				<th></th>
			</tr>
			</thead>
			<tbody>
			{reputations}
			<tr>
				<td>{N}</td>
				<td>{V} / 42999</td>
			</tr>
			{/reputations}
			</tbody>
		</table>
	</div>
</div>
</div>

<div class="reading-data">
	<div class="migration-items-container">
		{items}
		<div class="items_img"><a href="https://wotlk.evowow.com/item={I}"><img src="/assets/images/icons/{Icon}.png"></a><span>x{C}</span></div>
		{/items}
	</div>
</div>

<div class="readed-data"></div>
<div class="uk-align-center uk-text-center">
<p class="migration-data-agree" align="center" style="color:red;">If you agree with the data your character will receive, please press below Next.</p>
<p class="migration-check"></p>
<div class="migration-loader"><img src="/application/modules/migrate/images/ajax-loader.gif"></div>
</div>
<br>

<div class="uk-child-width-expand@s uk-text-center" uk-grid>
	<div>
		<div class="uk-card uk-card-body"> <a id="back" href="#" class='backb uk-button uk-margin-small uk-button-default'>Back</a></div>
	</div>
	<div>
		<div class="uk-card uk-card-body"><a id="migbit" href="#" class='uk-button uk-margin-small uk-button-default' onclick="Migration.Switch(4);">Next</a></div>
	</div>
</div>
