<?php

// [cg_dropcap]
if ( !function_exists( 'cg_dropcap' ) ) :

    function cg_dropcap( $atts, $content = null ) {
        extract( shortcode_atts( array(
            'style' => 'normal',
            'font_size' => '50px'
                        ), $atts ) );

        return "<span class=\"cg-dropcap cg-dropcg--$style\" style=\"font-size: $font_size; line-height: $font_size; width: $font_size; height: $font_size;\">" . do_shortcode( $content ) . "</span>";
    }

    add_shortcode( 'cg_dropcap', 'cg_dropcap' );
endif;

// [cg_divider]
if ( !function_exists( 'cg_divider' ) ) :

    function cg_divider( $atts, $content = null ) {
        extract( shortcode_atts( array(
            'style' => 'plain'
                        ), $atts ) );
        return '<hr class="cg-divider cg-divider--' . $style . '">';
    }

    add_shortcode( 'cg_divider', 'cg_divider' );
endif;

// [cg_social_icons]

if ( !function_exists( 'cg_social_icons' ) ) {

    function cg_social_icons( $atts, $content = null ) {
        $args = array(
            "icon" => "",
            "url" => "",
        );

        extract( shortcode_atts( $args, $atts ) );

        $output = "";
        $output .= "<span class='cg-social-icon-container'>";

        if ( $url != "" ) {
            $output .= "<a href='" . $url . "'>";
        }

        $output .= "<i class='fa " . $icon . "'></i>";

        if ( $url != "" ) {
            $output .= "</a>";
        }

        $output .= "</span>";
        return $output;
    }

}
add_shortcode( 'cg_social_icons', 'cg_social_icons' );


/* Person Profile shortcode */

if ( !function_exists( 'cg_person' ) ) {

    function cg_person( $atts, $content = null ) {
        $args = array(
            "person_img" => "",
            "person_name" => "",
            "person_title" => "",
            "person_desc" => "",
            "person_social_icon_1" => "",
            "person_social_icon_1_url" => "",
            "person_social_icon_2" => "",
            "person_social_icon_2_url" => "",
            "person_social_icon_3" => "",
            "person_social_icon_3_url" => "",
            "person_social_icon_4" => "",
            "person_social_icon_4_url" => "",
            "person_social_icon_5" => "",
            "person_social_icon_5_url" => "",
        );

        extract( shortcode_atts( $args, $atts ) );
        if ( is_numeric( $person_img ) ) {
            $person_img_src = wp_get_attachment_url( $person_img );
        } else {
            $person_img_src = $person_img;
        }

        $output = "<div class='cg-person'>";
        $output .= "<div class='cg-person-inner'>";
        if ( $person_img != "" ) {
            $output .= "<div class='cg-person-img'>";
            $output .= "<img src='$person_img_src' alt='' />";
            $output .= "</div>";
        }
        $output .= "<div class='cg-person-text'>";
        $output .= "<div class='cg-person-text-inner'>";
        $output .= "<div class='cg-team-title-container'>";
        $output .= "<h4 class='cg-person-name'>";
        $output .= $person_name;
        $output .= "</h4>";
        if ( $person_title != "" ) {
            $output .= "<span class='cg-person-title'>" . $person_title . "</span>";
        }
        $output .= "</div>";
        $output .= "<p class='cg-person-desc'>" . $person_desc . "</p>";
        $output .= "</div>";
        $output .= "<div class='cg-person-social-container'>";
        if ( $person_social_icon_1 != "" ) {
            $output .= do_shortcode( '[cg_social_icons icon="' . $person_social_icon_1 . '" size="fa-lg" url="' . $person_social_icon_1_url . '"]' );
        }
        if ( $person_social_icon_2 != "" ) {
            $output .= do_shortcode( '[cg_social_icons icon="' . $person_social_icon_2 . '" size="fa-lg" url="' . $person_social_icon_2_url . '"]' );
        }
        if ( $person_social_icon_3 != "" ) {
            $output .= do_shortcode( '[cg_social_icons icon="' . $person_social_icon_3 . '" size="fa-lg" url="' . $person_social_icon_3_url . '"]' );
        }
        if ( $person_social_icon_4 != "" ) {
            $output .= do_shortcode( '[cg_social_icons icon="' . $person_social_icon_4 . '" size="fa-lg" url="' . $person_social_icon_4_url . '"]' );
        }
        if ( $person_social_icon_5 != "" ) {
            $output .= do_shortcode( '[cg_social_icons icon="' . $person_social_icon_5 . '" size="fa-lg" url="' . $person_social_icon_5_url . '"]' );
        }

        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";
        return $output;
    }

}
add_shortcode( 'cg_person', 'cg_person' );

