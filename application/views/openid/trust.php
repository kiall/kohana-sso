<h1>OpenID Trust</h1>
<p>Would you like to sign in to <strong><?=$relying_party ?></strong>?</p>
<p>You are currently logged in as <strong><?=Auth::instance()->get_user()->username ?></strong></p>
<form action="<?=Route::url('openid', array('action' => 'trust')) ?>" method="post">
	<input type="submit" value="Confirm" name="trust">
	<input type="submit" value="Do not confirm">
</form>
<?=Debug::vars($request->message->args->values) ?>