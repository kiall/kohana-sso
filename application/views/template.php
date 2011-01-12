<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $l = substr(I18n::$lang, 0, 2) ?>" lang="<?php echo $l ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

		<title><?php echo $title ?> | Kohana SSO</title>

		<!-- TODO .. fix -->
		<link type="text/css" href="http://wk01-lmst.managedit.ie/kohana-sso/guide/media/css/print.css" rel="stylesheet" media="print" />
		<link type="text/css" href="http://wk01-lmst.managedit.ie/kohana-sso/guide/media/css/screen.css" rel="stylesheet" media="screen" />
		<link type="text/css" href="http://wk01-lmst.managedit.ie/kohana-sso/guide/media/css/kodoc.css" rel="stylesheet" media="screen" />
		<link type="text/css" href="http://wk01-lmst.managedit.ie/kohana-sso/guide/media/css/shCore.css" rel="stylesheet" media="screen" />
		<link type="text/css" href="http://wk01-lmst.managedit.ie/kohana-sso/guide/media/css/shThemeKodoc.css" rel="stylesheet" media="screen" />

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
						<li class="login first">
							<a href="<?php echo Route::url('account', array('action' => ($user ? 'logout' : 'login'))) ?>"><?php echo __(($user ? 'Logout' : 'Login')) ?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="container">
					<div class="span-22 prefix-1 suffix-1">
						<ul id="breadcrumb">
							<?php foreach ($breadcrumb as $link => $title): ?>
								<?php if (is_string($link)): ?>
									<li><?php echo HTML::anchor($link, $title) ?></li>
								<?php else: ?>
									<li class="last"><?php echo $title ?></li>
								<?php endif ?>
							<?php endforeach ?>
						</ul>
					</div>
					<div class="span-22 prefix-1 suffix-1">
						<?=Notices::display() ?>
					</div>
					<div class="span-6 prefix-1">
						<div id="topics">
							<?php echo $menu ?>
						</div>
					</div>
					<div id="body" class="span-16 suffix-1 last">
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