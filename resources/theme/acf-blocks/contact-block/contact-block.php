<?php
    /*
        Block Name: Contact Block
        Block Description: Block Description
        Internal: contact-block
        Post Types: post, page, custom-type
    */
    $use_park_locations = get_field('use_park_locations');
    $address_from = get_field('address_from');
    $park_location = (function() use ($use_park_locations, $address_from) {
        if (!$use_park_locations) return null;

        $park_locations = get_field('park_locations', 'option') ?? [];

        return current(array_filter($park_locations, fn($location) => $location['key'] == $address_from)) ?? null;
    })();

    $intro = get_field('intro');
    $heading = get_field('heading');
    $bg_colour = get_field('background_colour');
    $phone = get_field('phone_number', 'option');
    $owner_phone = get_field('ownership_phone_number', 'option');
    $email = get_field('email_address', 'option');
    $address = $use_park_locations ? [['address_line' => $park_location['address']]] : get_field('address', 'option');
    $map_heading = get_field('map_heading');
    $show_form = get_field('show_form');
    $embed_form_code = get_field('embed_form_code');
    $play_animation = get_field('play_animation');
    
    if($play_animation) {
        $play_animation = 'has-animation';
    }


    // General Settings
    $map_api = get_field('google_maps_api', 'option');
    $map_id = get_field('google_maps_id', 'option');
    $map_lat = $use_park_locations ? $park_location['map_lat'] : get_field('map_lat', 'option');
    $map_long = $use_park_locations ? $park_location['map_long'] : get_field('map_long', 'option');
    $map_pin_title = get_field('map_pin_title', 'option');
    $marker_img = get_field('marker_image', 'option');

    // Create id attribute allowing for custom "anchor" value.
    $id = 'contact-' . $block['id'];
    if( !empty($block['anchor']) ) {
        $id = $block['anchor'];
    }

    $post_type = get_post_type();

    if( $post_type == 'listing' ) {
        $id = 'contact-block';
    }
 
    include get_template_directory() . '/includes/block-options.php';
?>

<section id="<?php echo esc_attr($id); ?>" class="block contact <?= $bpt . ' ' . $bpb . ' ' . ' ' . $bg_colour . ' ' . $play_animation; ?>" data-scroll>
    <div class="container-xl">
        <div class="row justify-content-center text-lg-start">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="text-content">
                    <?php if($heading): ?>
                        <h2 class="heading "><?= $heading; ?></h2>
                    <?php endif; ?>
                    <?php if($intro): ?>
                        <div class="text ">
                            <?= wpautop($intro); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if( $phone || $email ): ?>
                    <div class="contact-wrap">
                        <?php if($phone): ?>
                            <div class="phone-wrap">
                                <div class="icon-wrap">
                                    <span class="material-icons">call</span>
                                </div>
                                <a href="tel:<?= str_replace(' ', '', $phone); ?>">Bookings <?= $phone; ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if($owner_phone): ?>
                            <div class="phone-wrap">
                                <div class="icon-wrap">
                                    <span class="material-icons">headset_mic</span>
                                </div>
                                <a href="tel:<?= str_replace(' ', '', $owner_phone); ?>"><?= $owner_phone; ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if($email): ?>
                            <div class="email-wrap">
                                <div class="icon-wrap">
                                    <span class="material-icons">email</span>
                                </div>
                                <a href="mailto:<?= $email; ?>"><?= $email; ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="cta-wrap">
                    <?php get_template_part( 'template-parts/cta-button', null, array( 'repeater' => false, 'submit' => false ) ); ?>
                </div>

            </div>
            
            <div class="col-12 col-md-6 col-lg-6">
                <?php if(!$show_form): ?>
                <div class="map-wrap">
                    <?php if($map_heading): ?>
                       <h4 class="heading"><?= $map_heading; ?></h4>
                    <?php endif; ?>

                    <?php if($address): ?>
                        <div class="contact-wrap location">
                            <div class="address-wrap">
                                <div class="icon-wrap">
                                    <span class="material-icons">location_on</span>
                                </div>
                                <div>
                                    <?php foreach( $address as $line ): ?>
                                        <span class="address"><?= $line['address_line']; ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div id="map-element" class="map"></div>

                </div>
                <?php else: ?>
                <div class="form-wrap">
                    <?php if($show_form && !$embed_form_code): ?>
                        <?php // get_template_part( 'template-parts/contact-form' ); ?>
                    <?php elseif($show_form && $embed_form_code): ?>
                        <?= $embed_form_code; ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <?php if(!$show_form): ?>

        <script type="text/javascript">

            function initMap() {

                const mapElement = document.getElementById( 'map-element' );

                let map;
                // Grab from General Settings
                const latLng = { lat: parseFloat( <?= $map_lat; ?> ), lng: parseFloat( <?= $map_long; ?> ) };

                const mapStyles =[
                        {
                            featureType: "poi",
                            elementType: "labels",
                            stylers: [
                                { 
                                    visibility: "off"
                                }
                            ]
                        }
                    ];

                if ( mapElement ) {
                    map = new google.maps.Map( mapElement, {
                        center: latLng,
                        zoom: 16,
                        disableDefaultUI: false,
                        mapId:'<?= $map_id; ?>',
                        // mapTypeId: 'satellite',
                        styles: mapStyles
                    } );

                    const icons = {
                        custom: {
                            icon: '<?= $marker_img; ?>',
                        },
                    }

                    let icon = false;

                    if( icons.custom.icon !== '' ) {
                        icon = icons.custom.icon;
                            let marker = new google.maps.Marker( {
                            position: { lat: parseFloat( <?= $map_lat; ?> ), lng: parseFloat( <?= $map_long; ?> ) },
                            title: '<?= $map_pin_title; ?>',
                            icon: icon,
                            map
                        } );
                    } else {
                            let marker = new google.maps.Marker( {
                            position: { lat: parseFloat( <?= $map_lat; ?> ), lng: parseFloat( <?= $map_long; ?> ) },
                            title: '<?= $map_pin_title; ?>',
                            map
                        } );
                    }

                }
            }

        </script>

        <script async src="https://maps.googleapis.com/maps/api/js?key=<?= $map_api; ?>&callback=initMap"></script>


    <?php endif; ?>
</section>
