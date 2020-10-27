<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<div class="wf-tab-content" data-id="<?php echo $target_id;?>">
	<p><?php _e('The company, shipping address and from address details in the sections below will be used to fill up the invoice and related documents accordingly.','print-invoices-packing-slip-labels-for-woocommerce'); ?></p>
	<h3 style="margin-bottom:0px; padding-bottom:5px; border-bottom:dashed 1px #ccc;"><?php _e('Company Info', 'print-invoices-packing-slip-labels-for-woocommerce'); ?></h3>
	<table class="form-table wf-form-table">
	    <?php
		self::generate_form_field(array(
			array(
			'label'=>__("Name",'print-invoices-packing-slip-labels-for-woocommerce'),
			'option_name'=>"woocommerce_wf_packinglist_companyname",
			),
			array(
				'type'=>"uploader",
				'label'=>__("Logo",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"woocommerce_wf_packinglist_logo",
			),
			array(
				'type'=>'textarea',
				'label'=>__("Footer",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"woocommerce_wf_packinglist_footer",
				'help_text'=>__("Set up a footer which will be used across the respective documents.",'print-invoices-packing-slip-labels-for-woocommerce'),
			),
		));
		?>
	</table>

	<h3 style="margin-bottom:0px; padding-bottom:5px; border-bottom:dashed 1px #ccc;">
		<?php _e('Address(Sender details)', 'print-invoices-packing-slip-labels-for-woocommerce'); ?> <?php echo Wf_Woocommerce_Packing_List_Admin::set_tooltip('address_details'); ?>
		<?php $tooltip_conf=Wf_Woocommerce_Packing_List_Admin::get_tooltip_configs('load_default_address'); ?>	
			<a class="wf_pklist_load_address_from_woo <?php echo $tooltip_conf['class'];?>" <?php echo $tooltip_conf['text'];?>>
				<span class="dashicons dashicons-admin-page"></span>
				<?php _e('Load from WooCommerce','print-invoices-packing-slip-labels-for-woocommerce');?>
			</a>
		</h3>
	<table class="form-table wf-form-table">
		<?php
		self::generate_form_field(array(
			array(
				'label'=>__("Department/Business Unit/Sender",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"woocommerce_wf_packinglist_sender_name",
				'mandatory'=>true,
			),
			array(
				'label'=>__("Address line 1",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"woocommerce_wf_packinglist_sender_address_line1",
				'mandatory'=>true,
			),
			array(
				'label'=>__("Address line 2",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"woocommerce_wf_packinglist_sender_address_line2",
			),
			array(
				'label'=>__("City",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"woocommerce_wf_packinglist_sender_city",
				'mandatory'=>true,
			)
		));
        $country_selected=Wf_Woocommerce_Packing_List::get_option('wf_country');
        if( strstr( $country_selected, ':' ))
        {
			$country_selected = explode( ':', $country_selected );
			$country         = current( $country_selected );
			$state           = end( $country_selected );                                            
		}else 
		{
			$country = $country_selected;
			$state   = '*';
		}                                 
        ?>
        <tr valign="top">
	        <th scope="row" >
	        	<label for="wf_country">
	        	<?php _e('Country/State','print-invoices-packing-slip-labels-for-woocommerce'); ?><span class="wt_pklist_required_field">*</span></label></th>
	        <td>
	        	<select name="wf_country" data-placeholder="<?php esc_attr_e( 'Choose a country&hellip;', 'woocommerce' ); ?>" required="required">
						<?php WC()->countries->country_dropdown_options($country,$state ); ?>
         		</select>
	        </td>
	        <td></td>
	    </tr>
	    <?php
	    self::generate_form_field(array(
			array(
				'label'=>__("Postal code",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"woocommerce_wf_packinglist_sender_postalcode",
				'mandatory'=>true,
			),
			array(
				'label'=>__("Contact number",'print-invoices-packing-slip-labels-for-woocommerce'), 
				'option_name'=>"woocommerce_wf_packinglist_sender_contact_number",
			),
			array(
				'label'=>__("VAT",'print-invoices-packing-slip-labels-for-woocommerce'), 
				'option_name'=>"woocommerce_wf_packinglist_sender_vat",
			),
		));
	    ?>
	</table>
	<h3 style="margin-bottom:0px; padding-bottom:5px; border-bottom:dashed 1px #ccc;"><?php _e('Other settings', 'print-invoices-packing-slip-labels-for-woocommerce'); ?></h3>
	<table class="form-table wf-form-table">
	    <?php
	    $mPDF_addon_url='https://wordpress.org/plugins/mpdf-addon-for-pdf-invoices/';
	    $form_fields=array(
			array(
				'type'=>"radio",
				'label'=>__("Preview before printing",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"woocommerce_wf_packinglist_preview",
				'radio_fields'=>array(
					'enabled'=>__('Enabled','print-invoices-packing-slip-labels-for-woocommerce'),
					'disabled'=>__('Disabled','print-invoices-packing-slip-labels-for-woocommerce')
				),
			),
			array(
				'type'=>"radio",
				'label'=>__("Enable RTL support",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"woocommerce_wf_add_rtl_support",
				'radio_fields'=>array(
					'Yes'=>__('Yes','print-invoices-packing-slip-labels-for-woocommerce'),
					'No'=>__('No','print-invoices-packing-slip-labels-for-woocommerce')
				),
				'help_text'=>sprintf(__("RTL support for documents. For better RTL integration in PDF documents please use our %s mPDF addon %s.", 'print-invoices-packing-slip-labels-for-woocommerce'), '<a href="'.$mPDF_addon_url.'" target="_blank">', '</a>'),
			)
		);

	    /**
	    *	@since 2.6.6
	    *	Add PDF library switching option if multiple libraries available
	    */
	    if(is_array($pdf_libs) && count($pdf_libs)>1)
		{
			$pdf_libs_form_arr=array();
			foreach ($pdf_libs as $key => $value)
			{
				$pdf_libs_form_arr[$key]=(isset($value['title']) ? $value['title'] : $key);
			}
			$form_fields[]=array(
				'type'=>"select",
				'label'=>__("PDF library",'print-invoices-packing-slip-labels-for-woocommerce'),
				'option_name'=>"active_pdf_library",
				'select_fields'=>$pdf_libs_form_arr,
				'help_text'=>__('The default library to generate PDF', 'print-invoices-packing-slip-labels-for-woocommerce'),
			);
		}

		self::generate_form_field($form_fields);
		?>
	</table>
	<?php 
    include "admin-settings-save-button.php";
    ?>
</div>