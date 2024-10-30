<?php

/*
* Helper Functions
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Plugisn Options value
 * return on/off
 */
if( !function_exists('htbuilder_get_option') ){
    function htbuilder_get_option( $option, $section, $default = '' ){
    	if(isset($_GET['header'])){
    		switch ($_GET['header']) {
    			case '1':
    				return 1884;
    				break;

    			case '2':
    				return 1986;
    				break;
    		}
    	}

        $options = get_option( $section );
        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }
        return $default;
    }
}

/*
 * Elementor Setting page value
 * return $elget_value
 */
if( !function_exists('htbuilder_get_elementor_setting') ){
    function htbuilder_get_elementor_setting( $key, $post_id ){
        // Get the page settings manager
        $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

        // Get the settings model for current post
        $page_settings_model = $page_settings_manager->get_model( $post_id );

        // Retrieve value
        $elget_value = $page_settings_model->get_settings( $key );
        return $elget_value;
    }
}


/*
 * Elementor Templates List
 * return array
 */
if( !function_exists('htbuilder_elementor_template') ){
    function htbuilder_elementor_template() {
        $templates = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
        $types = array();
        if ( empty( $templates ) ) {
            $template_lists = [ '0' => __( 'Do not Saved Templates.', 'ht-builder' ) ];
        } else {
            $template_lists = [ '0' => __( 'Select Template', 'ht-builder' ) ];
            foreach ( $templates as $template ) {
                $template_lists[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            }
        }
        return $template_lists;
    }
}


/**
 * Elementor version
 */
function htbuilder_elementor_version( $operator = '<', $version = '2.6.0' ) {
    if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, $version, $operator ) ) { return true; } else{ return false; }
}

/**
 * Generate Elementor icon
 */
function htbuilder_render_icon( $settings = [], $new_icon = 'selected_icon', $old_icon = 'icon', $attributes = [] ){

    $migrated = isset( $settings['__fa4_migrated'][$new_icon] );
    $is_new = empty( $settings[$old_icon] ) && \Elementor\Icons_Manager::is_migration_allowed();

    $attributes['aria-hidden'] = 'true';
    $output = '';

    if ( htbuilder_elementor_version( '>=', '2.6.0' ) && ( $is_new || $migrated ) ) {

        if ( empty( $settings[$new_icon]['library'] ) ) {
            return false;
        }

        $tag = 'i';
        // handler SVG Icon
        if ( 'svg' === $settings[$new_icon]['library'] ) {
            if ( ! isset( $settings[$new_icon]['value']['id'] ) ) {
                return '';
            }
            $output = Elementor\Core\Files\Assets\Svg\Svg_Handler::get_inline_svg( $settings[$new_icon]['value']['id'] );

        } else {
            $icon_types = \Elementor\Icons_Manager::get_icon_manager_tabs();
            if ( isset( $icon_types[ $settings[$new_icon]['library'] ]['render_callback'] ) && is_callable( $icon_types[ $settings[$new_icon]['library'] ]['render_callback'] ) ) {
                return call_user_func_array( $icon_types[ $settings[$new_icon]['library'] ]['render_callback'], [ $settings[$new_icon], $attributes, $tag ] );
            }

            if ( empty( $attributes['class'] ) ) {
                $attributes['class'] = $settings[$new_icon]['value'];
            } else {
                if ( is_array( $attributes['class'] ) ) {
                    $attributes['class'][] = $settings[$new_icon]['value'];
                } else {
                    $attributes['class'] .= ' ' . $settings[$new_icon]['value'];
                }
            }
            $output = '<' . $tag . ' ' . \Elementor\Utils::render_html_attributes( $attributes ) . '></' . $tag . '>';
        }

    } else {
        if ( empty( $attributes['class'] ) ) {
            $attributes['class'] = $settings[ $old_icon ];
        } else {
            if ( is_array( $attributes['class'] ) ) {
                $attributes['class'][] = $settings[ $old_icon ];
            } else {
                $attributes['class'] .= ' ' . $settings[ $old_icon ];
            }
        }
        $output = sprintf( '<i %s></i>', \Elementor\Utils::render_html_attributes( $attributes ) );
    }

    return $output;
 
}


/**
* Blog page return true
*/
if( !function_exists('htbuilder_is_blog_page') ){
    function htbuilder_is_blog_page() {
        global $post;
        //Post type must be 'post'.
        $post_type = get_post_type( $post );
        return (
            ( is_home() || is_archive() )
            && ( $post_type == 'post')
        ) ? true : false ;
    }
}

/*
 * HTML Tag list
 * return array
 */
