<?php
if( !defined( 'ABSPATH' ) )
    exit;

if( !class_exists( 'YWCDS_Typography' ) ){

    class YWCDS_Typography{


        public static function output( $option ){

        $value  =    get_option( $option['id'] );
        $defaults   =   array();
       if( !isset( $option['default'] ) )
           $defaults    =   $option['default'];

        $value = wp_parse_args( $value, $defaults );



    ?>

            <tr id="<?php echo $option['id'];?>" valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_html( $option['title'] ); ?></label>
                </th>
                <td class="forminp forminp-<?php echo sanitize_title( $option['type'] ) ?>[size]" >
                  <input type="number" name="<?php echo esc_attr( $option['id'] );?>[size]" min="1" max="99" value="<?php echo $value['size'];?>" >
                </td>
                <td class="forminp forminp-<?php echo sanitize_title( $option['type'] ) ?>[unit]">
                    <select name="<?php echo esc_attr( $option['id'] );?>[unit]" id="<?php echo  esc_attr( $option['id'] ) ?>-unit" >
                        <option value="px" <?php selected( $value['unit'], 'px' ) ?>><?php _e( 'px', 'ywcca' ) ?></option>
                        <option value="em" <?php selected( $value['unit'], 'em' ) ?>><?php _e( 'em', 'ywcca' ) ?></option>
                        <option value="pt" <?php selected( $value['unit'], 'pt' ) ?>><?php _e( 'pt', 'ywcca' ) ?></option>
                        <option value="rem" <?php selected( $value['unit'], 'rem' ) ?>><?php _e( 'rem', 'ywcca' ) ?></option>
                    </select>
                </td>
                <td class="forminp forminp-<?php echo sanitize_title( $option['type'] ) ?>[style]" >

                    <select name="<?php echo esc_attr( $option['id'] );?>[style]" id="<?php echo  esc_attr( $option['id'] ) ?>-style">
                        <option value="regular" <?php selected( $value['style'], 'regular' ) ?>><?php _e( 'Regular', 'ywcca' ) ?></option>
                        <option value="bold" <?php selected( $value['style'], 'bold' ) ?>><?php _e( 'Bold', 'ywcca' ) ?></option>
                        <option value="extra-bold" <?php selected( $value['style'], 'extra-bold' ) ?>><?php _e( 'Extra bold', 'ywcca' ) ?></option>
                        <option value="italic" <?php selected($value['style'], 'italic' ) ?>><?php _e( 'Italic', 'ywcca' ) ?></option>
                        <option value="bold-italic" <?php selected( $value['style'], 'bold-italic' ) ?>><?php _e( 'Italic bold', 'ywcca' ) ?></option>
                    </select>
                </td>
                <td class="forminp forminp-<?php echo sanitize_title( $option['type'] ) ?>[transform]" >

                    <select name="<?php echo esc_attr( $option['id'] );?>[transform]" id="<?php echo  esc_attr( $option['id'] ) ?>-transform">
                        <option value="none" <?php selected( $value['transform'], 'none' ) ?>><?php _e( 'None', 'ywcca' ) ?></option>
                        <option value="lowercase" <?php selected( $value['transform'], 'lowercase' ) ?>><?php _e( 'Lowercase', 'ywcca' ) ?></option>
                        <option value="uppercase" <?php selected( $value['transform'], 'uppercase' ) ?>><?php _e( 'Uppercase', 'ywcca' ) ?></option>
                        <option value="capitalize" <?php selected($value['transform'], 'capitalize' ) ?>><?php _e( 'Capitalize', 'ywcca' ) ?></option>
                    </select>
                </td>


            </tr>

     <?php
        }
    }
}