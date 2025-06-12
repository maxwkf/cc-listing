<?php 
    /*
        Block Name: Image Gallery Block
        Block Description: Image Gallery Block
        Internal: image-gallery-block
        Post Types: post, page, custom-type
    */

    // Create id attribute allowing for custom "anchor" value.
    $id = 'image-gallery-' . $block['id'];
    if( !empty($block['anchor']) ) {
        $id = block['anchor'];
    }

    include get_template_directory() . '/includes/block-options.php';

    $images = get_field('images');
?>

<?php if($images): ?>
    <section id="<?php echo esc_attr($id); ?>" class="rental-image-gallery block bg-light-grey <?= $bpt . ' ' . $bpb; ?>">
        <div class="container">
            <div class="gallery-wrap">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="img-wrap lg-img-col">
                            <a class="img-lightbox-link" href="<?= $images[0]['url']; ?>" rel="lightbox" aria-hidden="true">
                                <img src="<?= $images[0]['url']; ?>" alt="<?= $images[0]['alt']; ?>">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="swiper rental-carousel-mobile swiper-cont">
                            <div class="img-grid swiper-wrapper">
                                <?php
                                    $i = 0;
                                    $image_count = count($images);
                                ?>

                                <?php foreach($images as $img): ?>
                                <div class="swiper-slide img-wrap">
                                    <a class="img-lightbox-link" href="<?= $img['url']; ?>" rel="lightbox" aria-hidden="true">
                                        <img src="<?= $img['url']; ?>" alt="<?= $img['alt']; ?>">
                                    </a>
                                </div>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="swiper-pagination"></div>
                            <?php
                                $photos_text = 'photos';
                                if( $image_count - 4 === 1 ) {
                                    $photos_text = 'photo';
                                }
                            ?>
                            <?php if( $i > 4 ): ?>
                                <div class="more-photos">
                                    <div class="wrap">
                                        <span class="image-count">+<?= $image_count - 4 . ' ' . $photos_text; ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>