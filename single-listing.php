<?php 

    include get_template_directory() . '/includes/block-options.php';
    $images = get_field('images');
    $block_padding_top = get_field('block_padding_top');
    $block_padding_bottom = get_field('block_padding_bottom');

    $title = get_field('title');
    $price = get_field('price');
    $description = get_field('description');
    $video = get_field('video');
    $floor_plan = get_field('floor_plan');
    $sticky_title = get_field('sticky_title');
    $sticky_cta = get_field('sticky_cta') ? : array('title' => 'Book a call', 'url' => '/booking-form', 'target' => '_self');
    $icon_summary = get_field('icon_summary');
    $feature_icon = get_field('feature_icon');
    $key_feature_icon = get_field('key_feature_icon');

    $heading = get_field('heading');
    $intro = get_field('intro');
    $phone = get_field('phone_number', 'option');
    $owner_phone = get_field('ownership_phone_number', 'option');
    $email = get_field('email_address', 'option');
    $map_heading = get_field('map_heading');
    $address = get_field('address', 'option');
    $embed_form_code = get_field('embed_form_code', 'option');
    $map_api = get_field('google_maps_api', 'option');
    $map_id = get_field('google_maps_id', 'option');
    $map_lat = get_field('map_lat', 'option');
    $map_long = get_field('map_long', 'option');
    $map_pin_title = get_field('map_pin_title', 'option');
    $marker_img = get_field('marker_image', 'option');
?>

<?php get_header(); ?>
<?php include get_template_directory() . '/includes/navigation.php'; ?>

<main>
    <?php
        acf_render_block(
            array(
                'id'    => uniqid('block_share-block'),
                'name'  => 'acf/share-block',
                'data'  => array(
                    'title' => $title,
                    'price' => $price,
                    'block_padding_top' => $block_padding_top,
                    'block_padding_bottom' => $block_padding_bottom,
                )
            )
        );
    ?>

    <?php
        acf_render_block(
            array(
                'id'    => uniqid('block_image-gallery-block'),
                'name'  => 'acf/image-gallery-block',
                'data'  => array(
                    'images' => $images,
                    'block_padding_top' => $block_padding_top,
                    'block_padding_bottom' => $block_padding_bottom,
                )
            )
        );
    ?>
    <?php
        acf_render_block(
            array(
                'id'    => uniqid('block_listing-summary-block'),
                'name'  => 'acf/listing-summary-block',
                'data'  => array(
                    'title' => $title,
                    'price' => $price,
                    'description' => $description,
                    'video' => $video,
                    'floor_plan' => $floor_plan,
                    'sticky_title' => $sticky_title,
                    'sticky_cta' => $sticky_cta,
                    'icon_summary' => $icon_summary,
                    'feature_icon' => $feature_icon,
                    'key_feature_icon' => $key_feature_icon,
                    'block_padding_top' => $block_padding_top,
                    'block_padding_bottom' => $block_padding_bottom,
                )
            )
        );
    ?>

<?php
        acf_render_block(
            array(
                'id'    => uniqid('block_contact-block'),
                'name'  => 'acf/contact-block',
                'data'  => array(
                    'heading' => $heading,
                    'intro' => $intro,
                    'phone_number' => $phone,
                    'ownership_phone_number' => $owner_phone,
                    'email' => $email,
                    'map_heading' => $map_heading,
                    'address' => $address,
                    'show_form' => true,
                    'embed_form_code' => $embed_form_code,
                    'block_padding_top' => $block_padding_top,
                    'block_padding_bottom' => $block_padding_bottom,
                    'google_maps_api' => $map_api,
                    'google_maps_id' => $map_id,
                    'map_lat' => $map_lat,
                    'map_long' => $map_long,
                    'map_pin_title' => $map_pin_title,
                    'marker_image' => $marker_img,
                )
            )
        );
    ?>

    <?php
    $whatsapp_icon = get_field('whatsapp_icon', 'option');
        acf_render_block(
            [
                'id'    => uniqid('block_chat-form-block'),
                'name'  => 'acf/chat-form-block',
                'data' => [
                    'whatsapp_icon' => get_field('whatsapp_icon', 'option'),
                ]
            ]
        );
    ?>
</main>


<?php include get_template_directory() . '/includes/footer.php'; ?>

<?php get_footer(); ?>