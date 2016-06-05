<?php
if( !defined( 'ABSPATH' ) )
    exit;
?>
<div class="wrap">
	<h3><?php _e( 'Export Settings', 'ywcds' ); ?></h3>

<div class="ywcds-exportsettings" style="background: #fff;">
    <form id="ywcds-export" method="post">
        <table class="form-table">
            <input type="hidden" name="ywcds_export_now" value="1">

            <tbody>

            <tr valign="top" class="manual-exportation">
                <th scope="row" class="titledesc" style="padding: 20px;">
                    <label for="ywcds_export_start_date"><?php _e( 'From', 'ywcds' ); ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input type="text" class="date-picker" name="ywcds_export_start_date"
                           id="ywcds_export_start_date" placeholder="dd-mm-yyyy"
                           maxlength="10" value="" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}">
						<span
                            class="description"><?php _e( 'Choose starting date. Leave it unselected to start from the first item found.', 'ywcds' ); ?></span>
                </td>
            </tr>

            <tr valign="top" class="manual-exportation">
                <th scope="row" class="titledesc" style="padding: 20px;">
                    <label for="ywcds_export_end_date"><?php _e( 'To', 'ywcds' ); ?></label>
                </th>
                <td class="forminp forminp-text">
                    <input type="text" class="date-picker" name="ywcds_export_end_date"
                           id="ywcds_export_end_date" placeholder="dd-mm-yyyy"
                           maxlength="10" value="" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01])-(0[1-9]|1[012])-[0-9]{4}">

						<span
                            class="description"><?php _e( 'Choose end date. Leave it unselected to end with the last item found.', 'ywcds' ); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                </th>
                <td class="forminp forminp-color plugin-option">
                    <input type="submit" value="<?php _e( 'Export', 'ywcds' ); ?>" id="ywcds_export_btn"
                           class="button-primary" name="submit">
                </td>
            </tr>

            </tbody>
        </table>
    </form>
</div>
