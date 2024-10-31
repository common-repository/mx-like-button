
<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$row_options_for_post_type = mxmlb_get_like_option_by_name( '_turn_of_for_post_types' );

$row_options_for_post_type = maybe_unserialize( $row_options_for_post_type->mx_like_option_value );

?>

<h2><?php echo __( 'Pro Version', 'mxmlb-domain' ); ?></h2>

<form action="" id="mxmlb_post_type_form">

	<p><?php echo __( 'Turn on the "MX Like Button"', 'mxmlb-domain' ); ?></p>

	<?php
		// for BuddyPress
		$bp_checked = '';

		$bp_mxmlb_post_type_turn_of = 'mxmlb_post_type_turn_of';

		if( !in_array( 'bp-comments', $row_options_for_post_type ) ) {

			$bp_checked = 'checked';

			$bp_mxmlb_post_type_turn_of = '';

		}

	?>

	<div class="mx-post-type-box <?php echo $bp_mxmlb_post_type_turn_of; ?> <?php echo !mxmlb_pro_version_available() ? 'mxmlb_post_type_turn_of' : ''; ?>">
			
		<input type="checkbox" class="mx_post_type_checkbox" id="mx_bp-comments" data-post-type="bp-comments" <?php echo $bp_checked; ?> <?php echo !mxmlb_pro_version_available() ? 'disabled' : ''; ?> />
		<label for="mx_bp-comments">BuddyPress Comments (Reply)</label>

	</div>


	<p><?php echo __( 'Turn on the "MX Like Button" popup', 'mxmlb-domain' ); ?></p>

	<?php
		// Enable Popup
		$bp_checked = '';

		$bp_mxmlb_post_type_turn_of = 'mxmlb_post_type_turn_of';

		if( !in_array( 'mx-like-popup', $row_options_for_post_type ) ) {

			$bp_checked = 'checked';

			$bp_mxmlb_post_type_turn_of = '';

		}

	?>

	<div class="mx-post-type-box <?php echo $bp_mxmlb_post_type_turn_of; ?> <?php echo !mxmlb_pro_version_available() ? 'mxmlb_post_type_turn_of' : ''; ?>">
			
		<input type="checkbox" class="mx_post_type_checkbox" id="mx_mx-like-popup" data-post-type="mx-like-popup" <?php echo $bp_checked; ?> <?php echo !mxmlb_pro_version_available() ? 'disabled' : ''; ?> />
		<label for="mx_mx-like-popup">Enable Popup</label>

	</div>

	<p><?php echo __( 'Turn on the "MX Like Button" on home page', 'mxmlb-domain' ); ?></p>

	<?php
		// Enable on home page
		$bp_checked = '';

		$bp_mxmlb_post_type_turn_of = 'mxmlb_post_type_turn_of';

		if( in_array( 'mx-like-enable_homepage', $row_options_for_post_type ) ) {

			$bp_checked = 'checked';

			$bp_mxmlb_post_type_turn_of = '';

		}

	?>

	<div class="mx-post-type-box <?php echo $bp_mxmlb_post_type_turn_of; ?> <?php echo !mxmlb_pro_version_available() ? 'mxmlb_post_type_turn_of' : ''; ?>">
			
		<input type="checkbox" class="mx_post_type_checkbox" id="mx_mx-like-enable_homepage" data-post-type="mx-like-enable_homepage" <?php echo $bp_checked; ?> <?php echo !mxmlb_pro_version_available() ? 'disabled' : ''; ?> />
		<label for="mx_mx-like-enable_homepage">Enable on Home Page</label>

	</div>
	
</form>

<?php if( !mxmlb_pro_version_active() ) : ?>

	<h3>New features:</h3>

	<p>Thank you for using the Mx Like Button plugin. :)</p>
	
	<iframe width="560" height="315" src="https://www.youtube.com/embed/FTOb9KvanUE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<?php endif; ?>

<?php if( !mxmlb_pro_version_available() ) : ?>
<!-- chelck PRO ... -->
<div class="mx-trial-period-has-ended">
	
	<h2><?php echo __( 'Your trial period has ended!', 'mxmlb-domain' ); ?></h2>

	<p>
		If you want to activate PRO version, you have to buy activator.
	</p>
	<p>
		It only cost <span>$15</span>
	</p>

	<p>
		Contact the author of the plugin on <a href="https://www.facebook.com/profile.php?id=100011204207772" target="_blank">Facebook</a>
	</p>

</div>

	
<!-- ... chelck PRO -->
<?php endif; ?>