<?php
class CcListing {
    public function __construct() {
        register_activation_hook( CC_LISTING_PLUGIN_FILE, [ $this, 'plugin_activation' ] );
        register_deactivation_hook( CC_LISTING_PLUGIN_FILE, [ $this, 'plugin_deactivation' ] );
        $this->_initialize();
    }

    
    private function _initialize() {
        $this->_init_scripts_and_styles_hook();
        $this->_init_custom_post_type();
        $this->_init_ajax_functions();
    }

    /**
     * Plugin activation hook need to be public or it cannot be called
     */
    public function plugin_activation() {
        $this->_create_acf_blocks();
    }

    /**
     * Plugin deactivation hook need to be public or it cannot be called
     */
    public function plugin_deactivation() {
    }


    public function filter_listings() {
        @ini_set( 'display_errors', 1 );
        // block operations if nonce not correct
        if (check_ajax_referer('filter_listings-' . $_POST['post_id'], 'nonce', false) == false) {
            wp_send_json_error('invalid_key', 'filter_listings');
        }

        $per_page = get_field('listings_per_page', 'option') ?? 9;
        $target_page = esc_attr($_POST['target_page']) ?? 1;

        $query = $this->_filter_build_query();

        $search_result = $this->_filter_get_search_result($query, $target_page, $per_page);

        $result = empty($search_result) ? 'no_results' : $search_result;

        wp_send_json_success($result, 'filter_listings');
    }

    private function _init_ajax_functions() {
        // AJAX form handlers
        add_action('wp_ajax_nopriv_filter_listings', [$this, 'filter_listings']);
        add_action('wp_ajax_filter_listings', [$this, 'filter_listings']);
    }

