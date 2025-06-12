<?php
/*
   Block Name: Share Block
   Block Description: Share Block
   Internal: share-block
   Post Types: post, page, news
*/
    $play_animation = get_field('play_animation');

    if($play_animation) {
        $play_animation = 'has-animation';
    }


    // Create id attribute allowing for custom "anchor" value.
    $id = 'share-' . $block['id'];
    if( !empty($block['anchor']) ) {
        $id = $block['anchor'];
    }


    $post_type = 'listing';

    $get_post_type = get_post_type();

    $title = get_field('title');
    $price = get_field("price");

?>

<section id="<?php echo esc_attr($id); ?>" class="block share <?= $play_animation ; ?>" data-scroll>
    <div class="container">
        <div class="wrapper">
            <?php if( $get_post_type === 'news' ): ?>
                <p><a class="link" href="/news/">Back to News</a></p>
            <?php else: ?>
                <p><a class="link" href="/holiday-homes-caravans-for-sale/">Back to listings</a></p>
            <?php endif; ?>


            <!-- Button trigger modal -->
            <button type="button" class="share-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <span class="material-symbols-outlined">share</span>
                Share
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Share this place</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="title-wrap">
                                <?php if($title) : ?>
                                    <p><?php echo $title; ?></p>
                                <?php endif; ?>
                                <?php if($price): ?>
                                    <p><?php echo number_format($price); ?></p>
                                <?php endif; ?>
                            </div>
                            <ul class="social-icons">
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>" target="_blank">
                                        <img src="<?php echo get_template_directory_uri() . '/images/icons/facebook.svg' ;?>" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?text=<?php echo get_the_permalink(); ?>" target="_blank">
                                        <img src="<?php echo get_template_directory_uri() . '/images/icons/twitter.svg' ;?>" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_the_permalink(); ?>" target="_blank">
                                        <img src="<?php echo get_template_directory_uri() . '/images/icons/linkedin.svg' ;?>" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="https://api.whatsapp.com/send?text=<?php echo get_the_permalink(); ?>" target="_blank">
                                        <img src="<?php echo get_template_directory_uri() . '/images/icons/whatsapp.svg' ;?>" alt="">
                                    </a>
                                </li>
                                
                            </ul>
                            <h3>Copy URL</h3>
                            <form class="copy">
                                <input type="text" value="<?php echo get_the_permalink(); ?>">
                                <button type="button">Copy url</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>