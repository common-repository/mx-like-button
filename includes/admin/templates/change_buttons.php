
<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<h2><?php echo __( 'You can change the images below.', 'mxmlb-domain' ); ?></h2>

<p><i><?php echo __( 'Recommended image size 50x50 px. and picture formats - .png', 'mxmlb-domain' ); ?></i></p>

<?php 

// display blocks for downloading new images
$upload_images_serialize = mxmlb_get_like_option_by_name( '_upload_images' )->mx_like_option_value;

$images_array = maybe_unserialize( $upload_images_serialize );

?>

<?php foreach( $images_array as $key => $value ) : ?>

	<?php

		$image_url = MXMLB_PLUGIN_URL . 'includes/frontend/assets/img/' . $key . '.png';

		if( $value !== '' ) {

			$image_url = get_site_url() . '/' . $value;

		}

	?>

	<div class="mx-block_wrap">

		<h3><?php echo __( $key . ' button', 'mxmlb-domain' ); ?></h3>

		<div class="mx-like-preview-wrap">
			<img src="<?php echo $image_url; ?>" alt="" class="mx-like-preview" />
			<button data-type-like="<?php echo $key; ?>" class="mx-btn-remove" <?php echo $value == '' ? 'style="display: none;"' : ''; ?> title="<?php echo __( 'Remove this image', 'mxmlb-domain' ); ?>"><i class="fa fa-close"></i></button>
		</div>

		<form enctype="multipart/form-data" action="" method="POST" class="mxmlb_form_upload_like_img">

			<input name="bl_upload_<?php echo $key; ?>" data-type-like="<?php echo $key; ?>" class="lb_upload_img" type="file" />
			<input type="submit" value="<?php echo __( 'Upload Image', 'mxmlb-domain' ); ?>" />

		</form>

	</div>

<?php endforeach; ?>