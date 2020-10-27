<?php
/**
 * Necessary functions for customizer module
 *
 * @link       
 * @since 4.0.0     
 *
 * @package  Wf_Woocommerce_Packing_List  
 */

if (!defined('ABSPATH')) {
    exit;
}
include "class-customizer-product-table.php";
include "class-customizer-address.php";
class Wf_Woocommerce_Packing_List_CustomizerLib
{
	use Wf_Woocommerce_Packing_List_Customizer_Product_table; /* product table related functions */
	use Wf_Woocommerce_Packing_List_Customizer_Address; /* address related functions */


	const TO_HIDE_CSS='wfte_hidden';
	public static $reference_arr=array();
	public static function get_order_number($order,$template_type)
	{
		$order_number=$order->get_order_number();
		return apply_filters('wf_pklist_alter_order_number', $order_number, $template_type, $order);
	}

	/**
	* @since 4.0.3
	* get documnted generated date
	*/
	public static function get_printed_on_date($html)
	{
		$printed_on_format=self::get_template_html_attr_vl($html, 'data-printed_on-format', 'm/d/Y');
		return date($printed_on_format);
	}

	public static function set_order_data($find_replace,$template_type,$html,$order=null)
	{
		if(!is_null($order))
        {
        	$wc_version=WC()->version;
			$order_id=$wc_version<'2.7.0' ? $order->id : $order->get_id();

			$find_replace['[wfte_order_number]']=self::get_order_number($order,$template_type);
			if(Wf_Woocommerce_Packing_List_Public::module_exists('invoice'))
			{
				$find_replace['[wfte_invoice_number]']=Wf_Woocommerce_Packing_List_Invoice::generate_invoice_number($order,false); //do not force generate
			}else
			{
				$find_replace['[wfte_invoice_number]']='';
			}

			//order date
			$order_date_match=array();
			$order_date_format='m/d/Y';
			if(preg_match('/data-order_date-format="(.*?)"/s',$html,$order_date_match))
			{
				$order_date_format=$order_date_match[1];
			}

			$order_date=get_the_date($order_date_format,$order_id);
			$order_date=apply_filters('wf_pklist_alter_order_date', $order_date, $template_type, $order);
			$find_replace['[wfte_order_date]']=$order_date;

			//invoice date
			if(Wf_Woocommerce_Packing_List_Public::module_exists('invoice'))
			{
				$invoice_date_match=array();
				$invoice_date_format='m/d/Y';
				if(preg_match('/data-invoice_date-format="(.*?)"/s',$html,$invoice_date_match))
				{
					$invoice_date_format=$invoice_date_match[1];
				}

				//must call this line after `generate_invoice_number` call
				$invoice_date=Wf_Woocommerce_Packing_List_Invoice::get_invoice_date($order_id,$invoice_date_format,$order);
				$invoice_date=apply_filters('wf_pklist_alter_invoice_date',$invoice_date,$template_type,$order);
				$find_replace['[wfte_invoice_date]']=$invoice_date;
			}else
			{
				$find_replace['[wfte_invoice_date]']='';
			}

			//dispatch date
			$dispatch_date_match=array();
			$dispatch_date_format='m/d/Y';
			if(preg_match('/data-dispatch_date-format="(.*?)"/s',$html,$dispatch_date_match))
			{
				$dispatch_date_format=$dispatch_date_match[1];
			}
			$dispatch_date=get_the_date($dispatch_date_format,$order_id);
			$dispatch_date=apply_filters('wf_pklist_alter_dispatch_date',$dispatch_date,$template_type,$order);
			$find_replace['[wfte_dispatch_date]']=$dispatch_date;
		}
		return $find_replace;
	}


	public static function package_doc_items($find_replace,$template_type,$order,$box_packing,$order_package)
	{
		if(!is_null($box_packing))
        {
			$box_details=$box_packing->wf_packinglist_get_table_content($order,$order_package);
			
			$box_name=$box_details['name'];
			if(Wf_Woocommerce_Packing_List::get_option('woocommerce_wf_packinglist_package_type')=='box_packing')
			{
				$box_name=apply_filters('wf_pklist_include_box_name_in_packinglist',$box_name, $box_details, $order);
				$box_name_label=apply_filters('wf_pklist_alter_box_name_label','Box name',$template_type,$order);
				$find_replace['[wfte_box_name]']=(trim($box_name)!="" ? $box_name_label.': '.$box_name : '');
			}else
			{
				$find_replace['[wfte_box_name]']='';
			}
		}else
		{
			$find_replace['[wfte_box_name]']='';
		}
		return $find_replace;
	}

