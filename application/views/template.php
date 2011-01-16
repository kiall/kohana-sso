<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$l = substr(I18n::$lang, 0, 2) ?>" lang="<?=$l ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

		<title><?=$title ?> | Kohana SSO</title>

		<?=HTML::style('media/css/print.css', array('media' => 'print')) ?>
		<?=HTML::style('media/css/screen.css', array('media' => 'screen')) ?>
		<?=HTML::style('media/css/kodoc.css', array('media' => 'screen')) ?>
		<?=HTML::style('media/css/shCore.css', array('media' => 'screen')) ?>
		<?=HTML::style('media/css/shThemeKodoc.css', array('media' => 'screen')) ?>

		<?=HTML::style('media/css/notices.css') ?>
		<?=HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js') ?>
		<?=HTML::script('media/js/notices.js') ?>

		<!--[if lt IE 9]>
			<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
		<![endif]-->

		<?
		// Are there any additional head lines to add?
		if (isset($head) and is_array($head))
		{
			foreach ($head as $h)
			{
				echo $h.PHP_EOL;
			}
		}
		?>
	</head>
	<body>
		<div id="header">
			<div class="container">
				<a href="http://kohanaframework.org/" id="logo">
					<img src="<?=Route::url('docs/media', array('file' => 'img/kohana.png')) ?>" />
				</a>
				<div id="menu">
					<ul>
						<? if ($user): ?>
							<li class="profile first">
								<a href="<?=Route::url('profile', array('username' => $user->username)) ?>"><?=__('Profile') ?></a>
							</li>
							<li class="logout last">
								<a href="<?=Route::url('account', array('action' => 'logout')) ?>"><?=__('Logout') ?></a>
							</li>
						<? else: ?>
							<li class="register first">
								<a href="<?=Route::url('account', array('action' => 'register')) ?>"><?=__('Register') ?></a>
							</li>
							<li class="login last">
								<a href="<?=Route::url('account', array('action' => 'login')) ?>"><?=__('Login') ?></a>
							</li>
						<? endif; ?>
					</ul>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="container">
					<div class="span-22 prefix-1 suffix-1">
						<h1>Kohana SSO</h1>
					</div>
					<div class="span-22 prefix-1 suffix-1">
						<?=Notices::display() ?>
					</div>
					<div class="span-22 prefix-1 suffix-1">
						<?=$body ?>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<div class="container">
				
				<div class="span-24 last right">
					<p>Powered by <?=HTML::anchor('http://kohanaframework.org/', 'Kohana') ?> v<?=Kohana::VERSION ?></p>
				</div>
			</div>
		</div>
	</body>
</html>