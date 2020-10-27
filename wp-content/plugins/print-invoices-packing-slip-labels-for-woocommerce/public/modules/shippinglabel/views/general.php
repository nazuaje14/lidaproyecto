<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="wf-tab-content" data-id="<?php echo $target_id;?>">
<p><?php _e('Configure the general settings required for the shipping label.','print-invoices-packing-slip-labels-for-woocommerce');?></p>
<table class="wf-form-table">
	<?php
    Wf_Woocommerce_Packing_List_Admin::generate_form_field(array(
        array(
            'type'=>"select",
            'label'=>__("Shipping label size", 'print-invoices-packing-slip-labels-for-woocommerce'),
            'option_name'=>"woocommerce_wf_packinglist_label_size",
            'select_fields'=>array(
                2=>__('Full Page', 'print-invoices-packing-slip-labels-for-woocommerce'),
            )
        ),
        array(
            'type'=>"radio",
            'label'=>__("Add footer", 'print-invoices-packing-slip-labels-for-woocommerce'),
            'option_name'=>"woocommerce_wf_packinglist_footer_sl",
            'field_name'=>"woocommerce_wf_packinglist_footer_sl",
            'radio_fields'=>array(
                'Yes'=>__('Yes', 'print-invoices-packing-slip-labels-for-woocommerce'),
                'No'=>__('No', 'print-invoices-packing-slip-labels-for-woocommerce')
            ),
            'help_text'=>__("Add footer in shipping label",'print-invoices-packing-slip-labels-for-woocommerce'),
        ),
    ), $this->module_id);
    ?>
</table>
<?php 
include plugin_dir_path( WF_PKLIST_PLUGIN_FILENAME )."admin/views/admin-settings-save-button.php";
?>
</div>