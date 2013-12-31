<?php

$title = isset($title_for_layout) ? $title_for_layout : 'Untitled';

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7 oldie"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8 oldie"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9 oldie"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<title><?php echo $title; ?></title>
		
		<?php echo $this->Html->css('admin'); ?>
		
		<link href="<?php echo $this->Html->url('/favicon.ico?'), filemtime(WWW_ROOT . 'favicon.ico'); ?>" type="image/x-icon" rel="icon" />
		<link href="<?php echo $this->Html->url('/favicon.ico?'), filemtime(WWW_ROOT . 'favicon.ico'); ?>" type="image/x-icon" rel="shortcut icon" />
		
		<?php echo $this->Html->script('libs/modernizr-2.6.2.min.js'); ?>
		
	</head>
	<body>
		
		<?php echo $this->element('admin/leftbar'); ?>
		
		<div id="wrap">
			<div id="main">
				<div class="container">
				
					<?php echo $this->Session->flash(); ?>
				
					<?php echo $this->fetch('content'); ?>
				
				</div>
			</div><!-- /#main -->
		</div><!-- /#wrap -->
		
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script>window.jQuery || document.write("<script src='<?php echo $this->Html->url('/js/libs/jquery-1.10.2.min.js'); ?>'>\x3C/script>")</script>
		<script>var URL = '<?php echo $this->Html->url('/'); ?>';</script>
		
		<?php echo $this->Html->script('plugins'); ?>
		
		<?php echo $this->Html->script('libs/vex/js/vex.combined.min.js'); ?>
		
		<?php echo $this->fetch('script'); ?>
		
		<?php echo $this->Html->script('libs/bootstrap.min'); ?>
		
		<?php echo $this->Html->script('main'); ?>
		
	</body>
</html>