	/**
	* 	Set extra data like footer, special_notes. Modules can override these type of data
	*	@since 4.0.3
	*/
	private static function set_extra_text_data($find_replace,$data_slug,$template_type,$html,$order=null)
	{
		if(!isset($find_replace['[wfte_'.$data_slug.']'])) /* check already added */
		{
			//module settings are saved under module id
			$module_id=Wf_Woocommerce_Packing_List::get_module_id($template_type);

			$txt_data=Wf_Woocommerce_Packing_List::get_option('woocommerce_wf_packinglist_'.$data_slug,$module_id);
			if($txt_data===false || $txt_data=='') //custom data from module not present or empty
			{
				//call main data
				$txt_data=Wf_Woocommerce_Packing_List::get_option('woocommerce_wf_packinglist_'.$data_slug);
			}
			if(!is_null($order))
			{
				$txt_data=apply_filters('wf_pklist_alter_'.$data_slug.'_data', $txt_data, $template_type, $order);
			}
			$find_replace['[wfte_'.$data_slug.']']=nl2br($txt_data);
		}
		return $find_replace;
	}

	/**
	* 	Process text data like return policy, sale terms, transport terms.
	*	@since 4.0.3
	*/
	private static function set_text_data($find_replace,$data_slug,$template_type,$html,$order=null)
	{
		if(!isset($find_replace['[wfte_'.$data_slug.']'])) /* check already added */
		{
			$txt_data=Wf_Woocommerce_Packing_List::get_option('woocommerce_wf_packinglist_'.$data_slug);
			if(!is_null($order))
			{
				$txt_data=apply_filters('wf_pklist_alter_'.$data_slug.'_data',$txt_data,$template_type,$order);
			}
			$find_replace['[wfte_'.$data_slug.']']=nl2br($txt_data);
		}
		return $find_replace;
	}

	/**
	* 	Set other data, includes barcode, signature etc
	*	@since 4.0.0
	*	@since 4.0.2	Included total weight function, added $html argument
	*/
	public static function set_other_data($find_replace,$template_type,$html,$order=null)
	{
		//module settings are saved under module id
		$module_id=Wf_Woocommerce_Packing_List::get_module_id($template_type);

		//return policy, sale terms, transport terms
		$find_replace=self::set_text_data($find_replace,'return_policy',$template_type,$html,$order);
		$find_replace=self::set_text_data($find_replace,'transport_terms',$template_type,$html,$order);
		$find_replace=self::set_text_data($find_replace,'sale_terms',$template_type,$html,$order);

		//footer data
		$find_replace=self::set_extra_text_data($find_replace,'footer',$template_type,$html,$order);
		
		//special notes
		$find_replace=self::set_extra_text_data($find_replace,'special_notes',$template_type,$html,$order);


		//signature	
		$signture_url=Wf_Woocommerce_Packing_List::get_option('woocommerce_wf_packinglist_invoice_signature',$module_id);
		$find_replace['[wfte_signature_url]']=$signture_url;
		$find_replace['[wfte_signature]']=$signture_url;

		//barcode, additional info
		if(!is_null($order))
        {
			if(!isset($find_replace['[wfte_barcode_url]'])) /* check already added */
			{
				$invoice_number=Wf_Woocommerce_Packing_List_Public::module_exists('invoice') ? Wf_Woocommerce_Packing_List_Invoice::generate_invoice_number($order,false) : ''; 
				$invoice_number=apply_filters('wf_pklist_alter_barcode_data', $invoice_number, $template_type, $order);
				$find_replace['[wfte_barcode_url]']='';
				$find_replace['[wfte_barcode]']='';
				if($invoice_number!="" && strpos($html, '[wfte_barcode_url]') !== false)
				{
					include_once plugin_dir_path(WF_PKLIST_PLUGIN_FILENAME).'includes/class-wf-woocommerce-packing-list-barcode_generator.php';
					$barcode_width_factor=2;
					$barcode_width_factor=apply_filters('wf_pklist_alter_barcode_width_factor',$barcode_width_factor,$template_type,$invoice_number,$order);
					
					$barcode_file_type='png';
					$barcode_file_type=apply_filters('wf_pklist_alter_barcode_file_type', $barcode_file_type, $template_type, $order);
					
					$barcode_url=Wf_Woocommerce_Packing_List_Barcode_generator::generate($invoice_number, $barcode_file_type, $barcode_width_factor);
					if($barcode_url)
					{
						$find_replace['[wfte_barcode_url]']=$barcode_url;
						$find_replace['[wfte_barcode]']='1'; //just a value to prevent hiding barcode
					}
				}
			}

			if(!isset($find_replace['[wfte_additional_data]'])) /* check already added */
			{
				$additional_info='';
				$find_replace['[wfte_additional_data]']=apply_filters('wf_pklist_add_additional_info',$additional_info,$template_type,$order);
			}			
		}

		//set total weight
		$find_replace=self::set_total_weight($find_replace,$template_type,$html,$order);

		if(!isset($find_replace['[wfte_printed_on]'])) /* check already added */
		{
			//prints the current date with the given format
			$find_replace['[wfte_printed_on]']=self::get_printed_on_date($html);
		}
		return $find_replace;
	}
	
