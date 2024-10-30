<?php

namespace HT_Builder\Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 /**
 * Scripts Manager
 */
 class HTBuilder_Scripts{

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        $this->init();
    }

    public function init() {

        // Register Scripts
        add_action( 'init', [ $this, 'register_scripts' ] );

        // Frontend Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );

        // Editor Scripts
        add_action( 'elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_scripts'] );
    }

    /**
    * Register Scripts
    */

    public function register_scripts(){
        wp_register_script(
            'goodshare',
            HTBUILDER_PL_URL . 'assets/js/goodshare.min.js',
            array('jquery'),
            HTBUILDER_VERSION,
            TRUE
        );
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {

        // CSS
        wp_enqueue_style(
            'htbuilder-main',
            HTBUILDER_PL_URL . 'assets/css/htbuilder.css',
            NULL,
            HTBUILDER_VERSION
        );

        // JS
        wp_enqueue_script( 'masonry' );
        wp_enqueue_script(
            'htbuilder-main',
            HTBUILDER_PL_URL . 'assets/js/htbuilder.js',
            array('jquery'),
            HTBUILDER_VERSION,
            TRUE
        );

    }

    /**
     * Enqueue Editor scripts
     */
    public function enqueue_editor_scripts(){}

}

HTBuilder_Scripts::instance();