<?php

namespace HT_Builder\Elementor\HeaderFooter;
use Elementor\Plugin as Elementor;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*  HT Builder Header and Footer
*/
class HTBuilder_Header_Footer{

    public $header_id = '';
    public $footer_id = '';

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function __construct(){

        // Theme Support
        add_action( 'after_setup_theme', [ $this, 'theme_setup' ] );

        // Register Control Document
        add_action( 'elementor/documents/register_controls', [ $this, 'add_elementor_page_settings_controls'], 10, 1 );

        // Init Hook in wp
        add_action( 'wp', [ $this, 'init' ] );
    }

    /*
    * WP Hooks Init
    */
    public function init() {

        // Header Id
        if( !empty( htbuilder_get_elementor_setting( 'header_template', get_the_ID() ) ) ){
            $this->header_id = htbuilder_get_elementor_setting( 'header_template', get_the_ID() );
        }else{
            $this->header_id = htbuilder_get_option( 'header_page', 'htbuilder_templatebuilder_tabs', '0' );
        }

        // Footer id
        if( !empty( htbuilder_get_elementor_setting( 'footer_template', get_the_ID() ) ) ){
            $this->footer_id = htbuilder_get_elementor_setting( 'footer_template', get_the_ID() );
        }else{
            $this->footer_id = htbuilder_get_option( 'footer_page', 'htbuilder_templatebuilder_tabs', '0' );
        }

        // Content Hooks
        add_action( 'htbuilder_header_content', [ $this, 'header_content_elementor' ], 999999 );
        add_action( 'htbuilder_footer_content', [ $this, 'footer_content_elementor' ], 999999 );

        // Header Template Ovewrite
        if ( ! empty( $this->header_id ) ) {
            add_action( 'get_header', [ $this, 'get_header' ] );
        }

        // Footer Template Ovewrite
        if( ! empty( $this->footer_id )  ){
            add_action( 'get_footer', [ $this, 'get_footer' ] );
        }

    }

    /*
    * Elementor Page Document Setting Add
    */
    public function add_elementor_page_settings_controls( $page ) {

        $page->start_controls_section(
            'section_header_footer',
            [
                'label' => __( 'HT Header & Footer', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

            $page->add_control(
                'header_template',
                [
                    'label' => __( 'Header Template', 'ht-builder' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => htbuilder_elementor_template(),
                    'label_block'=>true,
                ]
            );

            $page->add_control(
                'footer_template',
                [
                    'label' => __( 'Footer Template', 'ht-builder' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '0',
                    'options' => htbuilder_elementor_template(),
                    'label_block'=>true,
                    'separator'=>'before',
                ]
            );

        $page->end_controls_section();

    }

    /*
    * After Theme Setup
    */
    public function theme_setup() {
        add_theme_support( 'custom-logo', array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ) );
    }

    /*
    * Header Content Overwrite to Custom template.
    */
    public function get_header( $name ) {
        require ( __DIR__ . '/../templates/theme-header.php' );

        $templates = [];
        $name = (string) $name;
        if ( '' !== $name ) {
            $templates[] = "header-{$name}.php";
        }
        $templates[] = 'header.php';

        // Avoid wp_head hooks
        remove_all_actions( 'wp_head' );
        ob_start();
        // Overwrite All Header Templates.
        locate_template( $templates, true );
        ob_get_clean();
    }

    /*
    * Footer Content Overwrite to Custom Template.
    */
    public function get_footer( $name ) {
        require ( __DIR__ . '/../templates/theme-footer.php' );

        $templates = [];
        $name = (string) $name;
        if ( '' !== $name ) {
            $templates[] = "footer-{$name}.php";
        }
        $templates[] = 'footer.php';

        ob_start();
        // Overwrite All Footer Templates.
        locate_template( $templates, true );
        ob_get_clean();
    }

    /* 
    * Render Elementor Header Content
    */
    public function header_content_elementor() {
        $templateid = $this->header_id;
        if( !empty( $templateid ) ){
            echo Elementor::instance()->frontend->get_builder_content_for_display( $templateid );
        }
    }

    /* 
    * Render Elementor Header Content
    */
    public function footer_content_elementor() {
        $templateid = $this->footer_id;
        if( !empty( $templateid ) ){
            echo Elementor::instance()->frontend->get_builder_content_for_display( $templateid );
        }
    }

}

HTBuilder_Header_Footer::instance();