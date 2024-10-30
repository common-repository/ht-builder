<div class="httemplates-templates-area">
    <div class="httemplate-row">

        <!-- PopUp Content Start -->
        <div id="httemplate-popup-area" style="display: none;">
            <div class="httemplate-popupcontent">
                <div class='htspinner'></div>
                <div class="htmessage" style="display: none;">
                    <p></p>
                    <span class="httemplate-edit"></span>
                </div>
                <div class="htpopupcontent">
                    <p><?php esc_html_e( 'Import template to your Library', 'ht-builder' );?></p>
                    <span class="htimport-button-dynamic"></span>
                    <div class="htpageimportarea">
                        <p> <?php esc_html_e( 'Create a new page from this template', 'ht-builder' ); ?></p>
                        <input id="htpagetitle" type="text" name="htpagetitle" placeholder="<?php echo esc_attr_x( 'Enter a Page Name', 'placeholder', 'ht-builder' ); ?>">
                        <span class="htimport-button-dynamic-page"></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- PopUp Content End -->

        <!-- Top banner area Start -->
        <div class="httemplate-top-banner-area">
            <div class="htbanner-content">
                <div class="htbanner-desc">
                    <h3><?php esc_html_e( 'HT Builder Templates Library', 'ht-builder' ); ?></h3>
                <?php if( is_plugin_active('ht-builder-pro/ht-builder.php') ): ?>
                    <p><?php esc_html_e( '10 Headers and 10 footers & 3 blog layout', 'ht-builder' ); ?></p>
                <?php else:?>
                    <p><?php esc_html_e( '2 Templates are Free and 18 Templates are Premium.', 'ht-builder' ); ?></p>
                <?php endif; ?>
                </div>
                <?php if( !is_plugin_active('ht-builder-pro/ht-builder.php') ){ ?>
                    <a href="https://hasthemes.com/plugins/ht-builder-wordpress-theme-builder-for-elementor/" target="_blank"><?php esc_html_e( 'Buy HT Builder Pro Version', 'ht-builder' );?></a>
                <?php } ?>
            </div>
        </div>
        <!-- Top banner area end -->

        <?php if( HTBuilder_Template_Library::instance()->get_templates_info()['templates'] ): ?>
            
            <div class="htbuilder-topbar">
                <span id="htbuilderclose">&larr; <?php esc_html_e( 'Back to Library', 'ht-builder' ); ?></span>
                <h3 id="htbuilder-tmp-name"></h3>
            </div>

            <ul id="tp-grid" class="tp-grid">

                <?php foreach ( HTBuilder_Template_Library::instance()->get_templates_info()['templates'] as $httemplate ): 
                    
                    $allcat = explode( ' ', $httemplate['category'] );

                    $htimp_btn_atr = [
                        'templpateid' => $httemplate['id'],
                        'templpattitle' => $httemplate['title'],
                        'message' => esc_html__( 'Successfully '.$httemplate['title'].' has been imported.', 'ht-builder' ),
                        'htbtnlibrary' => esc_html__( 'Import to Library', 'ht-builder' ),
                        'htbtnpage' => esc_html__( 'Import to Page', 'ht-builder' ),
                    ];

                ?>

                    <li data-pile="<?php echo esc_attr( implode(' ', $allcat ) ); ?>">
                        <div class="htsingle-templates-laibrary">
                            <div class="httemplate-thumbnails">
                                <img src="<?php echo esc_url( $httemplate['thumbnail'] ); ?>" alt="<?php echo esc_attr( $httemplate['title'] ); ?>">
                                <div class="httemplate-action">
                                    <?php if( $httemplate['is_pro'] == 1 ):?>
                                        <a href="https://hasthemes.com/plugins/ht-builder-wordpress-theme-builder-for-elementor/" target="_blank">
                                            <?php esc_html_e( 'Buy Now', 'ht-builder' ); ?>
                                        </a>
                                    <?php else:?>
                                        <a href="#" class="httemplateimp" data-templpateopt='<?php echo wp_json_encode( $htimp_btn_atr );?>' >
                                            <?php esc_html_e( 'Import', 'ht-builder' ); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="httemplate-content">
                                <h3><?php echo esc_html__( $httemplate['title'], 'ht-builder' ); if( $httemplate['is_pro'] == 1 ){ echo ' <span>( '.esc_html__('Pro','ht-builder').' )</span>'; } ?></h3>
                                <div class="httemplate-tags">
                                    <?php echo implode( ' / ', explode( ',', $httemplate['tags'] ) ); ?>
                                </div>
                            </div>
                        </div>
                    </li>

                <?php endforeach; ?>

            </ul>

            <script type="text/javascript">
                jQuery(document).ready(function($) {

                    $(function() {
                        var $grid = $( '#tp-grid' ),
                            $name = $( '#htbuilder-tmp-name' ),
                            $close = $( '#htbuilderclose' ),
                            $loaderimg = '<?php echo HTBUILDER_PL_URL . 'includes/admin/assets/images/ajax-loader.gif'; ?>',
                            $loader = $( '<div class="htbuilder-loader"><span><img src="'+$loaderimg+'" alt="" /></span></div>' ).insertBefore( $grid ),
                            stapel = $grid.stapel( {
                                onLoad : function() {
                                    $loader.remove();
                                },
                                onBeforeOpen : function( pileName ) {
                                    $( '.htbuilder-topbar,.httemplate-action' ).css('display','block');
                                    $( '.httemplate-content span' ).css('display','inline-block');
                                    $close.show();
                                    $name.html( pileName );
                                },
                                onAfterOpen : function( pileName ) {
                                    $close.show();
                                }
                            } );
                        $close.on( 'click', function() {
                            $close.hide();
                            $name.empty();
                            $( '.htbuilder-topbar,.httemplate-action,.httemplate-content span' ).css('display','none');
                            stapel.closePile();
                        } );
                    } );

                });
            </script>
        <?php endif; ?>

    </div>
</div>