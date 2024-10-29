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
<tr class="form-field">
    <th scope="row" valign="top"><label for="_alf_dp_disabled"><?php echo __('Disable Products', $this->plugin_name); ?></label></th>
    <td>
        <input type="checkbox" name="_alf_dp_disabled" id="_alf_dp_disabled" <?php echo ( $disabled === 'yes' )? 'checked' : ''; ?>/>
        <span class="description"><?php echo __('Check to disable this Category\'s product from Purchases', $this->plugin_name); ?></span>
    </td>
</tr>
<tr class="form-field">
    <th scope="row" valign="top"><?php echo __('Schedule Unavailability', $this->plugin_name); ?></th>
    <td>
        <span id='unavailability_schedule' class='alf_dp_cat'>
            <span class="orig">
                <input type="text" name="alf_dp_schedule[0][from]" placeholder="From (MM-DD-YYYY)" class="input-text alf_dp_schedule alf_dp_schedule_from" size="6" value="<?php echo ( isset($schedules[0]) && isset($schedules[0]["from"]) )? $schedules[0]["from"] : ''; ?>" /> 
                <input type="text" name="alf_dp_schedule[0][to]" placeholder="To (MM-DD-YYYY)" class="input-text alf_dp_schedule alf_dp_schedule_to" size="6" value="<?php echo ( isset($schedules[0]) && isset($schedules[0]["to"]) )? $schedules[0]["to"] : ''; ?>"/>
                <label for="alf_dp_schedule[0][annual]">Annual<br/> 
                    <input type="checkbox" class="checkbox" name="alf_dp_schedule[0][annual]" value="1" <?php echo ( isset($schedules[0]) && isset($schedules[0]["annual"]) )? "checked" : ''; ?> /></label>
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
    </td>
</tr>