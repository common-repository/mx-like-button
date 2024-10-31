<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<h1><?php echo __( 'MX Like Button settings', 'mxmlb-domain' ); ?></h1>

<nav class="mxmlb_admin_nav_bar">
	
	<ul>
		<li class="<?php echo $_GET['p'] == 'main' || $_GET['p'] == NULL ? 'mxmlb_active_item_menu' : ''; ?>">
			<a href="admin.php?page=mxmlb-mx-like-button-menu&p=main"><?php echo __( 'Main settings', 'mxmlb-domain' ); ?></a>			
		</li>
		<li class="<?php echo $_GET['p'] == 'change_buttons' ? 'mxmlb_active_item_menu' : ''; ?>">
			<a href="admin.php?page=mxmlb-mx-like-button-menu&p=change_buttons"><?php echo __( 'Change buttons', 'mxmlb-domain' ); ?></a>			
		</li>

		<!-- chelck PRO ... -->
			<li class="mx-pro-version-item <?php echo $_GET['p'] == 'go_to_pro_version' ? 'mxmlb_active_item_menu' : ''; ?>" <?php echo !mxmlb_pro_version_available() && !mxmlb_pro_version_active() ? 'style="opacity: 0.5;"' : ''; ?>>

				<?php if( mxmlb_pro_version_available() && !mxmlb_pro_version_active() ) : ?>
					<span class="mx-day-count" title="PRO version available for <?php echo mxmlb_pro_version_count(); ?> days"><?php echo mxmlb_pro_version_count(); ?></span>
				<?php endif; ?>

				<a href="admin.php?page=mxmlb-mx-like-button-menu&p=go_to_pro_version"><?php echo __( 'Additional', 'mxmlb-domain' ); ?></a>
			</li>
		<!-- ... chelck PRO -->
	</ul>

</nav>

<?php if( !mxmlb_pro_version_active() ) : ?>
	<div class="mx-trial-version-info">
		<p>
			You are using a trial version. You can purchase the PRO version and receive all subsequent updates.
		</p>

		<p>
			It only cost <span>$15</span>
		</p>

		<p>
			Contact the author of the plugin on <a href="https://www.facebook.com/profile.php?id=100011204207772" target="_blank">Facebook</a>
		</p>
	</div>
<?php endif; ?>