<header id="header">
	<div class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<?php echo $this->Html->link(Configure::read('Company.name'), '/', array('class' => 'navbar-brand')); ?>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><?php echo $this->Html->link('Home', '/'); ?></li>
					<li><?php echo $this->Html->link('About Us', '#'); ?></li>
					<li><?php echo $this->Html->link('Contact Us', '#'); ?></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo $this->Html->link('Action', '#'); ?></li>
							<li><?php echo $this->Html->link('Another Action', '#'); ?></li>
							<li><?php echo $this->Html->link('Something else here', '#'); ?></li>
							<li class="divider"></li>
							<li class="dropdown-header">Nav header</li>
							<li><?php echo $this->Html->link('Separated Link', '#'); ?></li>
							<li><?php echo $this->Html->link('One more separated link', '#'); ?></li>
						</ul>
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div><!-- /navbar -->
</header><!-- /#header -->