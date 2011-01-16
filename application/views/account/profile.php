<? if ($user !== FALSE AND $user->username == $profile_user->username): ?>
	<h2>Edit your profile</h2>
	<form action="<?=Route::url('profile', array('username' => $profile_user->username)) ?>" method="post">
		<label for="user[username]"><?=__('Username') ?>:</label>
		<?=$profile_user->username ?><br />
		<label for="user[email]"><?=__('E-Mail Address') ?>:</label>
		<?=Form::input('user[email]', $profile_user->email) ?>
		<?=Arr::path($errors, 'email') ?><br />
		<label for="user[password]"><?=__('Password') ?>:</label>
		<?=Form::password('user[password]') ?>
		<?=Arr::path($errors, 'password') ?><br />
		<label for="user[password_confirm]"><?=__('Password (again)') ?>:</label>
		<?=Form::password('user[password_confirm]') ?>
		<?=Arr::path($errors, 'user[password_confirm]') ?><br />
		<label for="user[first_name]"><?=__('First Name') ?>:</label>
		<?=Form::input('user[first_name]', $profile_user->first_name) ?>
		<?=Arr::path($errors, 'first_name') ?><br />
		<label for="user[last_name]"><?=__('Last Name') ?>:</label>
		<?=Form::input('user[last_name]', $profile_user->last_name) ?>
		<?=Arr::path($errors, 'last_name') ?><br />
		<label for="user[gender]"><?=__('Gender') ?>:</label>
		<?=Form::select('user[gender]', array(
			'M' => 'Male',
			'F' => 'Female',
		), $profile_user->timezone) ?>
		<?=Arr::path($errors, 'gender') ?><br />
		<label for="user[country]"><?=__('Country') ?>:</label>
		<?=Form::select('user[country]', (array) Kohana::config('countries'), $profile_user->country) ?>
		<?=Arr::path($errors, 'country') ?><br />
		<label for="user[language]"><?=__('Language') ?>:</label>
		<?=Form::select('user[language]', (array) Kohana::config('languages'), $profile_user->language) ?>
		<?=Arr::path($errors, 'language') ?><br />
		<label for="user[timezone]"><?=__('Timezone') ?>:</label>
		<?=Form::select('user[timezone]', (array) Kohana::config('timezones'), $profile_user->timezone) ?>
		<?=Arr::path($errors, 'timezone') ?><br />
		<?=Form::submit('submit', 'Save changes') ?>
	</form>
<? else: ?>
	<h2><?=$profile_user->first_name ?> <?=$profile_user->last_name ?>'s Profile</h2>
	<ul>
		<li><strong>Username</strong>: <?=$profile_user->username ?></li>
		<li><strong>Gender</strong>: <?=$profile_user->gender ?></li>
		<li><strong>Country</strong>: <?=$profile_user->country ?></li>
		<li><strong>Language</strong>: <?=$profile_user->language ?></li>
		<li><strong>Timezone</strong>: <?=$profile_user->timezone ?></li>
	</ul>
<? endif; ?>
