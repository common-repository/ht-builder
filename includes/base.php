<?php

namespace HT_Builder\Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Base {

    const MINIMUM_ELEMENTOR_VERSION = '2.5.0';
    const MINIMUM_PHP_VERSION = '5.4';

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );

        if ( did_action( 'elementor/loaded' ) ) {
            // After Active Plugin then redirect setting page
            register_activation_hook( HTBUILDER_PL_ROOT, [ $this, 'plugin_activate_hook'] );
            add_action('admin_init', [ $this, 'plugin_redirect_option_page' ] );
        }
    }

    /*
    * Load Text Domain
    */
    public function i18n() {
        load_plugin_textdomain( 'ht-builder' );
    }

    /*
    * Init Hook in Init
    */
    public function init() {

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // Include File
        $this->require_include_files();

        // Plugins Setting Page
        add_filter('plugin_action_links_'.HTBUILDER_PLUGIN_BASE, [ $this, 'plugins_setting_links' ] );

        // Register custom category
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

    }

    /*
    * Include File
    */
    public function require_include_files(){
        if ( ! function_exists('is_plugin_active') ){ include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }
        if( is_plugin_active('ht-builder-pro/ht-builder.php') ){
            require ( HTBUILDER_PL_PATH_PRO.'includes/admin/admin-setting.php' );
        }else{
            require( __DIR__ . '/admin/admin-setting.php');
        }
        require( __DIR__ . '/helper-function.php');
        require( __DIR__ . '/../classes/class.enqueue_scripts.php');
        require( __DIR__ . '/admin/template-library.php');
        if( htbuilder_get_option( 'enablecustomtemplate', 'htbuilder_templatebuilder_tabs', 'on' ) == 'on' ){
            require(  __DIR__ .'/../classes/class.template_builder.php' );
            require(  __DIR__ .'/../classes/class.widgets_control.php' );
            require(  __DIR__ .'/../classes/class.header_footer.php' );
        }

        if( is_admin() ){
            require(  __DIR__ .'/admin/recommended-plugins/recommendations.php' );
        }
    }

    /**
     * Add custom category.
     *
     * @param $elements_manager
     */
    public function add_category( $elements_manager ) {
        $elements_manager->add_category(
            'ht_builder',
            [
                'title' => __( 'HT Builder', 'ht-builder' ),
                'icon' => 'fa fa-snowflake-o',
            ]
        );
    }

    /**
     * Admin notice.
     * Doesn't have Elementor installed or activated.
     */
    public function admin_notice_missing_main_plugin() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $elementor = 'elementor/elementor.php';
        if( $this->is_plugins_active( $elementor ) ) {
            if( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor );
            $button_text = '<p><a href="' . $activation_url . '" class="button-primary">' . __( 'Activate Elementor', 'ht-builder' ) . '</a></p>';
        } else {
            if( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
            $button_text = '<p><a href="' . $activation_url . '" class="button-primary">' . __( 'Install Elementor', 'ht-builder' ) . '</a></p>';
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'ht-builder' ),
            '<strong>' . esc_html__( 'HT Builder', 'ht-builder' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'ht-builder' ) . '</strong>'
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message, $button_text );

    }

    /**
     * Admin notice.
     * Elementor required version.
     */
    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ht-builder' ),
            '<strong>' . esc_html__( 'HT Builder', 'ht-builder' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'ht-builder' ) . '</strong>',
             self::MINIMUM_ELEMENTOR_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice.
     * PHP required version.
     */
    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ht-builder' ),
            '<strong>' . esc_html__( 'HT Builder', 'ht-builder' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'ht-builder' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /*
    * Check Plugins is Installed or not
    */
    public function is_plugins_active( $pl_file_path = NULL ){
        $installed_plugins_list = get_plugins();
        return isset( $installed_plugins_list[$pl_file_path] );
    }

    /* 
    * Add settings link on plugin page.
    */
    public function plugins_setting_links( $links ) {
        $htbuilder_settings_link = '<a href="'.admin_url('admin.php?page=htbuilder').'">'.__( 'Settings', 'ht-builder' ).'</a>';
        array_unshift( $links, $htbuilder_settings_link );

        if( !is_plugin_active('ht-builder-pro/ht-builder.php') ){
            $links['htbgo_pro'] = sprintf('<a href="http://hasthemes.com/" target="_blank" style="color: #39b54a; font-weight: bold;">' . __('Go Pro','ht-builder') . '</a>');
        }
        return $links; 
    }

    /* 
    * Plugins After Install
    * Redirect Setting page
    */
    public function plugin_activate_hook() {
        add_option('htbuilder_do_activation_redirect', true);
    }
    public function plugin_redirect_option_page() {
        if ( get_option( 'htbuilder_do_activation_redirect', false ) ) {
            delete_option('htbuilder_do_activation_redirect');
            if( !isset( $_GET['activate-multi'] ) ){
                wp_redirect( admin_url('admin.php?page=ht-builder_extensions') );
            }
        }
    }



}
Base::instance();