	/**
	* Total price in words
	*	@since 4.0.2
	*/
	public static function set_total_in_words($total,$find_replace,$template_type,$html,$order=null)
	{
		if(strpos($html,'[wfte_total_in_words]')!==false) //if total in words placeholder exists then only do the process
        {
        	$total_in_words=self::convert_number_to_words($total);
        	$total_in_words=apply_filters('wf_pklist_alter_total_price_in_words',$total_in_words,$template_type,$order);
        	$find_replace['[wfte_total_in_words]']=$total_in_words;
        }
        return $find_replace;
	}

	/**
	*	Get the total weight of an order.
	*	@since 4.0.2	
	*	@param array $find_replace find and replace data
	* 	@param string $template_type document type Eg: invoice
	*	@param string $html template HTML
	* 	@param object $order order object
	*
	*	@return array $find_replace
	*/
	public static function set_total_weight($find_replace, $template_type, $html, $order=null)
	{
		$total_weight=0;
		if(strpos($html,'[wfte_weight]')!==false && !isset($find_replace['[wfte_weight]'])) //if total weight placeholder exists then only do the process, If already added then skip
        {
			if(!is_null($order))
			{
				$order_items=$order->get_items();
				$find_replace['[wfte_weight]']=__('n/a','wf-woocommerce-packing-list');
				if($order_items)
				{
					foreach($order_items as $item)
					{
						$quantity=(int) $item->get_quantity(); // get quantity
				        $product=$item->get_product(); // get the WC_Product object
				        $weight=0;
				        if($product)
				        {
				        	$weight=(float) $product->get_weight(); // get the product weight
				        }
				        $total_weight+=floatval($weight*$quantity);
					}
					$weight_data=$total_weight.' '.get_option('woocommerce_weight_unit');
					$weight_data=apply_filters('wf_pklist_alter_weight', $weight_data, $total_weight, $order);

					/* the below line is for adding compatibility for existing users */
					$weight_data=apply_filters('wf_pklist_alter_packinglist_weight',$weight_data,$total_weight,$order);
					$find_replace['[wfte_weight]']=$weight_data;
				}
			}else
			{
				$find_replace['[wfte_weight]']=$total_weight.' '.get_option('woocommerce_weight_unit');
			}
		}
		return $find_replace;
	}
	public static function set_extra_fields($find_replace,$template_type,$html,$order=null)
	{
		$extra_fields=array();
		//module settings are saved under module id
		$module_id=Wf_Woocommerce_Packing_List::get_module_id($template_type);
		if(!is_null($order))
        {
        	$wc_version=(WC()->version<'2.7.0') ? 0 : 1;
        	$order=($wc_version==0 ? new WC_Order($order) : new wf_order($order));
        	$order_id=($wc_version==0 ? $order->id : $order->get_id());
        	

        	//shipping method
        	if(!isset($find_replace['[wfte_shipping_method]'])) /* check already added */
			{
	        	$order_shipping =($wc_version==0 ? $order->shipping_method : $order->get_shipping_method());
	        	if(get_post_meta($order_id, '_tracking_provider', true) || $order_shipping)
	        	{
	        		$find_replace['[wfte_shipping_method]']=apply_filters('wf_pklist_alter_shipping_method', $order_shipping, $template_type, $order);
	        	}else
	        	{
	        		$find_replace['[wfte_shipping_method]']='';
	        	}
	        }

        	//tracking number
        	if(!isset($find_replace['[wfte_tracking_number]'])) /* check already added */
			{
	        	$tracking_key=Wf_Woocommerce_Packing_List::get_option('woocommerce_wf_tracking_number');
	        	$tracking_data=apply_filters('wf_pklist_tracking_data_key', $tracking_key, $template_type, $order);
	        	$tracking_details=get_post_meta($order_id, ($tracking_key!='' ? $tracking_data : '_tracking_number'), true);
	        	if($tracking_details)
	        	{
	        		$find_replace['[wfte_tracking_number]']=apply_filters('wf_pklist_alter_tracking_details', $tracking_details, $template_type, $order);    		
	        	}else
	        	{
	        		$find_replace['[wfte_tracking_number]']='';
	        	}
	        }

	        if(!isset($find_replace['[wfte_extra_fields]'])) /* check already added */
			{
		        $the_options=Wf_Woocommerce_Packing_List::get_settings($module_id);
	        	$default_options=Wf_Woocommerce_Packing_List::default_settings($module_id);
	        	$default_fields=array_values(Wf_Woocommerce_Packing_List::$default_additional_data_fields);
	        	$default_fields_label=array_flip(Wf_Woocommerce_Packing_List::$default_additional_data_fields);

	        	if(isset($the_options['wf_'.$template_type.'_contactno_email']) && is_array($the_options['wf_'.$template_type.'_contactno_email'])) //if user selected any fields
	        	{ 
	        		$user_created_fields=Wf_Woocommerce_Packing_List::get_option('wf_additional_data_fields'); //this is plugin main setting so no need to specify module id
	        		$user_created_fields=is_array($user_created_fields) ? $user_created_fields : array();

	        		//additional checkout fields
					$additional_checkout=Wf_Woocommerce_Packing_List::get_option('wf_additional_checkout_data_fields');
					
					/* if it is a numeric array convert it to associative.[Bug fix 4.0.1]    */
			        $additional_checkout=Wf_Woocommerce_Packing_List::process_checkout_fields($additional_checkout);

			        $user_created_fields=array_merge($user_created_fields,$additional_checkout);

	        		foreach($the_options['wf_'.$template_type.'_contactno_email'] as $val) //user selected fields
	        		{
	        			if(in_array($val,$default_fields))
	        			{
	        				$meta_vl='';
	        				if($val=='email')
	        				{
	        					$meta_vl=($wc_version==0 ? $order->billing_email : $order->get_billing_email());
	        				}elseif($val=='contact_number')
	        				{
	        					$meta_vl=($wc_version==0 ? $order->billing_phone : $order->get_billing_phone());
	        				}elseif($val=='vat')
	        				{
	        					$meta_vl=($wc_version==0 ? $order->billing_vat : get_post_meta($order_id,'_billing_vat',true));
	        				}elseif($val=='ssn')
	        				{
	        					$meta_vl=($wc_version==0 ? $order->billing_ssn : get_post_meta($order_id,'_billing_ssn',true));
	        				}elseif($val=='cus_note')
	        				{
	        					$meta_vl=($wc_version==0 ? $order->customer_note : $order->get_customer_note());
	        				}
	        				$extra_fields[$val]=$meta_vl;
	        			}else
	        			{
	        				//check meta key exists, and user created field exists
	         				if(isset($user_created_fields[$val]))
	        				{
	        					$label=$user_created_fields[$val];
	        					if(get_post_meta($order_id,'_billing_'.$val,true))
								{
									$extra_fields[$label]=get_post_meta($order_id,'_billing_'.$val,true);
								}
								if(get_post_meta($order_id,$val,true))
								{
									$extra_fields[$label]=get_post_meta($order_id,$val,true);
								}elseif(get_post_meta($order_id,'_'.$val,true))
								{
									$extra_fields[$label]=get_post_meta($order_id,'_'.$val,true);
								}
	        				}
	        			}       			
	        		}
	        	}

	        	//filter to alter extra fields
	        	$extra_fields=apply_filters('wf_pklist_alter_additional_fields',$extra_fields,$template_type,$order);
	        	
	        	$find_replace['[wfte_vat_number]']=isset($extra_fields['vat']) ? $extra_fields['vat'] : '';
	        	$find_replace['[wfte_ssn_number]']=isset($extra_fields['ssn']) ? $extra_fields['ssn'] : '';
	        	$find_replace['[wfte_email]']=isset($extra_fields['email']) ? $extra_fields['email'] : '';
	        	$find_replace['[wfte_tel]']=isset($extra_fields['contact_number']) ? $extra_fields['contact_number'] : '';

	        	$default_fields_placeholder=array(
	        		'vat'=>'vat_number',
	        		'ssn'=>'ssn_number',
	        		'contact_number'=>'tel',
	        	);

	        	//extra fields
	        	$ex_html='';
	        	if(is_array($extra_fields))
	        	{
		        	foreach($extra_fields as $ex_key=>$ex_vl)
		        	{
		        		if(!in_array($ex_key,$default_fields)) //not default fields like vat,ssn
	        			{
	        				if(is_string($ex_vl) && trim($ex_vl)!="")
	        				{
	        					$ex_html.='<div class="wfte_extra_fields">
						            <span>'.__(ucfirst($ex_key), 'wf-woocommerce-packing-list').':</span>
						            <span>'.__($ex_vl,'wf-woocommerce-packing-list').'</span>
						          </div>';
	        				}
		        		}else 
		        		{
		        			$placeholder_key=isset($default_fields_placeholder[$ex_key]) ? $default_fields_placeholder[$ex_key] : $ex_key;
		        			$placeholder='[wfte_'.$placeholder_key.']';
		        			if(strpos($html,$placeholder)===false) //default fields that have no placeholder
		        			{
		        				if(trim($ex_vl)!="")
		        				{
		        					$ex_html.='<div class="wfte_extra_fields">
							            <span>'.__($default_fields_label[$ex_key], 'wf-woocommerce-packing-list').':</span>
							            <span>'.__($ex_vl,'wf-woocommerce-packing-list').'</span>
							          </div>';
		        				}
		        			}
		        		}
		        	}
	        	}
	        	$find_replace['[wfte_extra_fields]']=$ex_html;
	        }

	        if(!isset($find_replace['[wfte_order_item_meta]'])) /* check already added */
			{
	        	$order_item_meta_data='';
	        	$order_item_meta_data=apply_filters('wf_pklist_order_additional_item_meta', $order_item_meta_data, $template_type, $order);
	        	$find_replace['[wfte_order_item_meta]']=$order_item_meta_data;
	        }
		}
		return $find_replace;
	}
	public static function set_logo($find_replace, $template_type)
	{
		//module settings are saved under module id
		$module_id=Wf_Woocommerce_Packing_List::get_module_id($template_type);

		$the_options=Wf_Woocommerce_Packing_List::get_settings($module_id);
		$the_options_main=Wf_Woocommerce_Packing_List::get_settings();
		$find_replace['[wfte_company_logo_url]']='';
		if(isset($the_options['woocommerce_wf_packinglist_logo']) && $the_options['woocommerce_wf_packinglist_logo']!="")
		{
			$find_replace['[wfte_company_logo_url]']=$the_options['woocommerce_wf_packinglist_logo'];
		}else
		{ 
			if($the_options_main['woocommerce_wf_packinglist_logo']!="")
			{
				$find_replace['[wfte_company_logo_url]']=$the_options_main['woocommerce_wf_packinglist_logo'];
			}				
		}
		$find_replace['[wfte_company_name]']=$the_options_main['woocommerce_wf_packinglist_companyname'];
		return $find_replace;
	}