// [cg_services]
function cg_services_shortcode( $params = array(), $content = null ) {
    extract( shortcode_atts( array(
        'image_url' => '',
        'title' => 'Title',
        'link_name' => '',
        'link_url' => ''
                    ), $params ) );

    $content = do_shortcode( $content );
    $our_services = '
		<div class="cg-services">
			<div class="cg-services-img-wrap"><img src="' . $image_url . '" alt="" /></div>
			<h4>' . $title . '</h4>
			<p>' . $content . '</p>
			<a href="' . $link_url . '">' . $link_name . '</a>
		</div>
	';
    return $our_services;
}

add_shortcode( 'cg_services', 'cg_services_shortcode' );

// [cg_notes]
function cg_notes_shortcode( $params = array(), $content = null ) {
    extract( shortcode_atts( array(
        'note_icon' => 'cg-icon-cube-1',
        'note_icon_color' => '#40a0dc',
        'title' => 'Title',
        'note_title_color' => '#ef9500',
        'note_link_url' => '',
        'note_text' => '',
                    ), $params ) );

    $note_title_color_string = '';
    $note_icon_string = '';

    $note_title_color_string = ' style="color:' . $note_title_color . ';"';
    $note_icon_string = ' style="color:' . $note_icon_color . ';"';

    $content = do_shortcode( $content );
    $cg_notes = '
		<div class="cg-notes">
			<a href="' . $note_link_url . '">
				<div class="icon ' . $note_icon . '"' . $note_icon_string . '></div>
				<div class="cg-note-content-wrap">
					<span class="cg-note-title"' . $note_title_color_string . '>' . $title . '</span>
					<span class="cg-note-text">' . $note_text . '</span>
				</div>
			</a>
		</div>
	';
    return $cg_notes;
}

add_shortcode( 'cg_notes', 'cg_notes_shortcode' );

// [cg_card]
if ( !function_exists( 'cg_card' ) ) :

    function cg_card( $atts, $content = null ) {
        extract( shortcode_atts( array(
            'type' => 'visa',
                        ), $atts ) );


        $output = "";
        $output .= "<span class='cg-card'>";

        if ( $type != "" ) {
            if ( $type == 'visa' ) {
                $output .= '<img src="' . CG_PLUGIN_URL . 'images/cc_visa.png" alt="Visa">';
            } elseif ( $type == 'mastercard' ) {
                $output .= '<img src="' . CG_PLUGIN_URL . 'images/cc_mc.png" alt="Mastercard">';
            } elseif ( $type == 'amex' ) {
                $output .= '<img src="' . CG_PLUGIN_URL . 'images/cc_amex.png" alt="American Express">';
            } elseif ( $type == 'paypal' ) {
                $output .= '<img src="' . CG_PLUGIN_URL . 'images/cc_paypal.png" alt="Paypal">';
            }
        }

        $output .= "</span>";
        return $output;
    }

    add_shortcode( 'cg_card', 'cg_card' );
endif;

function cg_titlewrap_shortcode( $atts, $content = null ) {

    return '<div class="titlewrap">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( 'cg_titlewrap', 'cg_titlewrap_shortcode' );

// [section]
function cg_section_shortcode( $params = array(), $content = null ) {

    $formatting = array(
        '&nbsp;' => '',
        '<p></p>' => '',
        '<p>[' => '[',
        ']</p>' => ']',
        ']<br />' => ']',
        '<br>[' => '[',
    );
    $content = strtr( $content, $formatting );
    $content = do_shortcode( $content );
    $container = '<section>' . $content . '</section>';
    return $container;
}

add_shortcode( 'cg_section', 'cg_section_shortcode' );

function cg_container_shortcode( $atts, $content = null ) {

    return '<div class="container">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( 'cg_container', 'cg_container_shortcode' );

function cg_rowpc80_shortcode( $atts, $content = null ) {

    return '<div class="row pc80">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( 'cg_row80pc', 'cg_rowpc80_shortcode' );

function cg_third_shortcode( $atts, $content = null ) {

    return '<div class="col-lg-4">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( 'cg_athird', 'cg_third_shortcode' );

function cg_one_third_shortcode( $atts, $content = null ) {

    return '<div class="col-ld-4">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( 'cg_one_third', 'cg_one_third_shortcode' );



