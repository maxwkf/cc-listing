<?php
/*
   Block Name: Listing Summary Block
   Block Description: Listing Summary Block
   Internal: listing-summary-block
   Post Types: post, page, custom-type

*/

    // Create id attribute allowing for custom "anchor" value.
    $id = 'listing-summary-' . $block['id'];
    if( !empty($block['anchor']) ) {
        $id = $block['anchor'];
    }

    include get_template_directory() . '/includes/block-options.php';

    $heading = get_field('title');
    $price = get_field('price');
    $description = get_field('description');
    $video = get_field('video');
    $floor_plan = get_field('floor_plan');
    $sticky_title = get_field('sticky_title');
    $sticky_cta = get_field('sticky_cta');
    $icon_summary = get_field('icon_summary');
    $key_feature_icon = get_field('key_feature_icon');
    $feature_icon = get_field('feature_icon');
?>

<section id="<?php echo esc_attr($id); ?>" class="listing-summary block <?= $bpt . ' ' . $bpb; ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="listing-details">

                    <div class="heading-wrap">
                        <?php if($heading) : ?>
                            <h2 class="text-dark heading"><?= $heading; ?></h2>
                        <?php endif; ?>
                        <?php if($price) : ?>
                            <h2 class="text-slr-olive">£<?= number_format($price); ?></h2>
                        <?php endif; ?>
                    </div>

                    <div class="icon-wrap">
                        <?php if( $icon_summary ): ?>
                            <div class="icons">
                            <?php foreach( $icon_summary as $icons ) : 
                                $icon = $icons['icon'];
                                $text = $icons['text'];
                                ?>
                                <div class="icon">
                                    <?= $icon; ?>
                                    <span class="text text-dark"><?= $text; ?></span>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if($floor_plan): ?>
                            <a class="text-dark text floorplan" href="#floorplan">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/floor-icon.svg" alt="">Floorplan</a>
                        <?php endif; ?>
                    </div>

                    <?php if($description): ?>
                        <div class="text text-dark intro">
                            <?= wpautop($description); ?>
                        </div>
                    <?php endif; ?>

                    <div class="features-wrap">
                        <?php if( $key_feature_icon ): ?>
                            <h3 class="heading text-dark">Key features</h3>
                            <div class="features">
                                <div class="row">
                                    <?php foreach( $key_feature_icon as $feature) :
                                        $icon = $feature['icon'];
                                        $text = $feature['text'];
                                        ?>
                                        <div class="col-lg-3 col-md-4 col-6 wrapper">
                                            <div class="icon"><?= $icon; ?></div>
                                            <p><?= $text; ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="features-wrap">
                        <?php if( $feature_icon ): ?>
                            <h3 class="heading text-dark">Included as standard</h3>
                            <div class="features">
                                <div class="row">
                                    <?php foreach( $feature_icon as $feature) :
                                        $icon = $feature['icon'];
                                        $text = $feature['text'];
                                        ?>
                                        <div class="col-lg-3 col-md-4 col-6 wrapper">
                                            <div class="icon"><?= $icon; ?></div>
                                            <p><?= $text; ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if( $video ): ?>
                    <div class="video-wrap">
                        <h3 class="text-dark">Walkthrough Video</h3>
                        <video autoplay loop muted playsinline data-object-fit="cover">       
                                <source src="<?= $video; ?>">
                        </video>
                    </div>
                    <?php endif; ?>

                    <div id="floorplan" class="floor-plan">
                        <?php if($floor_plan) :?>
                            <h3 class="text-dark">Floorplan</h3>
                            <?= wp_get_attachment_image( $floor_plan, 'image-text' ); ?>
                        <?php endif; ?>
                    </div>

                </div>    
            </div>
            <div class="col-lg-4">
                <?php if($sticky_cta): ?>
                <div id="sticky-wrap" class="sticky-wrap">
                    <p><?= $sticky_title; ?></p>
                    <div class="btn-wrap">
                        <?php if($sticky_cta) : ?>
                            <a class="btn btn-primary" href="<?= $sticky_cta['url']; ?>" target="<?= $sticky_cta['target']; ?>">
                                <?= $sticky_cta['title']; ?>
                            </a>
                        <?php endif; ?>                
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>