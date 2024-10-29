<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://allysonflores.com/
 * @since      1.0.0
 *
 * @package    Alf_Disable_Product_For_Woocommerce
 * @subpackage Alf_Disable_Product_For_Woocommerce/admin/partials
 */
?>
<div id='alf_dp_tab' class='panel woocommerce_options_panel'>
    <div class="options_group">
        <?php
        $main_dp_btn_txt = get_option( 'alf_dp_button_text', __( 'Unavailable', $this->plugin_name ) );
        $main_dp_msg_txt = get_option( 'alf_dp_msg_text', __( 'This product is unavailable as of the moment.', $this->plugin_name ) );
        woocommerce_wp_checkbox(array(
            'id'            => '_alf_dp_disabled',
            'label'         => __( 'Disable Product', $this->plugin_name ),
            'description'   => '<small>'.__( 'Check to disable this product from Purchases', $this->plugin_name ).'</small>'
        ));
        ?>
        <p class="form-field dimensions_field">
            <label for="unavailability_schedule"><?php echo __( 'Schedule Unavailability', $this->plugin_name ) ?></label>
            <span id="unavailability_schedule" class="wrap">
                <span class="orig">
                    <input type="text" name="alf_dp_schedule[0][from]" placeholder="From (MM-DD-YYYY)" class="input-text alf_dp_schedule alf_dp_schedule_from" size="6" value="<?php echo ( isset($schedules[0]) && isset($schedules[0]["from"]) )? $schedules[0]["from"] : ''; ?>" /> 
                    <input type="text" name="alf_dp_schedule[0][to]" placeholder="To (MM-DD-YYYY)" class="input-text alf_dp_schedule alf_dp_schedule_to" size="6" value="<?php echo ( isset($schedules[0]) && isset($schedules[0]["to"]) )? $schedules[0]["to"] : ''; ?>" />
                    <label for="alf_dp_schedule[0][annual]">Annual<br/> <input type="checkbox" class="checkbox" name="alf_dp_schedule[0][annual]" value="1" <?php echo ( isset($schedules[0]) && isset($schedules[0]["annual"]) )? "checked" : ''; ?> /></label>
                    <button class="add-field" type="button">&plus;</button> 
                </span>
                <span class="dup"><?php
                if( isset($schedules) && is_array($schedules) && count($schedules) > 1 ){
                    foreach ($schedules as $key => $sched) { if($key == 0){ continue; } ?>
                        <span class='dfield'>
                            <input type="text" name="alf_dp_schedule[<?php echo $key; ?>][from]" placeholder="From (MM-DD-YYYY)" class="input-text alf_dp_schedule alf_dp_schedule_from" size="6" value="<?php echo $sched["from"]; ?>" />  
                            <input type="text" name="alf_dp_schedule[<?php echo $key; ?>][to]" placeholder="To (MM-DD-YYYY)" class="input-text alf_dp_schedule alf_dp_schedule_to" size="6" value="<?php echo $sched["to"]; ?>" />
                            <label for="alf_dp_schedule[<?php echo $key; ?>][annual]"><br/><input type="checkbox" class="checkbox" name="alf_dp_schedule[<?php echo $key; ?>][annual]" value="1" <?php echo isset($sched["annual"])? "checked" : ""; ?> /></label>
                            <button type="button" class="rmv-field">-</button>
                        </span>  
                    <?php }
                }
                ?></span>
            </span>
        </p>
        <hr/>
        <?php 
        woocommerce_wp_text_input(array(
            'id'            => '_alf_dp_btn_text',
            'label'         => __( 'Button Text', $this->plugin_name ),
            'placeholder'   => $main_dp_btn_txt,
            'description'   => '<small>'.__( 'To change default click here <a href="'. admin_url() .'admin.php?page=wc-settings&tab=products&section=alf-disable-purchase" target="_blank">Settings</a>', $this->plugin_name ) .'</small>',
            'style'         => 'width: 140px;'
        ));
        woocommerce_wp_text_input(array(
            'id'            => '_alf_dp_msg_text',
            'label'         => __( 'Single Product Notice', $this->plugin_name ),
            'placeholder'   => $main_dp_msg_txt,
            'description'   => '<small>'.__( 'To change default click here <a href="'. admin_url() .'admin.php?page=wc-settings&tab=products&section=alf-disable-purchase" target="_blank">Settings</a>', $this->plugin_name ) .'</small>'
        ));
        ?>
    </div>
</div>