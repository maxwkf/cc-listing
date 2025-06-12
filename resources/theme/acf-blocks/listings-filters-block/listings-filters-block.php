<?php
/*
   Block Name: Listings Filters Block
   Block Description: Block Description
   Internal: listings-filters-block
   Post Types: post, page, custom-type
*/

$filters_icon = file_get_contents( get_template_directory_uri() . '/images/filters-icon.svg' );
$close_icon = file_get_contents( get_template_directory_uri() . '/images/close-icon.svg' );

$nonce = wp_create_nonce('filter_listings-' . get_the_ID());

$price_min = get_field('price_minimum', 'option');
$price_max = get_field('price_maximum', 'option');


$parkLocationField = (function($fieldName) {
    $specifications_group_id = 'group_650d9f2108c80'; // Replace with your group ID
    $specifications_fields      = array();

    $fields = acf_get_fields( $specifications_group_id );

    return current(array_filter($fields, function($field) use ($fieldName) {
        return $field['name'] === $fieldName;
    })) ?? null;
})('park_location');

$parkLocationOptions = $parkLocationField["choices"];

include get_template_directory() . '/includes/block-options.php';

?>

<section class="block listings <?= $bpt . ' ' . $bpb ; ?>">
    <div class="container-xl">
        <div class="row position-relative">
            <div class="col-12">
                <div class="heading-wrap">
                    <h3 class="heading">Our Listings:</h3>
                    <div class="filters-wrap">
                        <button class="filters-toggle">
                            <span class="icon"><?= $filters_icon; ?></span>    
                            Filter
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-10 col-lg-6 offset-md-1 offset-lg-3 filters-col">
                <div class="filters">
                    <form class="filters-form">

                    <input type="hidden" name="action" value="filter_listings" />
                        <input type="hidden" id="post_url" name="post_url" value="<?= admin_url('admin-ajax.php'); ?>" />
                        <input type="hidden" id="nonce" name="nonce" value="<?= $nonce; ?>" />
                        <input type="hidden" id="post_id" name="post_id" value="<?= get_the_ID(); ?>" />
                        <input type="hidden" id="target_page" name="target_page" value="1" />
                        <input type="hidden" id="price_min" name="price_min" value="<?= $price_min ?>" />
                        <input type="hidden" id="price_max" name="price_max" value="<?= $price_max ?>" />
                        
                        <div class="title-wrap">
                            <h2 class="title">Filter:</h2>
                            <button id="close-filters" class="close" type="button">
                                <span class="icon">
                                    <?= $close_icon; ?>
                                </span>
                            </button>
                        </div>

                        <div class="park-location-wrap">
                            <h3 class="heading">Park Location</h3>
                            <select class="form-select" id="park-location" name="park-location">
                                <option value="">Select a Park Location</option>
                                <?php foreach( $parkLocationOptions as $key => $name ) { ?>
                                    <option value="<?= $key ?>"><?= $name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="price-wrap">
                            <div class="price-heading">
                                <h3 class="heading">Price range</h3>
                                <div class="price-values">£
                                    <span id="price-values"></span>
                                </div>
                            </div>

                            <div id="price-slider"></div>
                        </div>
                            
                        <div class="categories">
                            <h3 class="heading">Category</h3>
                            <?php foreach( range(1,3) as $value) { ?>
                                <input class="btn-check" type="checkbox" name="bedrooms[]" id="bedroom-<?= $value ?>" value="<?= $value ?>" />
                                <label class="btn btn-primary white-text" for="bedroom-<?= $value ?>">Bedroom <?= $value ?></label>
                            <?php } ?>
                        </div>
                            
                        <div class="submit-wrap">
                            <button id="reset-filters" class="reset btn btn-secondary" type="button">Reset</button>
                            <input id="show-results" class="btn submit btn-primary" type="submit" value="Show results">
                        </div>

                    </form>
                </div>
            </div>

        </div>  
    </div>
    
    <div class="results">
        <div class="container-xl">
            
            <?php // Updated by AJAX  ?>
            <div id="no-results" class="row justify-content-center" style="display: none;">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="no-results">
                        <h3 class="heading">No results found - please adjust your search parameters.</h3>
                    </div>
                </div>
            </div>
            
            <div id="loader-holder" class="row loader-holder">
                <div class="col-12 col-md-8">
                    <div class="loader"></div>
                </div>
            </div>
                            
            <div id="listings-output" class="row"></div>
            <div id="pagination-output"></div>

        </div>
    </div>
    
</section>
