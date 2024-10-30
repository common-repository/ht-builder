<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

require( __DIR__ . '/classes/class.settings-api.php' );

class HTBuilder_Admin_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new HTBuilder_Settings_API();
        add_action( 'admin_init', [ $this, 'admin_init' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ], 220 );
        add_action('admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
        add_action( 'wsa_form_bottom_htbuilder_general_tabs', [ $this, 'html_general_tabs' ] );
        add_action( 'wsa_form_top_htbuilder_element_tabs', [ $this, 'html_popup_box' ] );
    }

    // Admin Initialize
    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->admin_get_settings_sections() );
        $this->settings_api->set_fields( $this->admin_fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    // Plugins menu Register
    function admin_menu() {
        add_menu_page( 
            __( 'HT Builder', 'ht-builder' ),
            __( 'HT Builder', 'ht-builder' ),
            'manage_options',
            'htbuilder',
            array ( $this, 'plugin_page' ),
            'dashicons-text-page',
            100
        );
    }

    // Admin Scripts
    public function enqueue_admin_scripts(){

        // wp core styles
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        // wp core scripts
        wp_enqueue_script( 'jquery-ui-dialog' );

        wp_enqueue_style( 'htbuilder-admin', HTBUILDER_PL_URL . 'includes/admin/assets/css/admin_optionspanel.css', FALSE, HTBUILDER_VERSION );
        
        wp_enqueue_script( 'htbuilder-admin', HTBUILDER_PL_URL . 'includes/admin/assets/js/admin_scripts.js', array('jquery'), HTBUILDER_VERSION, TRUE );
    }

    // Options page Section register
    function admin_get_settings_sections() {
        $sections = array(
            
            array(
                'id'    => 'htbuilder_general_tabs',
                'title' => esc_html__( 'General', 'ht-builder' )
            ),

            array(
                'id'    => 'htbuilder_templatebuilder_tabs',
                'title' => esc_html__( 'Template Builder', 'ht-builder' )
            ),

            array(
                'id'    => 'htbuilder_element_tabs',
                'title' => esc_html__( 'Elements', 'ht-builder' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function admin_fields_settings() {

        $settings_fields = array(

            'htbuilder_general_tabs' => array(),
            
            'htbuilder_templatebuilder_tabs' => array(

                array(
                    'name'  => 'enablecustomtemplate',
                    'label'  => __( 'Enable Custom template Layout', 'ht-builder' ),
                    'desc'  => __( 'Enable', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'    => 'single_blog_page',
                    'label'   => __( 'Single Blog Template.', 'ht-builder' ),
                    'desc'    => __( 'You can select Single blog page from here.', 'ht-builder' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => htbuilder_elementor_template()
                ),

                array(
                    'name'    => 'archive_blog_page',
                    'label'   => __( 'Blog Template.', 'ht-builder' ),
                    'desc'    => __( 'You can select blog page from here.', 'ht-builder' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => htbuilder_elementor_template()
                ),

                array(
                    'name'    => 'header_page',
                    'label'   => __( 'Header Template.', 'ht-builder' ),
                    'desc'    => __( 'You can select header template from here.', 'ht-builder' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => htbuilder_elementor_template()
                ),

                array(
                    'name'    => 'footer_page',
                    'label'   => __( 'Footer Template.', 'ht-builder' ),
                    'desc'    => __( 'You can select footer template from here.', 'ht-builder' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => htbuilder_elementor_template()
                ),

                array(
                    'name'    => 'search_pagep',
                    'label'   => __( 'Search Page Template.', 'ht-builder' ),
                    'desc'    => __( 'You can select search page from here. <span>( Pro )</span>', 'ht-builder' ),
                    'type'    => 'select',
                    'default' => 'select',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'=>'htproelement',
                ),

                array(
                    'name'    => 'error_pagep',
                    'label'   => __( '404 Page Template.', 'ht-builder-pro' ),
                    'desc'    => __( 'You can select 404 page from here. <span>( Pro )</span>', 'ht-builder-pro' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'   =>'htproelement',
                ),

                array(
                    'name'    => 'coming_soon_pagep',
                    'label'   => __( 'Coming Soon Page Template.', 'ht-builder-pro' ),
                    'desc'    => __( 'You can select coming soon page from here. <span>( Pro )</span>', 'ht-builder-pro' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'   =>'htproelement',
                ),

            ),

            'htbuilder_element_tabs'=>array(

                array(
                    'name'  => 'bl_post_title',
                    'label'  => __( 'Post Title', 'ht-builder' ),
                    'desc'  => __( 'Post Title', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_post_featured_image',
                    'label'  => __( 'Post Featured Image', 'ht-builder' ),
                    'desc'  => __( 'Post Featured Image', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_post_meta_info',
                    'label'  => __( 'Post Meta Info', 'ht-builder' ),
                    'desc'  => __( 'Post Meta Info', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_post_excerpt',
                    'label'  => __( 'Post Excerpt', 'ht-builder' ),
                    'desc'  => __( 'Post Excerpt', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_post_content',
                    'label'  => __( 'Post Content', 'ht-builder' ),
                    'desc'  => __( 'Post Content', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_post_comments',
                    'label'  => __( 'Post Comments', 'ht-builder' ),
                    'desc'  => __( 'Post Comments', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_post_search_form',
                    'label'  => __( 'Post Search Form', 'ht-builder' ),
                    'desc'  => __( 'Post Search Form', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_post_archive',
                    'label'  => __( 'Archive Posts', 'ht-builder' ),
                    'desc'  => __( 'Archive Posts', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_post_archive_title',
                    'label'  => __( 'Archive Title', 'ht-builder' ),
                    'desc'  => __( 'Archive Title', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),
                
                array(
                    'name'  => 'bl_page_title',
                    'label'  => __( 'Page Title', 'ht-builder' ),
                    'desc'  => __( 'Page Title', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_site_title',
                    'label'  => __( 'Site Title', 'ht-builder' ),
                    'desc'  => __( 'Site Title', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_site_logo',
                    'label'  => __( 'Site Logo', 'ht-builder' ),
                    'desc'  => __( 'Site Logo', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_nav_menu',
                    'label'  => __( 'Nav Menu', 'ht-builder' ),
                    'desc'  => __( 'Nav Menu', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_post_author_info',
                    'label'  => __( 'Author Info', 'ht-builder' ),
                    'desc'  => __( 'Author Info', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'htbuilder_table_row',
                ),

                array(
                    'name'  => 'bl_social_sharep',
                    'label'  => __( 'Social Share <span>( Pro )</span>', 'ht-builder' ),
                    'desc'  => __( 'Social share', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htbuilder_table_row pro',
                ),

                array(
                    'name'  => 'bl_print_pagep',
                    'label'  => __( 'Print Page <span>( Pro )</span>', 'ht-builder' ),
                    'desc'  => __( 'Print Page', 'ht-builder' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htbuilder_table_row pro',
                ),

                array(
                    'name'  => 'bl_view_counterp',
                    'label'  => __( 'View Counter <span>( Pro )</span>', 'ht-builder' ),
                    'desc'  => __( 'View Counter', 'ht-builder-pro' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htbuilder_table_row pro',
                ),

                array(
                    'name'  => 'bl_post_navigationp',
                    'label'  => __( 'Post Navigation <span>( Pro )</span>', 'ht-builder-pro' ),
                    'desc'  => __( 'Post Navigation', 'ht-builder-pro' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htbuilder_table_row pro',
                ),

                array(
                    'name'  => 'bl_related_postp',
                    'label'  => __( 'Related Post <span>( Pro )</span>', 'ht-builder-pro' ),
                    'desc'  => __( 'Related Post', 'ht-builder-pro' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htbuilder_table_row pro',
                ),

                array(
                    'name'  => 'bl_popular_postp',
                    'label'  => __( 'Popular Post <span>( Pro )</span>', 'ht-builder-pro' ),
                    'desc'  => __( 'Popular Post', 'ht-builder-pro' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'htbuilder_table_row pro',
                ),


            ),

        );
        
        return array_merge( $settings_fields );
    }

    // Admin Menu Page Render
    function plugin_page() {

        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'HT Builder Settings','ht-builder' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';

    }

    // Save Options Message
    function save_message() {
        if( isset($_GET['settings-updated']) ) { ?>
            <div class="updated notice is-dismissible"> 
                <p><strong><?php esc_html_e('Successfully Settings Saved.', 'ht-builder') ?></strong></p>
            </div>
            <?php
        }
    }

    // Pop up Box
    function html_popup_box(){
        ob_start();
        ?>
            <div id="htbuilder-dialog" title="<?php esc_html_e( 'Go Premium', 'ht-builder' ); ?>" style="display: none;">
                <div class="htdialog-content">
                    <span><i class="dashicons dashicons-warning"></i></span>
                    <p>
                        <?php
                            echo __('Purchase our','ht-builder').' <strong><a href="'.esc_url( 'https://hasthemes.com/plugins/ht-builder-wordpress-theme-builder-for-elementor/' ).'" target="_blank" rel="nofollow">'.__( 'premium version', 'ht-builder' ).'</a></strong> '.__('to unlock these pro elements!','ht-builder');
                        ?>
                    </p>
                </div>
            </div>
            <script type="text/javascript">
                ( function( $ ) {
                    
                    $(function() {
                        $( '.htbuilder_table_row.pro,.htproelement label' ).click(function() {
                            $( "#htbuilder-dialog" ).dialog({
                                modal: true,
                                minWidth: 500,
                                buttons: {
                                    Ok: function() {
                                      $( this ).dialog( "close" );
                                    }
                                }
                            });
                        });
                        $(".htbuilder_table_row.pro input[type='checkbox'],.htproelement select").attr("disabled", true);
                    });

                } )( jQuery );
            </script>
        <?php
        echo ob_get_clean();
    }

    // General tab
    function html_general_tabs(){
        ob_start();
        ?>
            <div class="htbuilder-general-tabs">

                <div class="htbuilder-document-section">
                    <div class="htbuilder-column">
                        <a href="https://hasthemes.com/plugins/ht-builder-wordpress-theme-builder-for-elementor/" target="_blank">
                            <img src="<?php echo HTBUILDER_PL_URL; ?>/includes/admin/assets/images/video-tutorial.jpg" alt="<?php esc_attr_e( 'Video Tutorial', 'ht-builder' ); ?>">
                        </a>
                    </div>
                    <div class="htbuilder-column">
                        <a href="https://demo.hasthemes.com/doc/htbuilder/index.html" target="_blank">
                            <img src="<?php echo HTBUILDER_PL_URL; ?>/includes/admin/assets/images/online-documentation.jpg" alt="<?php esc_attr_e( 'Online Documentation', 'ht-builder' ); ?>">
                        </a>
                    </div>
                    <div class="htbuilder-column">
                        <a href="https://hasthemes.com/contact-us/" target="_blank">
                            <img src="<?php echo HTBUILDER_PL_URL; ?>/includes/admin/assets/images/genral-contact-us.jpg" alt="<?php esc_attr_e( 'Contact Us', 'ht-builder' ); ?>">
                        </a>
                    </div>
                </div>

                <div class="different-pro-free">
                    <h3 class="htbuilder-section-title">HT Builder Free VS HT Builder Pro.</h3>
                    <div class="htbuilder-admin-row">
                        <div class="features-list-area">
                            <h3>HT Builder Free</h3>
                            <ul>
                                <li>14 Elements</li>
                                <li>Blog Page Builder</li>
                                <li>Single Blog Page Builder</li>
                                <li>Header Builder</li>
                                <li>Footer Builder</li>
                                <li>1 Readymade header to import</li>
                                <li>1 Readymade footer to import</li>
                                <li class="htdel"><del>Blog Search Page Builder</del></li>
                                <li class="htdel"><del>404 Error Page Builder</del></li>
                                <li class="htdel"><del>Coming soon Page Builder</del></li>
                                <li class="htdel"><del>Blog Archive Category Wise Individual layout</del></li>
                                <li class="htdel"><del>Blog Archive Tag Wise Individual layout</del></li>
                            </ul>
                            <a target="_blank" href="<?php echo esc_url( admin_url() ); ?>/plugin-install.php" class="button button-primary">Install Now</a>
                        </div>
                        <div class="features-list-area">
                            <h3>HT Builder Pro</h3>
                            <ul>
                                <li>20 Elements</li>
                                <li>Blog Page Builder</li>
                                <li>Single Blog Page Builder</li>
                                <li>Header Builder</li>
                                <li>Footer Builder</li>
                                <li>10 Readymade headers to import</li>
                                <li>10 Readymade footers to import</li>
                                <li>Blog Search Page Builder</li>
                                <li>404 Error Page Builder</li>
                                <li>Coming soon Page Builder</li>
                                <li>Blog Archive Category Wise Individual layout</li>
                                <li>Blog Archive Tag Wise Individual layout</li>
                            </ul>
                            <a target="_blank" href="https://hasthemes.com/plugins/ht-builder-wordpress-theme-builder-for-elementor/" class="button button-primary">Buy Now</a>
                        </div>
                    </div>

                </div>

            </div>
        <?php
        echo ob_get_clean();
    }

    // Plugins Library
    function html_our_plugins_library_tabs() {
        ob_start();
        ?>
        <div class="htoptions-plugins-laibrary">
            <p><?php echo esc_html__( 'Use Our plugins.', 'ht-builder' ); ?></p>
            <div class="htoptions-plugins-area">
                <h3><?php esc_html_e( 'Premium Plugins', 'ht-builder' ); ?></h3>
                <div class="htoptions-plugins-row">
                    
                    <div class="htoptions-single-plugins"><img src="<?php echo HTBUILDER_PL_URL; ?>/includes/admin/assets/images/woolentor.png" alt="">
                        <div class="htoptions-plugins-content">
                            <a href="https://hasthemes.com/plugins/woolentor-pro-woocommerce-page-builder/" target="_blank">
                                <h3><?php echo esc_html__( 'WooLentor - WooCommerce Page Builder and WooCommerce Elementor Addon', 'ht-builder' ); ?></h3>
                            </a>
                            <a href="https://hasthemes.com/plugins/woolentor-pro-woocommerce-page-builder/" class="htoptions-button" target="_blank"><?php echo esc_html__( 'More Details', 'ht-builder' ); ?></a>
                        </div>
                    </div>
                    
                    <div class="htoptions-single-plugins"><img src="<?php echo HTBUILDER_PL_URL; ?>/includes/admin/assets/images/ht-mega.png" alt="">
                        <div class="htoptions-plugins-content">
                            <a href="https://hasthemes.com/plugins/ht-mega-pro/" target="_blank">
                                <h3><?php echo esc_html__( 'HT Mega – Absolute Addons for Elementor Page Builder', 'ht-builder' ); ?></h3>
                            </a>
                            <a href="https://hasthemes.com/plugins/ht-mega-pro/" class="htoptions-button" target="_blank"><?php echo esc_html__( 'More Details', 'ht-builder' ); ?></a>
                        </div>
                    </div>
                    
                    <div class="htoptions-single-plugins"><img src="<?php echo HTBUILDER_PL_URL; ?>/includes/admin/assets/images/preview-hashbar-pro.jpg" alt="">
                        <div class="htoptions-plugins-content">
                            <a href="https://hasthemes.com/wordpress-notification-bar-plugin/" target="_blank">
                                <h3><?php echo esc_html__( 'HashBar Pro - WordPress Notification Bar plugin', 'ht-builder' ); ?></h3>
                            </a>
                            <a href="https://hasthemes.com/wordpress-notification-bar-plugin/" class="htoptions-button" target="_blank"><?php echo esc_html__( 'More Details', 'ht-builder' ); ?></a>
                        </div>
                    </div>
                    
                    <div class="htoptions-single-plugins"><img src="<?php echo HTBUILDER_PL_URL; ?>/includes/admin/assets/images/ht-script.png" alt="">
                        <div class="htoptions-plugins-content">
                            <a href="https://hasthemes.com/plugins/insert-headers-and-footers-code-ht-script/" target="_blank">
                                <h3><?php echo esc_html__( 'HT Script Pro - Insert Header & Footer Code', 'ht-builder' ); ?></h3>
                            </a>
                            <a href="https://hasthemes.com/plugins/insert-headers-and-footers-code-ht-script/" class="htoptions-button" target="_blank"><?php echo esc_html__( 'More Details', 'ht-builder' ); ?></a>
                        </div>
                    </div>

                    <div class="htoptions-single-plugins"><img src="<?php echo HTBUILDER_PL_URL; ?>/includes/admin/assets/images/wc-builder.jpg" alt="">
                        <div class="htoptions-plugins-content">
                            <a href="https://hasthemes.com/plugins/wc-builder-woocoomerce-page-builder-for-wpbakery/" target="_blank">
                                <h3><?php echo esc_html__( 'WC Builder - WooCommerce Page Builder for WP Bakery', 'wc-sales-notification-pro' ); ?></h3>
                            </a>
                            <a href="https://hasthemes.com/plugins/wc-builder-woocoomerce-page-builder-for-wpbakery/" class="htoptions-button" target="_blank"><?php echo esc_html__( 'More Details', 'wc-sales-notification-pro' ); ?></a>
                        </div>
                    </div>

                </div>

                <h3><?php esc_html_e( 'Free Plugins', 'ht-builder' ); ?></h3>
                <div class="htoptions-plugins-row">

                	<?php htbuilder_get_org_plugins(); ?>

                </div>

            </div>
        </div>
        <?php
        echo ob_get_clean();
    }
    

}

new HTBuilder_Admin_Settings();