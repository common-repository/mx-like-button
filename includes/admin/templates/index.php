<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$post_types = get_post_types();

$row_options_for_post_type = mxmlb_get_like_option_by_name( '_turn_of_for_post_types' );

$row_options_for_post_type = maybe_unserialize( $row_options_for_post_type->mx_like_option_value );

?>

<h2><?php echo __( 'List of post types:', 'mxmlb-domain' ); ?></h2>

<form action="" id="mxmlb_post_type_form">

	<p><?php echo __( 'You can turn off the "MX Like Button" for some types of posts.', 'mxmlb-domain' ); ?></p>

	<?php
		// for BuddyPress
		$bp_checked = '';

		$bp_mxmlb_post_type_turn_of = 'mxmlb_post_type_turn_of';

		if( !in_array( 'bp', $row_options_for_post_type ) ) {

			$bp_checked = 'checked';

			$bp_mxmlb_post_type_turn_of = '';

		}

	?>

	<div class="mx-post-type-box <?php echo $bp_mxmlb_post_type_turn_of; ?>">
			
		<input type="checkbox" class="mx_post_type_checkbox" id="mx_bp" data-post-type="bp" <?php echo $bp_checked; ?> />
		<label for="mx_bp">BuddyPress</label>

	</div>

	<?php foreach( $post_types as $post_type ) : ?>

		<?php 

			$checked = '';

			$mxmlb_post_type_turn_of = 'mxmlb_post_type_turn_of';

			if( !in_array( $post_type, $row_options_for_post_type ) ) {

				$checked = 'checked';

				$mxmlb_post_type_turn_of = '';

			}

		?>
	
		<div class="mx-post-type-box <?php echo $mxmlb_post_type_turn_of; ?>">
			
			<input type="checkbox" class="mx_post_type_checkbox" id="mx_<?php echo $post_type; ?>" data-post-type="<?php echo $post_type; ?>" <?php echo $checked; ?> />
			<label for="mx_<?php echo $post_type; ?>"><?php echo $post_type; ?></label>

		</div>

	<?php endforeach; ?>

	<!--  -->
	<p><?php echo __( 'Switch off MX Like Button to non-signed-in users.', 'mxmlb-domain' ); ?></p>

	<?php
		// for BuddyPress
		$bp_checked = '';

		$bp_mxmlb_post_type_turn_of = 'mxmlb_post_type_turn_of';

		if( !in_array( 'switch-on-to-logout-users', $row_options_for_post_type ) ) {

			$bp_checked = 'checked';

			$bp_mxmlb_post_type_turn_of = '';

		}

	?>

	<div class="mx-post-type-box <?php echo $bp_mxmlb_post_type_turn_of; ?>">
			
		<input type="checkbox" class="mx_post_type_checkbox" id="mx_switch-on-to-logout-users" data-post-type="switch-on-to-logout-users" <?php echo $bp_checked; ?> />
		<label for="mx_switch-on-to-logout-users">Show to non-signed-in users</label>

	</div>

</form>