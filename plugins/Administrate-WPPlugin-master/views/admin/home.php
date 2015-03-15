<h3><?php _e('Introduction', 'administrate'); ?></h3>
<?php 
_e('
<p>Please use the tabs above to configure the Administrate plugin. For more information about how to use the plugin, please see the <a href="?page=administrate_help">Help tab</a>.</p>
', 'administrate'); 
?>

<?php $txt = $this->plugin->parse_readme($this->plugin->get_path('/readme.txt')); ?>

<h3><?php _e('Upgrade Instructions', 'administrate'); ?></h3>
<?php foreach ($txt[0]['items'] as $item) { ?>
	<?php if (is_array($item) && ($item['title'] == 'Installation')) { ?>
		<?php foreach ($item['items'] as $subitem) { ?>
			<?php if (is_array($subitem) && ($subitem['title'] == 'Upgrading')) { ?>
				<?php foreach ($subitem['items'] as $line) { ?>
					<?= $line; ?><br>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>

<h3><?php _e('Changelog', 'administrate'); ?></h3>
<?php foreach ($txt[0]['items'] as $item) { ?>
	<?php if (is_array($item) && isset($item['title']) && ($item['title'] == 'Changelog')) { ?>
		<?php foreach ($item['items'] as $release) { ?>
			<?php if (isset($release['title'])) { ?>
				<h4><?= $release['title']; ?></h4>
				<ul>
					<?php foreach ($release['items'] as $note) { ?>
						<li><?= $note; ?></li>
					<?php } ?>
				</ul>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>

<h3><?php _e('Upgrade Notice', 'administrate'); ?></h3>
<?php foreach ($txt[0]['items'] as $item) { ?>
	<?php if (is_array($item) && isset($item['title']) && ($item['title'] == 'Upgrade Notice')) { ?>
		<?php foreach ($item['items'] as $release) { ?>
			<?php if (isset($release['title'])) { ?>
				<h4><?= $release['title']; ?></h4>
				<ul>
					<?php foreach ($release['items'] as $note) { ?>
						<li><?= $note; ?></li>
					<?php } ?>
				</ul>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } ?>