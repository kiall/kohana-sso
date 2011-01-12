<h1>Do you trust this site??</h1>
<p><?=Auth::instance()->get_user()->username ?></p>
<p><a href="<?=Route::url('account', array('action' => 'logout')) ?>">Logout</a></p>
<form action="<?=Route::url('openid', array('action' => 'trust')) ?>" method="post">
	<input type="submit" value="Confirm" name="trust">
	<input type="submit" value="Do not confirm">
</form>