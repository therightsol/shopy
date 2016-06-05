<?php
if( !defined( 'ABSPATH' ) )
    exit;
?>
<div class="ywcds_form_container">
    <div id="ywcds_add_donation_form">
        <div class="ywcds_amount_field">
            <label for="ywcds_amount"><?php echo $message_for_donation;?></label>
            <input type="text" class="ywcds_amount" name="ywcds_amount"/>
        </div>
        <div class="ywcds_button_field">
            <input type="hidden" class="ywcds_product_id" name="add_donation_to_cart" value="<?php echo $product_id;?>" />
            <input type="submit" name="ywcds_submit_widget" class="ywcds_submit_widget <?php echo $button_class;?>" value="<?php echo $button_text;?>" />
        </div>
    </div>
    <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ) ?>" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" />
    <div class="ywcds_message woocommerce-message" style="display: none;"></div>
</div>