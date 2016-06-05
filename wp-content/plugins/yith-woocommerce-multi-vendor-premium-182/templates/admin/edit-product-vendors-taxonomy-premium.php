<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */
?>

<?php YITH_Vendors()->admin->add_upload_field( 'table', $vendor->header_image ) ?>

<tr class="form-field yith-choosen">
    <th scope="row" valign="top">
        <label for="yith_vendor_owner"><?php _e( 'Vendor Shop Owner', 'yith_wc_product_vendors' ); ?></label>
    </th>
    <td>
        <select name="yith_vendor_data[owner]" id="yith_vendor_owner" class="ajax_chosen_select_customer" style="width:95%;" data-placeholder="<?php _e( 'Search users', 'yith_wc_product_vendors' ); ?>">
            <?php echo $owner; ?>
        </select>
        <br />
        <span class="description"><?php _e( 'User that can manage products in this shop and view sale reports.', 'yith_wc_product_vendors' ); ?></span>
    </td>
</tr>

<tr class="form-field yith-choosen">
    <th scope="row" valign="top">
        <label for="yith_vendor_admins"><?php _e( 'Vendor Shop Admins', 'yith_wc_product_vendors' ); ?></label>
    </th>
    <td>
        <select name="yith_vendor_data[admins][]" id="yith_vendor_admins" class="ajax_chosen_select_customer" multiple="multiple" style="width:95%;" data-placeholder="Search for users">
            <?php echo $vendor_admins; ?>
        </select>
        <br />
        <span class="description"><?php _e( 'User that can manage products in this vendor shop and view sale reports.', 'yith_wc_product_vendors' ); ?></span>
    </td>
</tr>

<tr class="form-field yith-choosen">
    <th scope="row" valign="top">
        <h4><?php _e( 'Contact information :', 'yith_wc_product_vendors' ) ?></h4>
    </th>
</tr>

<tr class="form-field yith-choosen">
    <th scope="row" valign="top">
        <label for="yith_vendor_location"><?php _e( 'Location', 'yith_wc_product_vendors' ); ?></label>
    </th>

    <td>
        <input type="text" class="regular-text" name="yith_vendor_data[location]" id="yith_vendor_location" placeholder="MyStore S.A. Avenue MyStore 55, 1800 Vevey, Switzerland" value="<?php echo $vendor->location ?>" /><br />
    </td>
</tr>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="yith_vendor_store_email"><?php _e( 'Store email', 'yith_wc_product_vendors' ); ?></label>
    </th>
    <td>
        <input type="text" class="regular-text" name="yith_vendor_data[store_email]" id="yith_vendor_store_email" value="<?php echo $vendor->store_email ?>" /><br />
    </td>
</tr>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="yith_vendor_telephone"><?php _e( 'Telephone', 'yith_wc_product_vendors' ); ?></label>
    </th>
    <td>
        <input type="text" class="regular-text" name="yith_vendor_data[telephone]" id="yith_vendor_telephone" value="<?php echo $vendor->telephone ?>" /><br />
    </td>
</tr>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="yith_vendor_vat"><?php _e( 'VAT/SSN', 'yith_wc_product_vendors' ); ?></label>
    </th>
    <td>
        <input type="text" class="regular-text" name="yith_vendor_data[vat]" id="yith_vendor_vat" value="<?php echo $vendor->vat ?>" /><br />
    </td>
</tr>

<tr class="form-field">
    <th>
        <?php $socials = $vendor->socials ?>
        <h4><?php _e( 'Social profile:', 'yith_wc_product_vendors' ) ?></h4>
    </th>
</tr>

<?php foreach( $social_fields as $social => $label ) : ?>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="yith_vendor_social_<?php echo $social ?>"><?php echo $label ?></label>
    </th>

    <td>
        <input type="text" class="regular-text" name="yith_vendor_data[socials][<?php echo $social ?>]" id="yith_vendor_social_<?php echo $social ?>" value="<?php echo isset( $socials[ $social ] ) ? $socials[ $social ] : '' ?>" placeholder="http://" /><br />
    </td>
</tr>

<?php endforeach; ?>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="yith_vendor_paypal_email"><?php _e( 'PayPal email address', 'yith_wc_product_vendors' ); ?></label>
    </th>
    <td>
        <input type="text" class="regular-text" name="yith_vendor_data[paypal_email]" id="yith_vendor_paypal_email" value="<?php echo $vendor->paypal_email ?>" /><br />
        <br />
        <span class="description"><?php _e( 'Vendor\'s PayPal email address where profits will be delivered.', 'yith_wc_product_vendors' ); ?></span>
    </td>
</tr>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="yith_vendor_enable_selling"><?php _e( 'Enable sales', 'yith_wc_product_vendors' ); ?></label>
    </th>
    <td>
        <?php $enable_selling = 'yes' == $vendor->enable_selling ? true : false; ?>
        <input type="checkbox" name="yith_vendor_data[enable_selling]" id="yith_vendor_enable_selling" value="yes" <?php checked( $enable_selling )?> /><br />
        <br />
        <span class="description"><?php _e( 'Enable or disable product sales.', 'yith_wc_product_vendors' ); ?></span>
    </td>
</tr>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="yith_vendor_skip_review"><?php _e( 'Skip admin review', 'yith_wc_product_vendors' ); ?></label>
    </th>
    <td>
        <?php $skip_review = 'yes' == $vendor->skip_review ? true : false; ?>
        <input type="checkbox" name="yith_vendor_data[skip_review]" id="yith_vendor_skip_review" value="yes" <?php checked( $skip_review )?> /><br />
        <br />
        <span class="description"><?php _e( 'Allow vendors to add products without admin review', 'yith_wc_product_vendors' ); ?></span>
    </td>
</tr>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="yith_vendor_enable_selling"><?php _e( 'Commission:', 'yith_wc_product_vendors' ); ?></label>
    </th>
    <td>
        <input type="number" class="regular-text" name="yith_vendor_data[commission]" id="yith_vendor_commission" value="<?php echo esc_attr( $vendor->get_commission() * 100 ); ?>" min="0" max="100" step="0.1" /> %<br/>

        <br />
        <span class="description"><?php _e( 'Percentage of the total sale price that this vendor receives.', 'yith_wc_product_vendors' ); ?></span>
    </td>
</tr>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="yith_vendor_registration_date"><?php _e( 'Registration date:', 'yith_wc_product_vendors' ); ?></label>
    </th>
    <td>
        <?php echo $vendor->get_registration_date( 'display' ) ?>
    </td>
</tr>
