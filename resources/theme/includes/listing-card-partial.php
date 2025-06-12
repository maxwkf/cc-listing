<?php
(function() {
    $image = get_field('images', get_the_ID());
    $icon_summary = get_field('icon_summary', get_the_ID());
    $title = get_field('title', get_the_ID());
    $price = get_field('price', get_the_ID());
    $description = get_field('description', get_the_ID());
    $card_banner = get_field('card_banner', get_the_ID());
    $button_title = get_field('button_title', get_the_ID());

?>
<div class="card">
    <?php if($image): ?>
        <div class="img-wrap">
            <?php if($card_banner): ?>
                <div class="card-banner <?= $card_banner['value']; ?>">
                    <span><?= $card_banner['label']; ?></span>
                </div>
            <?php endif; ?>
            <img src="<?= $image[0]['url']; ?>" alt="<?= $image[0]['alt']; ?>">
        </div>
    <?php endif; ?>
    <div class="card-content">
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
        <h3><?= $title ?></h3>

        <?php if ( !in_array( $card_banner['value'], ['under-offer', 'sold'] ) ) : ?>
            <h3>£<?= number_format(intval($price)); ?></h3>
        <?php endif; ?>

        <?php if($description): ?>
            <p class="short-description"><?= $description; ?></p>
        <?php endif; ?>

        <a class="btn btn-primary" href="<?php echo get_the_permalink(); ?>"><?= $button_title ? $button_title : 'View Lodge' ?></a>

    </div>
    
</div>

<?php })(); ?>