if( !function_exists('htbuilder_html_tag_lists') ){
    function htbuilder_html_tag_lists() {
        $html_tag_list = [
            'h1'   => __( 'H1', 'ht-builder' ),
            'h2'   => __( 'H2', 'ht-builder' ),
            'h3'   => __( 'H3', 'ht-builder' ),
            'h4'   => __( 'H4', 'ht-builder' ),
            'h5'   => __( 'H5', 'ht-builder' ),
            'h6'   => __( 'H6', 'ht-builder' ),
            'p'    => __( 'p', 'ht-builder' ),
            'div'  => __( 'div', 'ht-builder' ),
            'span' => __( 'span', 'ht-builder' ),
        ];
        return $html_tag_list;
    }
}

/*
 * HTML Tag Validation
 * return strig
 */
if( !function_exists('htbuilder_validate_html_tag') ){
    function htbuilder_validate_html_tag( $tag ) {
        $allowed_html_tags = [
            'article',
            'aside',
            'footer',
            'header',
            'section',
            'nav',
            'main',
            'div',
            'h1',
            'h2',
            'h3',
            'h4',
            'h5',
            'h6',
            'p',
            'span',
        ];
        $valid_tag = is_string( $tag ) ? strtolower( $tag ) : 'div';
        return in_array( $valid_tag, $allowed_html_tags ) ? $tag : 'div';
    }
}

/*
 * Custom Pagination
 */
function htbuilder_custom_pagination( $totalpage ){
    $big = 999999999;
    echo '<div class="htbuilder-pagination">';
        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $totalpage,
            'prev_text' => '&larr;', 
            'next_text' => '&rarr;', 
            'type'      => 'list', 
            'end_size'  => 3, 
            'mid_size'  => 3
        ) );
    echo '</div>';
}


/*
 * Plugisn API Data Fetch
 */
function htbuilder_get_org_plugins( $author = 'htplugins' ) {
    
    $plcachekey = 'hastech_plugins';
    $plugins_data = get_transient( $plcachekey );

    if ( !$plugins_data ) {

        $args    = (object) array(
            'author'   => $author,
            'per_page' => '50',
            'page'     => '1',
            'fields'   => array( 'slug', 'name', 'version', 'downloaded', 'active_installs' )
        );
        $request = array( 'action' => 'query_plugins', 'timeout' => 15, 'request' => serialize( $args ) );

        //https://codex.wordpress.org/WordPress.org_API
        $url = 'http://api.wordpress.org/plugins/info/1.0/';
        $response = wp_remote_post( $url, array( 'body' => $request ) );
        if ( ! is_wp_error( $response ) ) {
            $plugins_data = array();
            $plugins  = unserialize( $response['body'] );
            if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
                foreach ( $plugins->plugins as $pl_info ) {
                    $plugins_data[] = array(
                        'slug'            => $pl_info->slug,
                        'name'            => $pl_info->name,
                        'version'         => $pl_info->version,
                        'downloaded'      => $pl_info->downloaded,
                        'active_installs' => $pl_info->active_installs
                    );
                }
            }
            set_transient( $plcachekey, $plugins_data, 24 * HOUR_IN_SECONDS );
        }
    }

    if ( is_array( $plugins_data ) && ( count( $plugins_data ) > 0 ) ) {
        array_multisort( array_column( $plugins_data, 'active_installs' ), SORT_DESC, $plugins_data );

        foreach ( $plugins_data as $pl_data ) {
            $extension = '.png';
            $jpegs = array('quickswish', 'ht-social-share', 'docus', 'whols', 'wptemplata', 'wishsuite', 'ht-menu-lite', '99fy-core', 'ever-compare', 'wp-plugin-manager', 'extensions-for-cf7');

            if( in_array($pl_data['slug'], $jpegs) ){
                $extension = '.jpg';
            }

            ?>
                <div class="htoptions-single-plugins htfree-plugins">
                     <div class="htoptions-img">
                         <img src="https://ps.w.org/<?php echo $pl_data['slug']; ?>/assets/icon-128x128<?php echo $extension; ?>" alt="">
                     </div>
                     <div class="htoptions-plugins-content">
                         <a href="https://wordpress.org/plugins/<?php echo $pl_data['slug']; ?>/"><h3><?php echo esc_html__( $pl_data['name'], 'ht-builder' ); ?></h3></a>
                         <a class="htoptions-button" href="<?php echo esc_url( admin_url() ); ?>plugin-install.php?s=<?php echo $pl_data['slug']; ?>&tab=search&type=term" target="_blank"><?php echo esc_html__( 'Install Now', 'ht-builder' ); ?></a>
                     </div>
                 </div>
            <?php
        }
    }

}