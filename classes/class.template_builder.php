<?php
namespace HT_Builder\Elementor;
use Elementor\Plugin as Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTBuilder_Custom_Template_Layout{
    
    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
        add_action('init', array( $this, 'init' ) );
    }

    /*
    * init Hooks init
    */
    public function init(){

        // Single template
        add_filter( 'template_include', array( $this, 'change_template' ), 999 );
        add_action( 'htbuilder_single_blog_content', array( $this, 'single_blog_content_elementor' ), 999 );

        // Archive Template
        add_action( 'htbuilder_blog_content', array( $this, 'blog_content_elementor' ), 999 );

    }

    /*
    * Change template
    */
    public function change_template( $template ) {

        if ( is_embed() ) { return $template; }

        // Custom Template id
        $single_tm_id = $this->custom_template_id( 'single_blog_page' );
        $archive_tm_id = $this->custom_template_id( 'archive_blog_page' );
        
        // Template Slug
        $singletemplateid = get_page_template_slug( $single_tm_id );
        $archivetemplateid = get_page_template_slug( $archive_tm_id );

        // Elementor template type
        $single_elementor_template = get_post_meta($single_tm_id, '_wp_page_template', true); 
        $archive_elementor_template = get_post_meta($archive_tm_id, '_wp_page_template', true); 

        // Single Page
        if ( is_singular( 'post' ) && !empty( $single_tm_id ) ) {
            if ( 'elementor_header_footer' === $single_elementor_template ) {
                $template = HTBUILDER_PL_PATH . 'templates/single-fullwidth.php';
            } elseif ( 'elementor_canvas' === $single_elementor_template ) {
                $template = HTBUILDER_PL_PATH . 'templates/single-canvas.php';
            } else {
                $template = HTBUILDER_PL_PATH . 'templates/single-fullwidth.php';
            }
        }

        // Archive page
        elseif( ( is_post_type_archive( 'post' ) || htbuilder_is_blog_page() ) && !empty( $archive_tm_id ) ){
            if ( 'elementor_header_footer' === $archive_elementor_template ) {
                $template = HTBUILDER_PL_PATH . 'templates/archive-fullwidth.php';
            } elseif ( 'elementor_canvas' === $archive_elementor_template ) {
                $template = HTBUILDER_PL_PATH . 'templates/archive-canvas.php';
            } else {
                $template = HTBUILDER_PL_PATH . 'templates/archive-fullwidth.php';
            }
        }
        
        return $template;
        
    }

    /*
    * Custom Template ID
    */
    public function custom_template_id( $option_key ){

        $custom_tm_id = htbuilder_get_option( $option_key, 'htbuilder_templatebuilder_tabs', '0' );

        // Meta value
        $bltermlayoutid = 0;
        if( is_category() || is_tag() ){
            $termobj = get_queried_object();
            $bltermlayoutid = get_term_meta( $termobj->term_id, 'htbuilder_selectterm_layout', true ) ? get_term_meta( $termobj->term_id, 'htbuilder_selectterm_layout', true ) : '0';
        }
        if( $bltermlayoutid != '0' ){
            $custom_tm_id = $bltermlayoutid;
        }
        return $custom_tm_id;
    }

    /* 
    * Render Elementor single blog content
    */
    public function single_blog_content_elementor( $post ) {
        $templateid = $this->custom_template_id( 'single_blog_page' );
        if( !empty( $templateid ) ){
            echo Elementor::instance()->frontend->get_builder_content_for_display( $templateid );
        }else{
            the_content();
        }
    }

    /* 
    * Render Elementor blog content
    */
    public function blog_content_elementor( $post ) {
        $templateid = $this->custom_template_id( 'archive_blog_page' );
        if( !empty( $templateid ) ){
            echo Elementor::instance()->frontend->get_builder_content_for_display( $templateid );
        }else{
            the_content();
        }
    }


}

HTBuilder_Custom_Template_Layout::instance();