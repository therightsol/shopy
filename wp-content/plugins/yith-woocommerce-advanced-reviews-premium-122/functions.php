<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!function_exists("yith_define")) {
    /**
     * Defined a constant if not already defined
     *
     * @param string $name The constant name
     * @param mixed $value The value
     */
    function yith_define($name, $value = true)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }
}

/**
 * Check if a jetpack module is currently active and try disabling before activating this one
 */
if (function_exists('yith_deactive_jetpack_module')) {
    global $yith_jetpack_1;
    yith_deactive_jetpack_module($yith_jetpack_1, 'YITH_YWAR_PREMIUM', plugin_basename(__FILE__));
}

if (!function_exists('is_plugin_active')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

if (!function_exists('yith_initialize_plugin_fw')) {
    function yith_initialize_plugin_fw($plugin_dir)
    {
        if (!function_exists('yit_deactive_free_version')) {
            require_once $plugin_dir . 'plugin-fw/yit-deactive-plugin.php';
        }

        if (!function_exists('yith_plugin_registration_hook')) {
            require_once $plugin_dir . 'plugin-fw/yit-plugin-registration-hook.php';
        }

        /* Plugin Framework Version Check */
        if (!function_exists('yit_maybe_plugin_fw_loader') && file_exists($plugin_dir . 'plugin-fw/init.php')) {
            require_once($plugin_dir . 'plugin-fw/init.php');
        }
    }
}

if (!function_exists('yith_ywar_install_woocommerce_admin_notice')) {

    function yith_ywar_install_woocommerce_admin_notice()
    {
        ?>
        <div class="error">
            <p><?php _e('YITH WooCommerce Advanced Reviews is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-advanced-reviews'); ?></p>
        </div>
    <?php
    }
}

if (!function_exists(' yith_maybe_script_minified_path')) {
    /**
     * Return the path to a minified file, if exists
     * @param $script_path string script path, without extension
     *
     * @return string the path to the resource to use
     */
    function yith_maybe_script_minified_path($script_path)
    {
        $maintenance = isset($_GET["script_debug_on"]);
        $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) || $maintenance ? '' : '.min';

        return $script_path . $suffix . '.js';
    }
}

