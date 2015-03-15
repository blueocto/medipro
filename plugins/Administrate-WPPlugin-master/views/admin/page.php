<div class="wrap" id="AdministratePlugin" class="<?= str_replace('_', '-', $_GET['page']); ?>">

	<a href="http://www.getadministrate.com" target="_blank" class="provider"><?php _e('Get Administrate', 'administrate'); ?></a>
	<h2><?php _e('Administrate Plugin Settings', 'administrate'); ?></h2>
	
	<?php $messages = $this->plugin->get_messages(); ?>
	<?php foreach ($messages as $message) { ?>
		<div class="<?= $message['type']; ?> settings-error below-h2"> 
			<p><?= $message['str']; ?></p>
		</div>
	<?php } ?>
	<?php settings_errors(); ?>
	
	<ul class="nav-tab-wrapper">  
		<?php $tabSelected = false; ?>
		<?php foreach ($this->_get_admin_tabs() as $page=>$title) { ?>
			<li><a href="?page=<?= $page; ?>" class="nav-tab<?php if ($_GET['page'] == $page) { ?> nav-tab-active<?php } ?>"><?= $title; ?></a></li>
			<?php 
			if ($_GET['page'] == $page) {
				$tabSelected = $title;
			}
			?>
		<?php } ?>
	</ul>
	
	<?php if ($tabSelected) { ?>

		<?php
		//  Set the page path
		$pageInclude = $this->plugin->get_path('/views/admin/' . $this->plugin->strip_namespace($_GET['page']) . '.php');
		?>
	
		<?php if (file_exists($pageInclude)) { ?>
			<?php include($pageInclude); ?>
		<?php } else { ?>
			<h3><?= $tabSelected; ?></h3>
			<form action="options.php" method="post">
				<?php 
				settings_fields($_GET['page']); 
				do_settings_sections($_GET['page']);
				submit_button(); 
				?>
			</form>
			<?php if ($_GET['page'] == $this->plugin->add_namespace('api_options')) { ?>
				<?php include($this->plugin->get_path('/views/admin/cache_actions.php')); ?>
			<?php } ?>
		<?php } ?>
		
	<?php } else { ?>

		<?php require_once($this->plugin->get_path('/views/admin/home.php')); ?>
		
	<?php } ?>

</div>
