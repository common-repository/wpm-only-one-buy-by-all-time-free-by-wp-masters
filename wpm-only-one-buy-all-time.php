<?php
/*
 * Plugin Name: One-Time Products Purchases for Woo - Free by WP Masters
 * Plugin URI: https://wp-masters.com/products/wpm-only-one-buy-all-time
 * Description: Restricts purchases to only one product per user per lifetime. Configurable.
 * Author: WP-Masters
 * Text Domain: wpm-only-one-buy-all-time
 * Author URI: https://wp-masters.com/
 * Version: 1.0.0
 *
 * @author      WP-Masters
 * @version     v.1.0.0 (18/07/22)
 * @copyright   Copyright (c) 2022
*/

define('PLUGIN_ONLY_ONE_BUY_ID', 'only-one-buy-all-time');
define('PLUGIN_ONLY_ONE_BUY_PATH', plugins_url('', __FILE__));

class WPM_OnlyOneBuyAllTime
{
    private $settings;

    /**
     * Initialize functions
     */
    public function __construct()
    {
        // Init Functions
        add_action('init', [$this, 'save_settings']);
        add_action('init', [$this, 'load_settings']);
        add_action('save_post', [$this, 'save_result']);

        // Admin menu
        add_action('admin_menu', [$this, 'register_menu']);

        // Include Styles and Scripts
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts_and_styles']);

        // WooCommerce Functions
        add_action('woocommerce_before_calculate_totals', [$this, 'change_cart_item_quantities'], 99, 1);
        add_action('woocommerce_product_options_general_product_data', [$this, 'add_general_option']);
        add_filter('woocommerce_add_to_cart_validation', [$this, 'validate_add_cart_item'], 10, 5);
    }

    /**
     * Save Core Settings to Option
     */
    public function save_settings()
    {
        if (isset($_POST['only_one_buy']) && is_array($_POST['only_one_buy'])) {
            $data = $this->sanitize_array($_POST['only_one_buy']);

            update_option('only_one_buy', serialize($data));
        }
    }

    /**
     * Load Saved Settings
     */
    public function load_settings()
    {
        $this->settings = unserialize(get_option('only_one_buy'));
    }

    /**
     * CHeck if item in the Storage
     */
    public function validate_add_cart_item($passed, $product_id, $quantity, $variation_id = '', $variations = '' )
    {
        // Check is Limited product
        $product = wc_get_product($product_id);
        $is_limited = get_post_meta($product_id, 'buy_once', true);

        if(is_user_logged_in() && $is_limited == '1' && $product->get_type() != 'subscription' || isset($this->settings['work_mode']) && $this->settings['work_mode'] == '1' && $product->get_type() != 'subscription') {

            // Get all customer orders
            $customer_orders = get_posts(array(
                'numberposts' => -1,
                'meta_key' => '_customer_user',
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_value' => get_current_user_id(),
                'post_type' => wc_get_order_types(),
                'post_status' => ['wc-completed', 'wc-processing', 'wc-processing', 'wc-on-hold']
            ));

            $already_ordered_ids = [];
            foreach ($customer_orders as $customer_order) {
                $order = wc_get_order($customer_order);
                foreach($order->get_items() as $item) {
                    $already_ordered_ids[] = $item->get_product_id();
                }
            }

            // Check Current stocks is not bigger than ordered
            if(in_array($product_id, $already_ordered_ids)) {
                $passed = false;
                wc_add_notice(__( "You have already purchased this product, can't add it to cart.", 'wpm_only_one_buy'), 'error');
            }
        }

        return $passed;
    }

    /**
     * Add Checkbox option to Set Product is can be ordered once
     */
    public function add_general_option()
    {
        $buy_once = get_post_meta($_GET['post'], 'buy_once', true);
        ?>
        <p class="form-field">
            <input type="checkbox" name="buy_once" id="buy_once" value='1' <?php if($buy_once == '1') {echo esc_attr('checked');}?>>
            <label for="buy_once">Can Buy Product only Once</label>
        </p>
        <?php
    }