	/**
	*  	Get variation data, meta data
	*	@since 4.0.0
	*	@since 4.0.2 [Bug fix] Showing meta data key instead of meta data label
	*	@since 4.0.4 Added compatiblity to handle meta with empty keys and duplicate meta keys
	*/
	public static function get_order_line_item_variation_data($order_item,$item_id,$_product,$order, $template_type)
	{  
        if (WC()->version > '2.7.0') {
            global $wpdb;
            $meta_value_data = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value, meta_id, order_item_id
        FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = %d
        ORDER BY meta_id", absint($item_id)), ARRAY_A);

        }

        $variation = '';
        $meta_data = array();
        if($metadata = ((WC()->version < '2.7.0') ? $order->has_meta($item_id) : $meta_value_data)) 
        {
	        
	        foreach ($metadata as $meta) 
            { 
                // Skip hidden core fields
                if (in_array($meta['meta_key'], array(
                            '_qty',
                            '_reduced_stock',
                            '_tax_class',
                            '_product_id',
                            '_variation_id',
                            '_line_subtotal',
                            '_line_subtotal_tax',
                            '_line_total',
                            '_line_tax',
                            'method_id',
                            'cost',
                            '_refunded_item_id',
                        ))) {
                    continue;
                }
                
                // Skip serialised meta
                if (is_serialized($meta['meta_value'])) {
                    continue;
                }

                // Get attribute data
                if (taxonomy_exists(wc_sanitize_taxonomy_name($meta['meta_key']))) 
                {
                    $term = get_term_by('slug', $meta['meta_value'], wc_sanitize_taxonomy_name($meta['meta_key']));
                    $meta_key = wc_attribute_label(wc_sanitize_taxonomy_name($meta['meta_key']));
                    $meta_value = isset($term->name) ? $term->name : $meta['meta_value'];
                    $meta_data[$meta_key] = $meta_value;
                }else
                {
                	$meta_data[$meta['meta_key']] = $meta['meta_value'];
                    $meta_data[$meta['meta_key']] = apply_filters('wf_pklist_alter_meta_value',$meta['meta_value'],$meta_data, $meta['meta_key']);
                }
            }
            
            // [Bug fix] Showing meta data key instead of meta data label
            if(method_exists($order_item,'get_formatted_meta_data'))
            {
                foreach($order_item->get_formatted_meta_data() as $meta_id=>$meta)
                {
					if(!isset($meta_data[$meta->display_key]))
                    {
                        if(isset($meta_data[$meta->key]))
                        {
							$val_backup=$meta_data[$meta->key];
                            unset($meta_data[$meta->key]);
                            $meta_data[$meta->display_key]=$val_backup;
                        }else
                        {
							if($meta->display_key=="")
							{
								$meta_data[]=strip_tags($meta->display_value);
							}else
							{
								$meta_data[$meta->display_key]=trim(strip_tags($meta->display_value));
							}
                        }
                    }else
					{
						if(html_entity_decode(trim(strip_tags($meta_data[$meta->display_key])))!=html_entity_decode(trim(strip_tags($meta->display_value)))) /* same key but value different */
						{							
							$meta_data[]=array($meta->display_key, trim(strip_tags($meta->display_value)));
						}
					}
                }
            }

            $meta_data = apply_filters('wf_pklist_modify_meta_data', $meta_data);
            $variation='';
            foreach ($meta_data as $id => $value) 
            {
                if($value != '')
                {
					if(intval($id)===$id) //numeric array
					{
						if(is_array($value))
						{
							$current_item=wp_kses_post(rawurldecode($value[0])) . ' : ' . wp_kses_post(rawurldecode($value[1])) . ' ';
						}else
						{
							$current_item=wp_kses_post(rawurldecode($value)) . ' ';
						}
					}else
					{
						$current_item= wp_kses_post(rawurldecode($id)) . ' : ' . wp_kses_post(rawurldecode($value)) . ' ';
					}              	
                	$variation.= apply_filters('wf_alter_line_item_variation_data', $current_item, $meta_data, $id, $value);
                }
            }
        }
        return $variation;
    }


