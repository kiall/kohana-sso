<form action="<?=Route::url('account', array('action' => 'login')) ?>" method="post">
	Username:<input type="text" name="username" /><br/>
	Password:<input type="password" name="password" /><br/>
	<input type="submit" value="Login">
</form>