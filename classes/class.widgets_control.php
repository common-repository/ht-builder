<?php

namespace HT_Builder\Elementor;
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Controls_Stack;
use Elementor\Plugin as Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Widgets Control
*/
class HTBuilder_Widgets_Control{

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

    // Widgets Initialize
    public function init() {

        // Add Plugin actions
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
    }

    // Widgets Register
    public function register_widgets() {

        $bl_element  = array();
        $element_manager = array();

        // Builder Element
        if( htbuilder_get_option( 'enablecustomtemplate', 'htbuilder_templatebuilder_tabs', 'on' ) == 'on' ){
            $bl_element  = array(
                'bl_post_title',
                'bl_post_featured_image',
                'bl_post_meta_info',
                'bl_post_excerpt',
                'bl_post_content',
                'bl_post_comments',
                'bl_post_search_form',
                'bl_post_archive',
                'bl_post_archive_title',
                'bl_page_title',
                'bl_site_title',
                'bl_site_logo',
                'bl_nav_menu',
                'bl_post_author_info',
            );
        }
        $element_manager = array_merge( $element_manager, $bl_element );

        // Include Widget files
        foreach ( $element_manager as $element ){
            if (  ( htbuilder_get_option( $element, 'htbuilder_element_tabs', 'on' ) === 'on' ) && file_exists(HTBUILDER_PL_PATH.'includes/widgets/'.$element.'.php' ) ){
                require( HTBUILDER_PL_PATH.'includes/widgets/'.$element.'.php' );
                $class_name = 'HT_Builder\Elementor\Widget\\'.$element.'_ELement';
                Elementor::instance()->widgets_manager->register_widget_type( new $class_name() );
            }
        }

    }

}

HTBuilder_Widgets_Control::instance();