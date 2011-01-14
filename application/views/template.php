<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $l = substr(I18n::$lang, 0, 2) ?>" lang="<?php echo $l ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

		<title><?php echo $title ?> | Kohana SSO</title>

		<!-- TODO .. fix -->
		<?php echo HTML::style('media/css/print.css', array('media' => 'print')) ?>
		<?php echo HTML::style('media/css/screen.css', array('media' => 'screen')) ?>
		<?php echo HTML::style('media/css/kodoc.css', array('media' => 'screen')) ?>
		<?php echo HTML::style('media/css/shCore.css', array('media' => 'screen')) ?>
		<?php echo HTML::style('media/css/shThemeKodoc.css', array('media' => 'screen')) ?>
		
		<?php echo HTML::style('media/css/notices.css') ?>
		<?php echo HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js') ?>
		<?php echo HTML::script('media/js/notices.js') ?>
		<script type="text/javascript">
			$(function(){
				$('#add_notice').click(function(){
					var type = $('#type').val();
					var message = $('#message').val();
					var persist = $('#persist').val();
					persist = (persist == 'TRUE');
					$('#notices-container').add_notice(type, message, persist);
				});
			});
		</script>

		<!--[if lt IE 9]>
			<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
		<![endif]-->

		<?
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
					<img src="<?php echo Route::url('docs/media', array('file' => 'img/kohana.png')) ?>" />
				</a>
				<div id="menu">
					<ul>
						<? if ($user): ?>
							<li class="logout first">
								
							</li>
							<li class="changepw">
								<a href="<?php echo Route::url('account', array('action' => 'profile', 'username' => $user->username)) ?>"><?php echo __('Profile') ?></a>
							</li>
							<li class="profile last">
								<a href="<?php echo Route::url('account', array('action' => 'logout')) ?>"><?php echo __('Logout') ?></a>
							</li>
						<? else: ?>
							<li class="register first">
								<a href="<?php echo Route::url('account', array('action' => 'register')) ?>"><?php echo __('Register') ?></a>
							</li>
							<li class="login last">
								<a href="<?php echo Route::url('account', array('action' => 'login')) ?>"><?php echo __('Login') ?></a>
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
						<?php echo $body ?>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<div class="container">
				
				<div class="span-24 last right">
					<p>Powered by <?php echo HTML::anchor('http://kohanaframework.org/', 'Kohana') ?> v<?php echo Kohana::VERSION ?></p>
				</div>
			</div>
		</div>
	</body>
</html>