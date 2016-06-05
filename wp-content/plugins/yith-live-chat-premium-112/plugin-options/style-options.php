<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$defaults = YITH_Live_Chat()->defaults;

return array(
    'style' => array(

        /* =================== HOME =================== */
        'home'     => array(
            array(
                'name' => __( 'YITH Live Chat: Appearance Settings', 'yith-live-chat' ),
                'type' => 'title'
            ),
            array(
                'type' => 'close'
            )
        ),
        /* =================== END SKIN =================== */

        /* =================== MESSAGES =================== */
        'settings' => array(
            array(
                'name' => __( 'Main Color', 'yith-live-chat' ),
                'desc' => __( 'This color will be applied to Chat button, header and form buttons. Default', 'yith-live-chat' ) . ': #009EDB',
                'id'   => 'header-button-color',
                'type' => 'custom-colorpicker',
                'std'  => $defaults['header-button-color'],
            ),
            array(
                'name'              => __( 'Chat Button Width', 'yith-live-chat' ),
                'desc'              => sprintf( __( 'Default%s Min.%s Max.%s', 'yith-live-chat' ), ': 260px,', ': 200px,', ': 400px' ),
                'id'                => 'chat-button-width',
                'type'              => 'custom-number',
                'std'               => $defaults['chat-button-width'],
                'custom_attributes' => array(
                    'min'      => 200,
                    'max'      => 400,
                    'required' => 'required'
                )
            ),
            array(
                'name'              => __( 'Chat Conversation Width', 'yith-live-chat' ),
                'desc'              => sprintf( __( 'Default%s Min.%s Max.%s', 'yith-live-chat' ), ': 370px,', ': 200px,', ': 400px' ),
                'id'                => 'chat-conversation-width',
                'type'              => 'custom-number',
                'std'               => $defaults['chat-conversation-width'],
                'custom_attributes' => array(
                    'min'      => 200,
                    'max'      => 400,
                    'required' => 'required'
                )
            ),
            array(
                'name'              => __( 'Form Width', 'yith-live-chat' ),
                'desc'              => sprintf( __( 'Default%s Min.%s Max.%s', 'yith-live-chat' ), ': 260px,', ': 200px,', ': 400px' ),
                'id'                => 'form-width',
                'type'              => 'custom-number',
                'std'               => $defaults['form-width'],
                'custom_attributes' => array(
                    'min'      => 200,
                    'max'      => 400,
                    'required' => 'required'
                )
            ),
            array(
                'name'    => __( 'Chat Position', 'yith-live-chat' ),
                'desc'    => __( 'Default', 'yith-live-chat' ) . ': Right bottom corner',
                'id'      => 'chat-position',
                'type'    => 'select',
                'std'     => $defaults['chat-position'],
                'options' => array(
                    'left-top'     => __( 'Top left corner', 'yith-live-chat' ),
                    'right-top'    => __( 'Top right corner', 'yith-live-chat' ),
                    'left-bottom'  => __( 'Bottom left corner', 'yith-live-chat' ),
                    'right-bottom' => __( 'Bottom right corner', 'yith-live-chat' ),
                )
            ),
            array(
                'name'              => __( 'Border Radius', 'yith-live-chat' ),
                'desc'              => sprintf( __( 'Default%s Min.%s Max.%s', 'yith-live-chat' ), ': 5px,', ': 0px,', ': 50px' ),
                'id'                => 'border-radius',
                'type'              => 'custom-number',
                'std'               => $defaults['border-radius'],
                'custom_attributes' => array(
                    'min'      => 0,
                    'max'      => 50,
                    'required' => 'required'
                )
            ),
            array(
                'name'    => __( 'Chat Opening Animation', 'yith-live-chat' ),
                'desc'    => __( 'Default', 'yith-live-chat' ) . ': Bounce',
                'id'      => 'chat-animation',
                'type'    => 'select',
                'std'     => $defaults['chat-animation'],
                'options' => array(
                    'none'     => __( 'None', 'yith-live-chat' ),
                    'bounceIn' => __( 'Bounce', 'yith-live-chat' ),
                    'fadeIn'   => __( 'Fade', 'yith-live-chat' ),
                )
            ),
            array(
                'name'              => __( 'Custom CSS', 'yith-live-chat' ),
                'desc'              => '',
                'id'                => 'custom-css',
                'type'              => 'textarea',
                'std'               => $defaults['custom-css'],
                'custom_attributes' => array(
                    'class' => 'textareas-css'
                )
            )
        ),
    )
);