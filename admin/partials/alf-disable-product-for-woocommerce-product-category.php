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
<div class="form-field" style="border-top: 1px solid #ccc; padding: 20px 0 0;">
    <label for="_alf_dp_disabled"><?php echo __('Disable Products', $this->plugin_name); ?></label>
    <input type="checkbox" name="_alf_dp_disabled" id="_alf_dp_disabled" />
    <span class="description"><?php echo __('Check to disable this Category\'s product from Purchases', $this->plugin_name); ?></span>
</div>
<div class="form-field">
    <div id='unavailability_schedule' class='alf_dp_cat'>
        <div class="orig">
            <div class="form-field">
                <label for="alf_dp_schedule[0][from]"><?php echo __('Schedule Unavailability', $this->plugin_name); ?></label>
                <input type="text" name="alf_dp_schedule[0][from]" placeholder="From (MM-DD-YYYY)" class="input-text alf_dp_schedule alf_dp_schedule_from" size="6" /> 
                <input type="text" name="alf_dp_schedule[0][to]" placeholder="To (MM-DD-YYYY)" class="input-text alf_dp_schedule alf_dp_schedule_to" size="6" />
                <label for="alf_dp_schedule[0][annual]">Annual<br/> 
                    <input type="checkbox" class="checkbox" name="alf_dp_schedule[0][annual]" value="1" /></label>
                <button class="add-field" type="button">&plus;</button> 
            </div>
        </div>
        <div class="dup"></div>
    </div>
</div>