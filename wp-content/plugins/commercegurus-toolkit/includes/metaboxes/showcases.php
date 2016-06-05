<?php

function cg_showcases_metaboxes( $meta_boxes ) {
    $prefix = '_cg_'; // Prefix for all fields
    $meta_boxes['showcase'] = array(
        'id' => 'showcase_metabox',
        'title' => __( 'Showcase Details', 'commercegurus' ),
        'pages' => array( 'showcases' ), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => __( 'Showcase url/link', 'commercegurus' ),
                'desc' => __( 'Where the showcase should link to. Please do not forget to include the http://', 'commercegurus' ),
                'id' => $prefix . 'showcase_url',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Showcase url/link description', 'commercegurus' ),
                'desc' => __( 'This is the text description displayed when clicking on the showcase url/link.', 'commercegurus' ),
                'id' => $prefix . 'showcase_url_desc',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Showcase image gallery', 'commercegurus' ),
                'desc' => __( 'Upload images and they will be shown in a slideshow..', 'commercegurus' ),
                'id' => $prefix . 'showcase_gallery',
                'type' => 'cg_gallery',
                'sanitization_cb' => 'cg_gallery_field_sanitise',
            ),
            array(
                'name' => __( 'Showcase Video Source', 'commercegurus' ),
                'desc' => __( 'As an alternative to a Showcase image gallery, you can embed a showcase video.', 'commercegurus' ),
                'id' => $prefix . 'showcase_video_source',
                'type' => 'select',
                'options' => array(
                    'youtube' => __( 'Youtube', 'commercegurus' ),
                    'vimeo' => __( 'Vimeo', 'commercegurus' ),
                ),
            ),
            array(
                'name' => __( 'Showcase Video ID', 'commercegurus' ),
                'desc' => __( 'Copy the ID of the video (E.g. http://www.youtube.com/watch?v=<strong>Sv6dMFF_yts</strong>) you want to show..', 'commercegurus' ),
                'id' => $prefix . 'showcase_video_embed',
                'type' => 'textarea'
            ),
        ),
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'cg_showcases_metaboxes' );
