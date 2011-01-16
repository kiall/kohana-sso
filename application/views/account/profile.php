<h2>Edit your profile</h2>
<form action="" method="post">
	<label for="user[username]"><?=__('Username') ?>:</label>
	<?=$user->username ?><br />
	<label for="user[email]"><?=__('E-Mail Address') ?>:</label>
	<?=Form::input('user[email]', $user->email) ?>
	<?=Arr::path($errors, 'email') ?><br />
	<label for="user[password]"><?=__('Password') ?>:</label>
	<?=Form::password('user[password]') ?>
	<?=Arr::path($errors, 'password') ?><br />
	<label for="user[password_confirm]"><?=__('Password (again)') ?>:</label>
	<?=Form::password('user[password_confirm]') ?>
	<?=Arr::path($errors, 'user[password_confirm]') ?><br />
	<label for="user[first_name]"><?=__('First Name') ?>:</label>
	<?=Form::input('user[first_name]', $user->first_name) ?>
	<?=Arr::path($errors, 'first_name') ?><br />
	<label for="user[last_name]"><?=__('Last Name') ?>:</label>
	<?=Form::input('user[last_name]', $user->last_name) ?>
	<?=Arr::path($errors, 'last_name') ?><br />
	<label for="user[gender]"><?=__('Gender') ?>:</label>
	<label for="user[gender]"><?=__('Gender') ?>:</label>
	<?=Form::select('user[gender]', array(
		'M' => 'Male',
		'F' => 'Female',
	), $user->timezone) ?>
	<?=Arr::path($errors, 'gender') ?><br />
	<label for="user[country]"><?=__('Country') ?>:</label>
	<?=Form::select('user[country]', (array) Kohana::config('countries'), $user->country) ?>
	<?=Arr::path($errors, 'country') ?><br />
	<label for="user[language]"><?=__('Language') ?>:</label>
	<?=Form::select('user[language]', (array) Kohana::config('languages'), $user->language) ?>
	<?=Arr::path($errors, 'language') ?><br />
	<label for="user[timezone]"><?=__('Timezone') ?>:</label>
	<?=Form::select('user[timezone]', (array) Kohana::config('timezones'), $user->timezone) ?>
	<?=Arr::path($errors, 'timezone') ?><br />
	<?=Form::submit('submit', 'Save changes') ?>
</form>