    private function _init_scripts_and_styles_hook() {
        add_action('wp_enqueue_scripts', function ($hook) {
        
            foreach ([
                // ['handle' => 'no-ui-slider',				'src' => 'node_modules/nouislider/dist/nouislider.min.js' ],
                // ['handle' => 'listing-ajax-controller',		'src' => 'resources/js/listing-ajax-controller.js'],
                // ['handle' => 'listing-filter-controller',	'src' => 'resources/js/listing-filter-controller.js'],
                // ['handle' => 'core',						'src' => 'resources/js/core.js', 'deps' => ['jquery']],
            ] as $detail) {
                $version  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . $detail['src'] ));
                wp_enqueue_script("@cc-listing/{$detail['handle']}", plugins_url( $detail['src'], __FILE__ ), $detail['deps'] ?? [], $version);
            }
        
            foreach([
                // ['handle' => 'no-ui-slider',				'src' => 'node_modules/nouislider/dist/nouislider.min.css' ],
            ] as $detail) {
                $version  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . $detail['src'] ));
                wp_enqueue_style("@cc-listing/{$detail['handle']}", plugins_url( $detail['src'], __FILE__ ), $detail['deps'] ?? [], $version);
            }
        });
    }

    private function _init_custom_post_type() {
        add_action(
            hook_name: 'init',
            callback: function() {
                $internal = 'listing';
                $singular_name = 'Listing';
                $plural_name = 'Listings';
                $description = 'A custom post type for listings';
                $taxs = [];
                $supports = ['title', 'thumbnail', 'revisions','excerpt','author'];

                return register_post_type(
                    $internal,
                    [
                        'description' => __($description),
                        'labels' => [
                            'name'          => __($plural_name),
                            'singular_name' => __($singular_name),
                            'add_new'       => __('Add New ' . $singular_name),
                            'add_new_item'  => __('Add A New ' . $singular_name),
                            'edit_item'     => __('Edit ' . $singular_name),
                            'new_item'      => __('New ' . $singular_name),
                            'view_item'     => __('View ' . $singular_name),
                            'view_items'    => __('View ' . $plural_name),
                            'search_items'  => __('Search ' . $plural_name),
                            'not_found'     => __('No '. $plural_name . ' found'),
                            'all_items'     => __('All ' . $plural_name)
                        ],
                        'heirarchical' => false,
                        'public' => true,
                        'has_archive' => true,
                        'taxonomies' => $taxs,
                        'menu_icon' => 'dashicons-welcome-view-site',
                        'supports' => $supports
                    ]
                );
            },
            priority: 10,
            accepted_args: 1
        );
    }

    private function _filter_generate_pagination($count, $target_page, $per_page, $query) {
        ob_start();
        include(locate_template('includes/pagination-partial.php', false, false));
        return ob_get_clean();
    }

    private function _filter_get_search_result(WP_Query $query, $target_page, $per_page) {
        if (!$query->have_posts()) {
            return null;
        }

        $result = ['cards' => '', 'paginator' => ''];
        $count = 0;

        while ($query->have_posts()) {
            $query->the_post();

            $count++;
            if (($count >= (($target_page * $per_page) - ($per_page - 1))) && ($count <= ($target_page * $per_page))) {
                $result['cards'] .= $this->_filter_generate_card();
            }
        }
        $result['paginator'] .= $this->_filter_generate_pagination($count, $target_page, $per_page, $query);

        return $result;
    }

    private function _filter_generate_card() {
        ob_start();
        echo '<div class="col-12 col-md-6 col-lg-4">';
        include(locate_template('includes/listing-card-partial.php', false, false));
        echo '</div>';
        return ob_get_clean();
    }

    private function _filter_build_query() {
        $data = $_POST;

        $base_query = (function() use ($data) {
            // The value in option is not used as default value as it might have not been set
            $price_min = empty($data['price_min']) ? 0 : $data['price_min'];
            $price_max = empty($data['price_max']) ? 1000000 : $data['price_max'];

            $price_query = [
                'key'       => 'price',
                'value'     => [$price_min, $price_max],
                'compare'   => 'BETWEEN',
                'type'      => 'NUMERIC'
            ];

            $bedroom_query = empty($data['bedrooms']) ? [] : [
                'key'       => 'bedrooms',
                'value'     => $data['bedrooms'],
                'compare'   => 'IN',
                'type'      => 'NUMERIC'
            ];

            // $double_glazed_windows_query = empty($data['double_glazed_windows']) ? [] : [
            //     'key'       => 'double_glazed_windows',
            //     'value'     => 1,
            //     'compare'   => '='
            // ];

            $combined_query_array = array_filter([$price_query, $bedroom_query, ]);
            return $combined_query_array ? $combined_query_array : [];
        })();



        // $showering_query = (function () use ($data) {
        //     $bathroom_query = empty($data['bathroom']) ? [] : [
        //         'key'       => 'bathrooms',
        //         'value'     => 0,
        //         'compare'   => '>',
        //         'type'      => 'NUMERIC'
        //     ];

        //     $shower_room_query = empty($data['shower_room']) ? [] : [
        //         'key'       => 'shower_room',
        //         'value'     => 1,
        //         'compare'   => '='
        //     ];

        //     $combined_query_array = array_filter([$bathroom_query, $shower_room_query]);

        //     return $combined_query_array ? [['relation' => 'OR', ...$combined_query_array]] : [];
        // })();

        // $heating_query = (function() use ($data) {
        //     $electric_heating_query = empty($data['electric_heating']) ? [] : [
        //         'key'       => 'heating_group_electric_heating',
        //         'value'     => 1,
        //         'compare'   => '='
        //     ];

        //     $gas_heating_query = empty($data['gas_heating']) ? [] : [
        //         'key'       => 'heating_group_gas_heating',
        //         'value'     => 1,
        //         'compare'   => '='
        //     ];

        //     $combined_query_array = array_filter([$electric_heating_query, $gas_heating_query]);

        //     return $combined_query_array ? [['relation' => 'OR', ...$combined_query_array]] : [];
        // })();

        // $kitchen_query = (function() use ($data) {
        //     $electric_cooker_query = empty($data['electric_cooker']) ? [] : [
        //         'key'       => 'kitchen_group_electric_cooker',
        //         'value'     => 1,
        //         'compare'   => '='
        //     ];

        //     $gas_cooker_query = empty($data['gas_cooker']) ? [] : [
        //         'key'       => 'kitchen_group_gas_cooker',
        //         'value'     => 1,
        //         'compare'   => '='
        //     ];

        //     $combined_query_array = array_filter([$electric_cooker_query, $gas_cooker_query]);

        //     return $combined_query_array ? [['relation' => 'OR', ...$combined_query_array]] : [];
        // })();

        $metakey = 'price';
        $orderby = 'meta_value_num';
        $orderdir = 'ASC';

        $args = [
            'post_type'		    => 'listing',
            'posts_per_page'    => -1,
            'no_found_rows'     => true,
            'meta_key'          => $metakey,
            'orderby'           => $orderby,
            'order'             => $orderdir,
            'post_status'       => 'publish',
            'relation'      => 'AND',
            'meta_query'        => [
                ...$base_query,
                // ...$showering_query,
                // ...$heating_query,
                // ...$kitchen_query
            ]
        ];

        return new WP_Query($args);
    }

    private function _create_acf_blocks() {
        $this->_custom_copy(
            src: CC_LISTING_PLUGIN_DIR . 'resources/theme',
            dst: CC_THEME_ROOT
        );

        // $this->_add_to_ajax_function();

        $this->_update_main_js();
        $this->_update_styles_scss();
    }

    private function _update_main_js() {
        $main_js_path = get_theme_root() . '/cc_theme/src/js/main.js';
        $main_js = file_get_contents($main_js_path);
        
        $new_content = (function() use ($main_js) {
            $result = $main_js;
            if (strpos($main_js, 'import listingsFiltersBlock from') === false) {
                $result = str_replace('// Block Specific JS Files', "// Block Specific JS Files\nimport listingsFiltersBlock from '../../acf-blocks/listings-filters-block/listings-filter-block';  // created via cc-listing plugin", $result);
            }
            if (strpos($main_js, 'listingsFiltersBlock') === false) {
                $result = str_replace('mainNav();', "mainNav();\n\tlistingsFiltersBlock();  // created via cc-listing plugin", $result);
            }
            return $result;

        })();

        file_put_contents($main_js_path, $new_content);
    }

    private function _update_styles_scss() {
        $styles_scss_path = get_theme_root() . '/cc_theme/src/scss/styles.scss';
        $styles_scss = file_get_contents($styles_scss_path);

        $new_content = (function() use ($styles_scss) {
            $result = $styles_scss;
            if (strpos($styles_scss, 'listing-filters-block.scss') === false) {
                $result = str_replace('@import "./blocks-global.scss";', "@import \"./blocks-global.scss\";\n@import \"../../acf-blocks/listings-filters-block/listing-filters-block.scss\";  // created via cc-listing plugin", $result);
            }
            return $result;
        })();

        file_put_contents($styles_scss_path, $new_content);
    }

    private function _remove_acf_blocks() {

    }

    /**
     * https://www.geeksforgeeks.org/copy-the-entire-contents-of-a-directory-to-another-directory-in-php/
     */
    private function _custom_copy($src, $dst) {

        // open the source directory
        $dir = opendir($src);

        // Make the destination directory if not exist
        @mkdir($dst);

        // Loop through the files in source directory
        foreach (scandir($src) as $file) {

            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) )
                {

                    // Recursively calling custom copy function
                    // for sub directory
                    $this->_custom_copy($src . '/' . $file, $dst . '/' . $file);

                }
                else {
                    $dest_file = $dst . '/' . $file;
                    // only copy file when file not exists to avoid overwriting
                    if (!file_exists($dest_file)) {
                        copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
        }

        closedir($dir);
    }

}