    /**
     * Save meta
     *
     * @param $post_id
     */
    public function save_result($post_id)
    {
        if(isset($_POST['buy_once'])) {
            update_post_meta($post_id, 'buy_once', '1');
        } else {
            update_post_meta($post_id, 'buy_once', '0');
        }
    }

    /**
     * Sanitize Array Data
     */
    public function sanitize_array($data)
    {
        $filtered = [];
        foreach($data as $key => $value) {
            if(is_array($value)) {
                foreach($value as $sub_key => $sub_value) {
                    $filtered[$key][$sub_key] = sanitize_text_field($sub_value);
                }
            } else {
                $filtered[$key] = sanitize_text_field($value);
            }
        }

        return $filtered;
    }

    /**
     * Change Products Quantity to 1
     */
    public function change_cart_item_quantities($cart)
    {
        $already_ordered_ids = [];
        if(is_user_logged_in()) {
            // Get all customer orders
            $customer_orders = get_posts(array(
                'numberposts' => -1,
                'meta_key' => '_customer_user',
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_value' => get_current_user_id(),
                'post_type' => wc_get_order_types(),
                'post_status' => ['wc-completed']
            ));

            foreach ($customer_orders as $customer_order) {
                $order = wc_get_order($customer_order);
                foreach($order->get_items() as $item) {
                    $already_ordered_ids[] = $item->get_product_id();
                }
            }
        }

        // Set Quantity to All Products in Cart
        foreach($cart->get_cart() as $cart_item_key => $cart_item) {
            $is_limited = get_post_meta($cart_item['product_id'], 'buy_once', true);

            if(in_array($cart_item['product_id'], $already_ordered_ids)) {
                $cart->remove_cart_item($cart_item_key);
            } elseif($is_limited == "1" && $cart_item['quantity'] > 1 || isset($this->settings['work_mode']) && $this->settings['work_mode'] == '1' && $cart_item['quantity'] > 1) {
                $cart->set_quantity($cart_item_key, 1);
            }
        }
    }

    /**
     * Include Scripts And Styles on Admin Pages
     */
    public function admin_scripts_and_styles()
    {
        // Register styles
        wp_enqueue_style(PLUGIN_ONLY_ONE_BUY_ID.'-font-awesome', plugins_url('templates/libs/font-awesome/scripts/all.min.css', __FILE__));
        wp_enqueue_style(PLUGIN_ONLY_ONE_BUY_ID.'-tips', plugins_url('templates/libs/tips/tips.css', __FILE__));
        wp_enqueue_style(PLUGIN_ONLY_ONE_BUY_ID.'-admin', plugins_url('templates/assets/css/admin.css', __FILE__));

        // Register Scripts
        wp_enqueue_script(PLUGIN_ONLY_ONE_BUY_ID.'-font-awesome', plugins_url('templates/libs/font-awesome/scripts/all.min.js', __FILE__));
        wp_enqueue_script(PLUGIN_ONLY_ONE_BUY_ID.'-tips', plugins_url('templates/libs/tips/tips.js', __FILE__));
        wp_enqueue_script(PLUGIN_ONLY_ONE_BUY_ID.'-admin', plugins_url('templates/assets/js/admin.js', __FILE__));
    }

    /**
     * Add Settings to Admin Menu
     */
    public function register_menu()
    {
        add_menu_page('Only One Buy', 'Only One Buy', 'edit_others_posts', 'wpm_only_one_buy_settings');
        add_submenu_page('wpm_only_one_buy_settings', 'Only One Buy', 'Only One Buy', 'manage_options', 'wpm_only_one_buy_settings', function ()
        {
            global $wp_version, $wpdb;

            // Get Saved Settings
            $settings = $this->settings;

            include 'templates/admin/settings.php';
        });
    }
}

new WPM_OnlyOneBuyAllTime();