<?php
// Using anonymous function here can restrict parameter requirements
// Developer can easily see required parameters at a glance
(function() use ($count, $target_page, $per_page) {
    
    $pages = ceil($count / $per_page);
?>

<div class="listings-pagination row justify-content-center">
    <div class="col-12 col-md-8">
        <?php if ($target_page != 1) : ?>
            <span class="prev page-numbers" data-page="<?= $target_page - 1 ?>">&lt;</span>
        <?php endif; ?>

        <?php for ($x = 1; $x <= $pages; $x++) : ?>
            <?php if ($x == $target_page) : ?>
                <span aria-current="page" class="page-numbers current"><?= $x ?></span>
            <?php else : ?>
                <span class="page-numbers" data-page="<?= $x ?>"><?= $x ?></span>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($target_page != $pages) : ?>
            <span class="next page-numbers" data-page="<?= $target_page + 1 ?>">&gt;</span>
        <?php endif; ?>
    </div>
</div>

<?php })(); ?>