    private static function wf_is_multi($array)
    {
	    $multi_check = array_filter($array,'is_array');
	    if(count($multi_check)>0) return true;
	    return false;
    }

    /**
    *	Convert number to words
    *	@author hunkriyaz <Github>
    *	@since 4.0.2
    *
    */
    public static function convert_number_to_words($number)
    {
	    $hyphen      = '-';
	    $conjunction = ' and ';
	    $separator   = ', ';
	    $negative    = 'negative ';
	    $decimal     = ' point ';
	    $dictionary  = array(
	        0                   => 'zero',
	        1                   => 'one',
	        2                   => 'two',
	        3                   => 'three',
	        4                   => 'four',
	        5                   => 'five',
	        6                   => 'six',
	        7                   => 'seven',
	        8                   => 'eight',
	        9                   => 'nine',
	        10                  => 'ten',
	        11                  => 'eleven',
	        12                  => 'twelve',
	        13                  => 'thirteen',
	        14                  => 'fourteen',
	        15                  => 'fifteen',
	        16                  => 'sixteen',
	        17                  => 'seventeen',
	        18                  => 'eighteen',
	        19                  => 'nineteen',
	        20                  => 'twenty',
	        30                  => 'thirty',
	        40                  => 'fourty',
	        50                  => 'fifty',
	        60                  => 'sixty',
	        70                  => 'seventy',
	        80                  => 'eighty',
	        90                  => 'ninety',
	        100                 => 'hundred',
	        1000                => 'thousand',
	        1000000             => 'million',
	        1000000000          => 'billion',
	        1000000000000       => 'trillion',
	        1000000000000000    => 'quadrillion',
	        1000000000000000000 => 'quintillion'
	    );
	    if (!is_numeric($number)) {
	        return false;
	    }
	    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
	        // overflow
	        /* 
	        trigger_error(
	            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
	            E_USER_WARNING
	        ); */
	        return false;
	    }
	    if ($number < 0) {
	        return $negative . self::convert_number_to_words(abs($number));
	    }
	    $string = $fraction = null;
	    if (strpos($number, '.') !== false) {
	        list($number, $fraction) = explode('.', $number);
	    }
	    switch (true) {
	        case $number < 21:
	            $string = $dictionary[$number];
	            break;
	        case $number < 100:
	            $tens   = ((int) ($number / 10)) * 10;
	            $units  = $number % 10;
	            $string = $dictionary[$tens];
	            if ($units) {
	                $string .= $hyphen . $dictionary[$units];
	            }
	            break;
	        case $number < 1000:
	            $hundreds  = $number / 100;
	            $remainder = $number % 100;
	            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
	            if ($remainder) {
	                $string .= $conjunction . self::convert_number_to_words($remainder);
	            }
	            break;
	        default:
	            $baseUnit = pow(1000, floor(log($number, 1000)));
	            $numBaseUnits = (int) ($number / $baseUnit);
	            $remainder = $number % $baseUnit;
	            $string = self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
	            if ($remainder) {
	                $string .= $remainder < 100 ? $conjunction : $separator;
	                $string .= self::convert_number_to_words($remainder);
	            }
	            break;
	    }
	    if (null !== $fraction && is_numeric($fraction)) {
	        $string .= $decimal;
	        $words = array();
	        foreach (str_split((string) $fraction) as $number) {
	            $words[] = $dictionary[$number];
	        }
	        $string .= implode(' ', $words);
	    }
	    return $string;
	} 

	/**
	*	@since 4.0.9	
	*	Add values to placeholders that are not available in the doc type module
	*/
	public static function add_missing_placeholders($find_replace, $template_type, $html, $order)
	{
		/**
		*	Handle all product table price section shortcodes
		*/
		$find_replace=self::set_extra_charge_fields($find_replace, $template_type, $html, $order);

		/**
		*	Handle all other infos, Including footer, return policy, total weight, printed on etc
		*/
		$find_replace=self::set_other_data($find_replace, $template_type, $html, $order);


		/**
		*	Handle order datas, Order meta, Shipping method, Tracking number etc 
		*/
		$find_replace=self::set_extra_fields($find_replace, $template_type, $html, $order);

		return $find_replace;
	}

	/**
	*	@since 4.0.9	
	*	Get tax inclusive text.
	*/
	public static function get_tax_incl_text($template_type, $order, $text_for='total_price')
	{
		$incl_tax_text=__('incl. tax', 'wf-woocommerce-packing-list');
	    return apply_filters('wf_pklist_alter_tax_inclusive_text', $incl_tax_text, $template_type, $order, $text_for);
	}

    /**
    *	Hide the empty placeholders in the template HTML
    *	@since 4.0.0
    *	@since 4.0.2	added wfte_weight in defult hide list
    */
    public static function hide_empty_elements($find_replace,$html,$template_type)
    {
    	$hide_on_empty_fields=array('wfte_vat_number','wfte_ssn_number','wfte_email','wfte_tel','wfte_shipping_method','wfte_tracking_number','wfte_footer','wfte_return_policy',
    		'wfte_product_table_coupon',
			'wfte_product_table_fee',
			'wfte_product_table_total_tax',
			'wfte_product_table_order_discount',
			'wfte_product_table_cart_discount',
			'wfte_product_table_shipping',
			'wfte_order_item_meta',
			'wfte_weight',
			'wfte_total_in_words',
			'wfte_signature',
		);
		$hide_on_empty_fields=apply_filters('wf_pklist_alter_hide_empty',$hide_on_empty_fields,$template_type);
    	foreach ($hide_on_empty_fields as $key => $value)
    	{
    		if(isset($find_replace['['.$value.']']))
	    	{
	    		if($find_replace['['.$value.']']=="")
	    		{
	    			$html=self::addClass($value, $html,self::TO_HIDE_CSS);
	    		}
	    	}else
	    	{
	    		$find_replace['['.$value.']']='';
	    		$html=self::addClass($value,$html,self::TO_HIDE_CSS);
	    	}
    	}

    	$html=apply_filters('wf_pklist_alter_html_after_hide_empty', $html, $hide_on_empty_fields, $template_type);
    	
    	return $html;
    }
    public static function getElmByClass($elm_class,$html)
    {
    	$matches=array();
    	$re = '/<[^>]*class\s*=\s*["\'](.*?[^"\']*)'.$elm_class.'(.*?[^"\']*)["\'][^>]*>/m';
		if(preg_match($re,$html,$matches))
		{
		  return $matches;
		}else
		{
			return false;
		}
    }
    private static function filterCssClasses($class)
    {
    	$class_arr=explode(" ",$class);
    	return array_unique(array_filter($class_arr));
    }
	private static function removeClass($elm_class,$html,$remove_class)
    {
    	$match=self::getElmByClass($elm_class,$html);
    	if($match) //found
    	{
    		$elm_class=$match[1].$elm_class.$match[2];
    		$new_class_arr=self::filterCssClasses($elm_class);
			foreach(array_keys($new_class_arr,$remove_class) as $key) {
			    unset($new_class_arr[$key]);
			}
			$new_class=implode(" ",$new_class_arr);
    		return str_replace($elm_class,$new_class,$html);
    	}
    	return $html;
    }

    /**
    *	Add class to element
    *	@since 4.0.0
    *	@param	string $elm_class CSS class to select
    *	@param  string $html HTML to search
    *	@param 	string $new_class new CSS class to add
    */
    public static function addClass($elm_class,$html,$new_class)
    {
    	$match=self::getElmByClass($elm_class,$html);
    	if($match) //found
    	{ 
    		$elm_class=$match[1].$elm_class.$match[2];
    		$new_class_arr=self::filterCssClasses($elm_class.' '.$new_class);
			$new_class=implode(" ",$new_class_arr);
    		return str_replace($elm_class,$new_class,$html);
    	}
    	return $html;
    }

    public static function get_template_html_attr_vl($html,$attr,$default='')
	{
		$match_arr=array();
		$out=$default;
		if(preg_match('/'.$attr.'="(.*?)"/s',$html,$match_arr))
		{
			$out=$match_arr[1];
			$out=($out=='' ? $default : $out);
		}
		return $out;
	}


	private static function dummy_product_row($columns_list_arr)
	{
		$html='';
		$dummy_vals=array(
			'image'=>self::generate_product_image_column_data(0,0,0),
			'product'=>'Jumbing LED Light Wall Ball',
			'sku'=>'A1234',
			'quantity'=>'1',
			'price'=>'$20.00',
			'tax'=>'$2.00',
			'total_price'=>'$100.00',
			'total_weight'=>'2 kg',
		);
		$html='<tr>';
		foreach($columns_list_arr as $columns_key=>$columns_value)
		{
			$is_hidden=($columns_key[0]=='-' ? 1 : 0); //column not enabled
			$column_id=($is_hidden==1 ? substr($columns_key,1) : $columns_key);
			$hide_it=($is_hidden==1 ? self::TO_HIDE_CSS : ''); //column not enabled
			$extra_col_options=$columns_list_arr[$columns_key];
			$td_class=$columns_key.'_td';
			$html.='<td class="'.$hide_it.' '.$td_class.' '.$extra_col_options.'">';
			$html.=isset($dummy_vals[$column_id]) ? $dummy_vals[$column_id] : '';
			$html.='</td>';
		}
		$html.='</tr>';
		return $html;
	}

	/* 
	* Add dummy data for customizer design view
	* @return array
	*/
	public static function dummy_data_for_customize($find_replace,$template_type,$html)
	{
		$find_replace['[wfte_invoice_number]']=123456;
		$find_replace['[wfte_order_number]']=123456;

		$order_date_format=self::get_template_html_attr_vl($html,'data-order_date-format','m/d/Y');
		$find_replace['[wfte_order_date]']=date($order_date_format);

		$invoice_date_format=self::get_template_html_attr_vl($html,'data-invoice_date-format','m/d/Y');
		$find_replace['[wfte_invoice_date]']=date($invoice_date_format);

		$dispatch_date_format=self::get_template_html_attr_vl($html,'data-dispatch_date-format','m/d/Y');
		$find_replace['[wfte_dispatch_date]']=date($dispatch_date_format);
		
		//Dummy billing addresss
		$find_replace['[wfte_billing_address]']='Webtoffee <br>20 Maple Avenue <br>San Pedro <br>California <br>United States (US) <br>90731 <br>';
		
		//Dummy shipping addresss
		$find_replace['[wfte_shipping_address]']='Webtoffee <br>20 Maple Avenue <br>San Pedro <br>California <br>United States (US) <br>90731 <br>';
		
		$find_replace['[wfte_vat_number]']='123456';
    	$find_replace['[wfte_ssn_number]']='SSN123456';
    	$find_replace['[wfte_email]']='info@example.com';
    	$find_replace['[wfte_tel]']='+1 123 456';
    	$find_replace['[wfte_shipping_method]']='DHL';
    	$find_replace['[wfte_tracking_number]']='123456';
    	$find_replace['[wfte_order_item_meta]']='';
    	$find_replace['[wfte_extra_fields]']='';
		$find_replace['[wfte_product_table_subtotal]']='$100.00';
		$find_replace['[wfte_product_table_shipping]']='$0.00';
		$find_replace['[wfte_product_table_cart_discount]']='$0.00';
		$find_replace['[wfte_product_table_order_discount]']='$0.00';
		$find_replace['[wfte_product_table_total_tax]']='$0.00';
		$find_replace['[wfte_product_table_fee]']='$0.00';
		$find_replace['[wfte_product_table_payment_method]']='PayPal';
		$find_replace['[wfte_product_table_payment_total]']='$100.00';
		$find_replace['[wfte_product_table_coupon]']='{ABCD100}';
		$find_replace['[wfte_barcode_url]']='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEYAAAAeAQMAAACrPfpdAAAABlBMVEX///8AAABVwtN+AAAAAXRSTlMAQObYZgAAABdJREFUGJVj+MzDfPg8P/NnG4ZRFgEWAHrncvdCJcw9AAAAAElFTkSuQmCC';
		
		$find_replace['[wfte_return_policy]']='Mauris dignissim neque ut sapien vulputate, eu semper tellus porttitor. Cras porta lectus id augue interdum egestas. Suspendisse potenti. Phasellus mollis porttitor enim sit amet fringilla. Nulla sed ligula venenatis, rutrum lectus vel';
		$find_replace['[wfte_footer]']='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc nec vehicula purus. Mauris tempor nec ipsum ac tempus. Aenean vehicula porttitor tortor, et interdum tellus fermentum at. Fusce pellentesque justo rhoncus';
		$find_replace['[wfte_special_notes]']='Special notes: consectetur adipiscing elit. Nunc nec vehicula purus. ';
		$find_replace['[wfte_transport_terms]']='Transport Terms: Nunc nec vehicula purus. Mauris tempor nec ipsum ac tempus.';
		$find_replace['[wfte_sale_terms]']='Sale terms: et interdum tellus fermentum at. Fusce pellentesque justo rhoncus';
		//on package type documents
		$find_replace['[wfte_box_name]']='';
		$find_replace['[wfte_qr_code]']='';
		$find_replace['[wfte_total_in_words]']=self::convert_number_to_words(100);
		$find_replace['[wfte_printed_on]']=self::get_printed_on_date($html);

		$find_replace=apply_filters('wf_pklist_alter_dummy_data_for_customize',$find_replace,$template_type,$html);

		$tax_items_match=array();
		if(preg_match('/<[^>]*data-row-type\s*=\s*"[^"]*\bwfte_tax_items\b[^"]*"[^>]*>(.*?)<\/tr>/s',$html,$tax_items_match))
		{
			$find_replace[$tax_items_match[0]]='';
		}
		return $find_replace;
	}
}
