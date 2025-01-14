<?php
namespace HT_Builder\Elementor\Widget;

// Elementor Classes
use Elementor\Plugin as Elementor;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bl_Post_Comments_ELement extends Widget_Base {

    public function get_name() {
        return 'bl-post-commnets';
    }

    public function get_title() {
        return __( 'BL: Post Comments', 'ht-builder' );
    }

    public function get_icon() {
        return 'htbuilder-icon eicon-comments';
    }

    public function get_categories() {
        return ['ht_builder'];
    }

    protected function register_controls() {

        // Input Box Style
        $this->start_controls_section(
            'post_commnet_inputbox_style_section',
            array(
                'label' => __( 'Input Box', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_control(
                'post_commnet_inputbox_color',
                [
                    'label'     => __( 'Color', 'ht-builder' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} form.comment-form input[type="text"]' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'post_commnet_inputbox_typography',
                    'label'     => __( 'Typography', 'ht-builder' ),
                    'selector'  => '{{WRAPPER}} form.comment-form input[type="text"]',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'post_commnet_inputbox_border',
                    'label' => __( 'Border', 'ht-builder' ),
                    'selector' => '{{WRAPPER}} form.comment-form input[type="text"]',
                ]
            );

        $this->end_controls_section();

        // Submit Button
        $this->start_controls_section(
            'post_commnet_submitbtn_style_section',
            array(
                'label' => __( 'Submit Button', 'ht-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->start_controls_tabs('submitbtn_style_tabs');

                // Submit Button Normal
                $this->start_controls_tab(
                    'submitbtn_style_normal_tab',
                    [
                        'label' => __( 'Normal', 'ht-builder' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_commnet_submitbtn_color',
                        [
                            'label'     => __( 'Color', 'ht-builder' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} form.comment-form input[type="submit"]' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'post_commnet_submitbtn_bg_color',
                        [
                            'label'     => __( 'Background Color', 'ht-builder' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} form.comment-form input[type="submit"]' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'post_commnet_submitbtn_typography',
                            'label'     => __( 'Typography', 'ht-builder' ),
                            'selector'  => '{{WRAPPER}} form.comment-form input[type="submit"]',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'post_commnet_submitbtn_border',
                            'label' => __( 'Border', 'ht-builder' ),
                            'selector' => '{{WRAPPER}} form.comment-form input[type="submit"]',
                        ]
                    );

                $this->end_controls_tab();

                // Submit Button Hover
                $this->start_controls_tab(
                    'submitbtn_style_hover_tab',
                    [
                        'label' => __( 'Hover', 'ht-builder' ),
                    ]
                );
                    
                    $this->add_control(
                        'post_commnet_submitbtn_hover_color',
                        [
                            'label'     => __( 'Color', 'ht-builder' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} form.comment-form input[type="submit"]:hover' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_control(
                        'post_commnet_submitbtn_hover_bg_color',
                        [
                            'label'     => __( 'Background Color', 'ht-builder' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} form.comment-form input[type="submit"]:hover' => 'background-color: {{VALUE}};',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'post_commnet_submitbtn_hover_border',
                            'label' => __( 'Border', 'ht-builder' ),
                            'selector' => '{{WRAPPER}} form.comment-form input[type="submit"]:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render( $instance = [] ) {

        if( Elementor::instance()->editor->is_edit_mode() ){
            echo '<h3>' . esc_html__('Post Comments', 'ht-builder' ). '</h3>';
        }else{
            if( !comments_open() ){
                ?>
                    <span class="htcomment-close">
                        <?php esc_html_e( 'Comments Are Closed', 'ht-builder' ); ?>
                    </span>
                <?php
            }else{
                comments_template();
            }
        }
    }

    

}
