<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.webtoffee.com/
 * @since      2.5.0
 *
 * @package    Wf_Woocommerce_Packing_List
 * @subpackage Wf_Woocommerce_Packing_List/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wf_Woocommerce_Packing_List
 * @subpackage Wf_Woocommerce_Packing_List/admin
 * @author     WebToffee <info@webtoffee.com>
 */
class Wf_Woocommerce_Packing_List_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.5.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.5.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/*
	 * module list, Module folder and main file must be same as that of module name
	 * Please check the `register_modules` method for more details
	 */
	public static $modules=array(
		'customizer',
		'uninstall-feedback',
	);

	public static $existing_modules=array();

	public $bulk_actions=array();

	public static $tooltip_arr=array();

	/**
	*	To store the RTL needed or not status
	*	@since 2.6.6
	*/
	public static $is_enable_rtl=null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.5.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.5.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wf-woocommerce-packing-list-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.5.0
	 */
	public function enqueue_scripts() 
	{
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wf-woocommerce-packing-list-admin.js', array( 'jquery','wp-color-picker','jquery-tiptip'), $this->version, false );
		//order list page bulk action filter
		$this->bulk_actions=apply_filters('wt_print_bulk_actions',$this->bulk_actions);

		$params=array(
			'nonces' => array(
		            'wf_packlist' => wp_create_nonce(WF_PKLIST_PLUGIN_NAME),
		     ),
			'ajaxurl' => admin_url('admin-ajax.php'),
			'no_image'=>Wf_Woocommerce_Packing_List::$no_image,
			'bulk_actions'=>array_keys($this->bulk_actions),
			'print_action_url'=>admin_url('?print_packinglist=true'),
			'msgs'=>array(
				'settings_success'=>__('Settings updated.','print-invoices-packing-slip-labels-for-woocommerce'),
				'all_fields_mandatory'=>__('All fields are mandatory','print-invoices-packing-slip-labels-for-woocommerce'),
				'settings_error'=>__('Unable to update Settings.','print-invoices-packing-slip-labels-for-woocommerce'),
				'select_orders_first'=>__('You have to select order(s) first!','print-invoices-packing-slip-labels-for-woocommerce'),
				'invoice_not_gen_bulk'=>__('One or more order do not have invoice generated. Generate manually?','print-invoices-packing-slip-labels-for-woocommerce'),
				'error'=>__('Error','print-invoices-packing-slip-labels-for-woocommerce'),
				'please_wait'=>__('Please wait','print-invoices-packing-slip-labels-for-woocommerce'),
			)
		);
		wp_localize_script($this->plugin_name, 'wf_pklist_params', $params);

	}


	/**
    * 	@since 2.5.8
    * 	Set tooltip for form fields 
    */
    public static function set_tooltip($key,$base_id="",$custom_css="")
    {
    	$tooltip_text=self::get_tooltips($key,$base_id);
    	if($tooltip_text!="")
    	{
    		$tooltip_text='<span style="color:#aaa; '.($custom_css!="" ? $custom_css : 'margin-top:-1px; margin-left:2px; position:absolute;').'" class="dashicons dashicons-editor-help wt-tips" data-wt-tip="'.$tooltip_text.'"></span>';
    	}
    	return $tooltip_text;
    }

    /**
    * 	@since 2.5.8
    * 	Get tooltip config data for non form field items
    * 	@return array 'class': class name to enable tooltip, 'text': tooltip text including data attribute if not empty
    */
    public static function get_tooltip_configs($key,$base_id="")
    {
    	$out=array('class'=>'','text'=>'');
    	$text=self::get_tooltips($key,$base_id);
    	if($text!="")
    	{
    		$out['text']=' data-wt-tip="'.$text.'"';
    		$out['class']=' wt-tips';
    	}  	
    	return $out;
    }

    /**
    *	@since 2.5.8
	* 	This function will take tooltip data from modules and store ot 
	*
	*/
	public function register_tooltips()
	{
		include(plugin_dir_path( __FILE__ ).'data/data.tooltip.php');
		self::$tooltip_arr=array(
			'main'=>$arr
		);
		/* hook for modules to register tooltip */
		self::$tooltip_arr=apply_filters('wt_pklist_alter_tooltip_data',self::$tooltip_arr);
	}

	/**
	* 	Get tooltips
	*	@since 2.5.8
	*	@param string $key array key for tooltip item
	*	@param string $base module base id
	* 	@return tooltip content, empty string if not found
	*/
	public static function get_tooltips($key,$base_id='')
	{
		$arr=($base_id!="" && isset(self::$tooltip_arr[$base_id]) ? self::$tooltip_arr[$base_id] : self::$tooltip_arr['main']);
		return (isset($arr[$key]) ? $arr[$key] : '');
	}

	/**
	 * Function to add Items to Orders Bulk action dropdown
	 *
	 * @since    2.5.0
	 */
	public function alter_bulk_action($actions)
	{
        return array_merge($actions,$this->bulk_actions);
	}
	

	/**
	 * Function to add print button in order list page action column
	 *
	 * @since    2.5.0
	 */
	public function add_checkout_fields($fields) 
	{
		$additional_options=Wf_Woocommerce_Packing_List::get_option('wf_invoice_additional_checkout_data_fields');
        if(is_array($additional_options) && count(array_filter($additional_options))>0)
        {
            foreach ($additional_options as $value)
            {
                $fields['billing']['billing_' . $value] = array(
                    'text' => 'text',
                    'label' => __(str_replace('_', ' ', $value), 'woocommerce'),
                    'placeholder' => _x('Enter ' . str_replace('_', ' ', $value), 'placeholder','woocommerce'),
                    'required' => false,
                    'class' => array('form-row-wide', 'align-left'),
                    'clear' => true
                );
            }
        }
		return $fields;
	}

	/**
	 * Function to add print button in order list page action column
	 *
	 * @since    2.5.0
	 */
	public function add_print_action_button($actions,$order)
	{
        $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
        $wf_pklist_print_options=array(
            array(
                'name' => '',
                'action' => 'wf_pklist_print_document',
                'url' => sprintf('#%s',$order_id)
            ),
        );
        return array_merge($actions,$wf_pklist_print_options);
    } 

    /**
	 * Function to add email attachments to order email
	 *
	 * @since    2.5.0
	 */
	public function add_email_attachments($attachments, $status=null, $order=null)
	{
		if(is_object($order) && is_a($order,'WC_Order') && isset($status))
		{
            $order=( WC()->version < '2.7.0' ) ? new WC_Order($order) : new wf_order($order);
			$order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
			$attachments=apply_filters('wt_email_attachments',$attachments,$order,$order_id,$status);
        }
		return $attachments;	
	}
   
    /**
	 * Function to add action buttons in order email
	 *
	 * 	@since    2.5.0
	 *	@since 	  2.6.5 	[Bug fix] Print button missing in email 
	 */
	public function add_email_print_actions($order)
	{
		if(is_object($order) && is_a($order,'WC_Order'))
		{
			$order=( WC()->version < '2.7.0' ) ? new WC_Order($order) : new wf_order($order);
			$order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
			$html='';
			$html=apply_filters('wt_email_print_actions',$html,$order,$order_id);	
		}
	}

    /**
	 * Function to add action buttons in user dashboard order list page
	 *
	 * @since    2.5.0
	 */
	public function add_fontend_print_actions($order)
	{
		$order=( WC()->version < '2.7.0' ) ? new WC_Order($order) : new wf_order($order);
		$order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
		$html='';
		$html=apply_filters('wt_frontend_print_actions',$html,$order,$order_id);	
	}

	public static function get_print_url($order_id, $action)
	{
		$url=wp_nonce_url(admin_url('?print_packinglist=true&post='.($order_id).'&type='.$action), WF_PKLIST_PLUGIN_NAME);
		$url=(isset($_GET['debug']) ? $url.'&debug' : $url);
		return $url;
	}

	public static function generate_print_button_data($order,$order_id,$action,$label,$icon_url,$is_show_prompt,$button_location="detail_page")
	{
		$url=self::get_print_url($order_id, $action);
		
		$href_attr='';
		$onclick='';
		$confirmation_clss='';
		if($is_show_prompt==1)
		{
			$confirmation_clss='wf_pklist_confirm_'.$action;
			$onclick='onclick=" return wf_Confirm_Notice_for_Manually_Creating_Invoicenumbers(\''.$url.'\','.$is_show_prompt.');"';
		}else
		{
			$href_attr=' href="'.$url.'"';
		}
		if($button_location=="detail_page")
        {
        ?>
		<tr>
			<td>
				<a class="button tips wf-packing-list-link" <?php echo $onclick;?> <?php echo $href_attr;?> target="_blank" data-tip="<?php echo strip_tags($label);?>" >
				<?php
				if($icon_url!="")
				{
				?>
					<img src="<?php echo $icon_url;?>" alt="<?php echo $label;?>" width="14"> 
				<?php
				}
				?>
				<?php echo $label;?>
				</a>
			</td>
		</tr>
		<?php
        }elseif($button_location=="list_page")
        {
        ?>
			<li>
				<a class="<?php echo $confirmation_clss;?>" data-id="<?php echo $order_id;?>" <?php echo $onclick;?> <?php echo $href_attr;?> target="_blank"><?php echo $label;?></a>
			</li>
		<?php
        }
	}

	/**
	 * Function to add action buttons in order list page
	 *
	 * @since    2.5.0
	 */
	public function add_print_actions($column)
	{
		global $post, $woocommerce, $the_order;
		if($column=='order_actions' || $column=='wc_actions')
		{
			$order = ( WC()->version < '2.7.0' ) ? new WC_Order($post->ID) : new wf_order($post->ID);
            $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
			$html='';
			?>
			<div id="wf_pklist_print_document-<?php echo $order_id;?>" class="wf-pklist-print-tooltip-order-actions">				
				<div class="wf-pklist-print-tooltip-content">
                    <ul>
                    <?php
					$html=apply_filters('wt_print_actions',$html,$order,$order_id);
					?>
					</ul>
                </div>
                <div class="wf_arrow"></div>	
			</div>
			<?php
		}
		return $column;
	}

	/**
	 * Registers meta box and printing options
	 *
	 * @since    2.5.0
	 */
	public function add_meta_boxes()
	{
		add_meta_box('woocommerce-packinglist-box', __('Print Actions','print-invoices-packing-slip-labels-for-woocommerce'), array($this,'create_metabox_content'),'shop_order', 'side', 'default');
	}

	/**
	 * Add plugin action links
	 *
	 * @param array $links links array
	 */
	public function plugin_action_links($links) 
	{
	   $links[] = '<a href="'.admin_url('admin.php?page='.WF_PKLIST_POST_TYPE).'">'.__('Settings', 'print-invoices-packing-slip-labels-for-woocommerce').'</a>';
	   $links[] = '<a href="https://www.webtoffee.com/product/woocommerce-pdf-invoices-packing-slips/" target="_blank">'.__('Upgrade to premium','print-invoices-packing-slip-labels-for-woocommerce').'</a>';
	   $links[] = '<a href="https://wordpress.org/support/plugin/print-invoices-packing-slip-labels-for-woocommerce" target="_blank">'.__('Support','print-invoices-packing-slip-labels-for-woocommerce').'</a>';
	   $links[] = '<a href="https://wordpress.org/support/plugin/print-invoices-packing-slip-labels-for-woocommerce/reviews/?rate=5#new-post" target="_blank">' . __('Review','print-invoices-packing-slip-labels-for-woocommerce') . '</a>';
	   return $links;
	}


	/**
	 * create content for metabox
	 *
	 * @since    2.5.0
	 */
	public function create_metabox_content()
	{
		global $post;
        $order = ( WC()->version < '2.7.0' ) ? new WC_Order($post->ID) : new wf_order($post->ID);
        $order_id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
        $html='';
		?>
		<table class="wf_invoice_metabox">
			<?php
			$html=apply_filters('wt_print_metabox',$html,$order,$order_id);
			?>
		</table>
		<?php
	}


	/**
	 * Registers menu options
	 * Hooked into admin_menu
	 *
	 * @since    2.5.0
	 */
	public function admin_menu()
	{
		$menus=array(
			array(
				'menu',
				__('General Settings','print-invoices-packing-slip-labels-for-woocommerce'),
				__('Invoice/Packing','print-invoices-packing-slip-labels-for-woocommerce'),
				'manage_woocommerce',
				WF_PKLIST_POST_TYPE,
				array($this,'admin_settings_page'),
				'dashicons-media-text',
				56
			)
		);

		$menus=apply_filters('wt_admin_menu',$menus);

		$menus[]=array(
			'submenu',
			WF_PKLIST_POST_TYPE,
			__('Other Documents','print-invoices-packing-slip-labels-for-woocommerce'),
			__('Other Documents','print-invoices-packing-slip-labels-for-woocommerce'),
			'manage_woocommerce',
			WF_PKLIST_POST_TYPE.'_document_settings_page',
			array($this,'admin_document_settings_page')
		);

		if(count($menus)>0)
		{
			add_submenu_page(WF_PKLIST_POST_TYPE,__('General Settings','print-invoices-packing-slip-labels-for-woocommerce'),__('General Settings','print-invoices-packing-slip-labels-for-woocommerce'), "manage_woocommerce",WF_PKLIST_POST_TYPE,array($this,'admin_settings_page'));
			foreach($menus as $menu)
			{
				if($menu[0]=='submenu')
				{
					add_submenu_page($menu[1],$menu[2],$menu[3],$menu[4],$menu[5],$menu[6]);
				}else
				{
					add_menu_page($menu[1],$menu[2],$menu[3],$menu[4],$menu[5],$menu[6],$menu[7]);	
				}
			}
		}

		if(function_exists('remove_submenu_page')){
			//remove_submenu_page(WF_PKLIST_POST_TYPE,WF_PKLIST_POST_TYPE);
		}
	}

	/**
	* @since 2.5.9
	* Is allowed to print
	*/
	public static function check_role_access()
	{
		$admin_print_role_access=array('manage_options', 'manage_woocommerce');
    	$admin_print_role_access=apply_filters('wf_pklist_alter_admin_print_role_access', $admin_print_role_access);  
    	$admin_print_role_access=(!is_array($admin_print_role_access) ? array() : $admin_print_role_access);
    	$is_allowed=false;
    	foreach($admin_print_role_access as $role) //checking access
    	{
    		if(current_user_can($role)) //any of the role is okay then allow to print
    		{
    			$is_allowed=true;
    			break;
    		}
    	}
    	return $is_allowed;
	}

	/**
	 * function to render printing window
	 *
	 */
    public function print_window() 
    {       
        $attachments = array();
        if(isset($_GET['print_packinglist'])) 
        {
        	//checkes user is logged in
        	if(!is_user_logged_in())
        	{
        		auth_redirect();
        	}
        	$not_allowed_msg=__('You are not allowed to view this page.','print-invoices-packing-slip-labels-for-woocommerce');
        	$not_allowed_title=__('Access denied !!!.','print-invoices-packing-slip-labels-for-woocommerce');

            $client = false;
            //	to check current user has rights to get invoice and packing list
            if(!isset($_GET['attaching_pdf']))
            {
	            $nonce=isset($_GET['_wpnonce']) ? sanitize_text_field($_GET['_wpnonce']) : ''; 
	            if(!(wp_verify_nonce($nonce,WF_PKLIST_PLUGIN_NAME)))
	            {
	                wp_die($not_allowed_msg,$not_allowed_title);
	            }else
	            {
	            	if(!$this->check_role_access()) //Check access
	                {
	                	wp_die($not_allowed_msg,$not_allowed_title);
	                }
	            	$orders = explode(',', sanitize_text_field($_GET['post']));
	            }
        	}else 
        	{
        		// to get the orders number
	            if(isset($_GET['email']) && isset($_GET['post']) && isset($_GET['user_print']))
	            {
	                $email_data_get =Wf_Woocommerce_Packing_List::wf_decode(sanitize_text_field($_GET['email']));
	                $order_data_get =Wf_Woocommerce_Packing_List::wf_decode(sanitize_text_field($_GET['post']));
	                $order_data = wc_get_order($order_data_get);
	                if(!$order_data)
	                {
	                	wp_die($not_allowed_msg,$not_allowed_title);
	                }
	                $logged_in_userid=get_current_user_id();
	                $order_user_id=((WC()->version < '2.7.0') ? $order_data->user_id : $order_data->get_user_id());
	                if($logged_in_userid!=$order_user_id) //the current order not belongs to the current logged in user
	                { 
	  	             	if(!$this->check_role_access()) //Check access
	                	{
	                		wp_die($not_allowed_msg,$not_allowed_title);
	                	}
	                }

	                //checks the email parameters belongs to the given order
	                if($email_data_get === ((WC()->version < '2.7.0') ? $order_data->billing_email : $order_data->get_billing_email())) 
	                {
	                    $orders=explode(",",$order_data_get); //must be an array
	                }else
	                {
	                    wp_die($not_allowed_msg,$not_allowed_title);
	                }
	            }else
	            {
	            	wp_die($not_allowed_msg,$not_allowed_title);
	            }
        	} 
            $orders=array_values(array_filter($orders));
            $orders=$this->verify_order_ids($orders);
            if(count($orders)>0)
            {
	            remove_action('wp_footer', 'wp_admin_bar_render', 1000);
	            $action = (isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '');
	            //action for modules to hook print function
	            do_action('wt_print_doc', $orders, $action);
	        }
            exit();
        }
    }

    /**
	* Check for valid order ids
	* @since 2.5.4
	* @since 2.5.7 Added compatiblity for `Sequential Order Numbers for WooCommerce`
	*/
    public static function verify_order_ids($order_ids)
    {
    	$out=array();
    	foreach ($order_ids as $order_id)
    	{
    		if(wc_get_order($order_id)===false)
    		{
    			/* compatibility for sequential order number */
    			$order_data=wc_get_orders(
    				array(
    					'limit' => 1,
    					'return' => 'ids',
    					'meta_query'=>array(
    						'key'=>'_order_number',
    						'value'=>$order_id,
    					)
    			));
    			if($order_data!=false && is_array($order_data) && count($order_data)==1)
    			{
    				$order_id=(int) $order_data[0];
    				if($order_id>0 && wc_get_order($order_id)!=false)
    				{
    					$out[]=$order_id;
    				}
    			}
    		}else
    		{
    			$out[]=$order_id;
    		}
    	}
    	return $out;
    }

    public function load_address_from_woo()
    {
    	if(!self::check_write_access()) 
		{
			exit();
		}
    	$out=array(
    		'status'=>1,
    		'address_line1'=>get_option('woocommerce_store_address'),
    		'address_line2'=>get_option('woocommerce_store_address_2'),
    		'city'=>get_option('woocommerce_store_city'),
    		'country'=>get_option('woocommerce_default_country'),
    		'postalcode'=>get_option('woocommerce_store_postcode'),
    	);
    	echo json_encode($out);
    	exit();
    }

	private function dismiss_notice()
	{
		$allowd_items=array('wf_pklist_notice_dissmissed_250');
		if(isset($_GET['wf_pklist_notice_dismiss']) && trim($_GET['wf_pklist_notice_dismiss'])!="")
		{
			if(in_array(sanitize_text_field($_GET['wf_pklist_notice_dismiss']),$allowd_items))
			{
				update_option(sanitize_text_field($_GET['wf_pklist_notice_dismiss']),1);
			}
		}
	}

	/**
	 * Admin document settings page
	 *
	 * @since    2.5.0
	 */
	public function admin_document_settings_page()
	{
		//dismiss the notice if exists
		$this->dismiss_notice();
		include WF_PKLIST_PLUGIN_PATH.'admin/partials/wf-woocommerce-packing-list-admin-document-settings.php';
	}

	/**
	 * Admin settings page
	 *
	 * @since    2.5.0
	 */
	public function admin_settings_page()
	{
		//dismiss the notice if exists
		$this->dismiss_notice();

		$the_options=Wf_Woocommerce_Packing_List::get_settings();
		$no_image=Wf_Woocommerce_Packing_List::$no_image;
		$order_statuses = wc_get_order_statuses();
		$wf_generate_invoice_for=Wf_Woocommerce_Packing_List::get_option('woocommerce_wf_generate_for_orderstatus');
		
		/**
		*	@since 2.6.6
		*	Get available PDF libraries
		*/
		$pdf_libs=Wf_Woocommerce_Packing_List::get_pdf_libraries();

		wp_enqueue_media();
		wp_enqueue_script('wc-enhanced-select');
		wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css');

		/* enable/disable modules */
		if(isset($_POST['wf_update_module_status']))
		{
			// Check nonce:
	        if(!Wf_Woocommerce_Packing_List_Admin::check_write_access()) 
    		{
    			exit();
    		}

		    $wt_pklist_common_modules=get_option('wt_pklist_common_modules');
		    if($wt_pklist_common_modules===false)
		    {
		        $wt_pklist_common_modules=array();
		    }
		    if(isset($_POST['wt_pklist_common_modules']))
		    {
		        $wt_pklist_post=self::sanitize_text_arr($_POST['wt_pklist_common_modules']);
		        foreach($wt_pklist_common_modules as $k=>$v)
		        {
		            if(isset($wt_pklist_post[$k]) && $wt_pklist_post[$k]==1)
		            {
		                $wt_pklist_common_modules[$k]=1;
		            }else
		            {
		                $wt_pklist_common_modules[$k]=0;
		            }
		        }
		    }else
		    {
		    	foreach($wt_pklist_common_modules as $k=>$v)
		        {
					$wt_pklist_common_modules[$k]=0;
		        }
		    }
		    update_option('wt_pklist_common_modules',$wt_pklist_common_modules);
		    wp_redirect($_SERVER['REQUEST_URI']); exit();
		}

		include WF_PKLIST_PLUGIN_PATH.'admin/partials/wf-woocommerce-packing-list-admin-display.php';
	}

	/**
	* @since 2.6.2
	* Is user allowed 
	*/
	public static function check_write_access($nonce_id='')
	{
		$er=true;
		//checkes user is logged in
    	if(!is_user_logged_in())
    	{
    		$er=false;
    	}

    	if($er===true) //no error then proceed
    	{
    		$nonce=sanitize_text_field($_REQUEST['_wpnonce']);
    		$nonce=(is_array($nonce) ? $nonce[0] : $nonce);
    		$nonce_id=($nonce_id=="" ? WF_PKLIST_PLUGIN_NAME : $nonce_id);
    		if(!(wp_verify_nonce($nonce, $nonce_id)))
	        {
	            $er=false;
	        }else
	        {
	        	if(!Wf_Woocommerce_Packing_List_Admin::check_role_access()) //Check access
	            {
	            	$er=false;
	            }
	        }
    	}
    	return $er;
	}

	/**
	* Form action for debug settings tab
	* @since 2.6.7
	*/
	public function debug_save()
	{	
		if(isset($_POST['wt_pklist_admin_modules_btn']))
		{
		    if(!Wf_Woocommerce_Packing_List_Admin::check_write_access()) 
	    	{
	    		return;
	    	}
	        
		    $wt_pklist_common_modules=get_option('wt_pklist_common_modules');
		    if($wt_pklist_common_modules===false)
		    {
		        $wt_pklist_common_modules=array();
		    }
		    if(isset($_POST['wt_pklist_common_modules']))
		    {
		        $wt_pklist_post=self::sanitize_text_arr($_POST['wt_pklist_common_modules']);
		        foreach($wt_pklist_common_modules as $k=>$v)
		        {
		            if(isset($wt_pklist_post[$k]) && $wt_pklist_post[$k]==1)
		            {
		                $wt_pklist_common_modules[$k]=1;
		            }else
		            {
		                $wt_pklist_common_modules[$k]=0;
		            }
		        }
		    }else
		    {
		    	foreach($wt_pklist_common_modules as $k=>$v)
		        {
					$wt_pklist_common_modules[$k]=0;
		        }
		    }

		    $wt_pklist_admin_modules=get_option('wt_pklist_admin_modules');
		    if($wt_pklist_admin_modules===false)
		    {
		        $wt_pklist_admin_modules=array();
		    }
		    if(isset($_POST['wt_pklist_admin_modules']))
		    {
		        $wt_pklist_post=self::sanitize_text_arr($_POST['wt_pklist_admin_modules']);
		        foreach($wt_pklist_admin_modules as $k=>$v)
		        {
		            if(isset($wt_pklist_post[$k]) && $wt_pklist_post[$k]==1)
		            {
		                $wt_pklist_admin_modules[$k]=1;
		            }else
		            {
		                $wt_pklist_admin_modules[$k]=0;
		            }
		        }
		    }else
		    {
		    	foreach($wt_pklist_admin_modules as $k=>$v)
		        {
					$wt_pklist_admin_modules[$k]=0;
		        }
		    }
		    update_option('wt_pklist_admin_modules',$wt_pklist_admin_modules);
		    update_option('wt_pklist_common_modules',$wt_pklist_common_modules);
		    wp_redirect($_SERVER['REQUEST_URI']); exit();
		}

		if(Wf_Woocommerce_Packing_List_Admin::check_role_access()) //Check access
	    {
			//module debug settings saving hook
	    	do_action('wt_pklist_module_save_debug_settings');
	    }
	}

	/**
	*	@since 2.6.2 
	* 	Validate array data
	*/
	public static function sanitize_text_arr($arr, $type='text')
	{
		if(is_array($arr))
		{
			$out=array();
			foreach($arr as $k=>$arrv)
			{
				if(is_array($arrv))
				{
					$out[$k]=self::sanitize_text_arr($arrv, $type);
				}else
				{
					if($type=='int')
					{
						$out[$k]=intval($arrv);
					}else
					{
						$out[$k]=sanitize_text_field($arrv);
					}
				}
			}
			return $out;
		}else
		{
			if($type=='int')
			{
				return intval($arr);
			}else
			{
				return sanitize_text_field($arr);
			}
		}
	}

	/**
	*	@since 2.6.2 
	* 	Settings validation function for modules and plugin settings
	*/
	public function validate_settings_data($val, $key, $validation_rule=array())
	{		
		if(isset($validation_rule[$key]) && is_array($validation_rule[$key])) /* rule declared/exists */
		{
			if(isset($validation_rule[$key]['type']))
			{
				if($validation_rule[$key]['type']=='text')
				{
					$val=sanitize_text_field($val);
				}elseif($validation_rule[$key]['type']=='text_arr')
				{
					$val=self::sanitize_text_arr($val);
				}elseif($validation_rule[$key]['type']=='int')
				{
					$val=intval($val);
				}elseif($validation_rule[$key]['type']=='float')
				{
					$val=floatval($val);
				}elseif($validation_rule[$key]['type']=='int_arr')
				{
					$val=self::sanitize_text_arr($val, 'int');
				}
				elseif($validation_rule[$key]['type']=='textarea')
				{
					$val=sanitize_textarea_field($val);
				}else
				{
					$val=sanitize_text_field($val);
				}
			}
		}else
		{
			$val=sanitize_text_field($val);
		}
		return $val;
	}

	public function validate_box_packing_field($value)
	{           
        $new_boxes = array();
        foreach ($value as $key => $value)
        {
            if ($value['length'] != '') {
                $value['enabled'] = isset($value['enabled']) ? true : false;
                $new_boxes[] = $value;
            }
        }
        return $new_boxes;
    }
	public static function generate_form_field($args,$base='')
	{		
		if(is_array($args))
		{
			foreach ($args as $key => $value)
			{
				$type=(isset($value['type']) ? $value['type'] : 'text');
				$field_name=isset($value['field_name']) ? $value['field_name'] : $value['option_name'];

				$form_toggler_p_class="";
				$form_toggler_register="";
				$form_toggler_child="";
				if(isset($value['form_toggler']))
				{
					if($value['form_toggler']['type']=='parent')
					{
						$form_toggler_p_class="wf_form_toggle";
						$form_toggler_register=' wf_frm_tgl-target="'.$value['form_toggler']['target'].'"';
					}
					elseif($value['form_toggler']['type']=='child')
					{
						$form_toggler_child=' wf_frm_tgl-id="'.$value['form_toggler']['id'].'" wf_frm_tgl-val="'.$value['form_toggler']['val'].'" '.(isset($value['form_toggler']['chk']) ? 'wf_frm_tgl-chk="'.$value['form_toggler']['chk'].'"' : '').(isset($value['form_toggler']['lvl']) ? ' wf_frm_tgl-lvl="'.$value['form_toggler']['lvl'].'"' : '');	
					}else
					{
						$form_toggler_child=' wf_frm_tgl-id="'.$value['form_toggler']['id'].'" wf_frm_tgl-val="'.$value['form_toggler']['val'].'" '.(isset($value['form_toggler']['chk']) ? 'wf_frm_tgl-chk="'.$value['form_toggler']['chk'].'"' : '').(isset($value['form_toggler']['lvl']) ? ' wf_frm_tgl-lvl="'.$value['form_toggler']['lvl'].'"' : '');	
						$form_toggler_p_class="wf_form_toggle";
						$form_toggler_register=' wf_frm_tgl-target="'.$value['form_toggler']['target'].'"';				
					}
					
				}
				$fld_attr=(isset($value['attr']) ? $value['attr'] : '');
				$field_only=(isset($value['field_only']) ? $value['field_only'] : false);
				$mandatory=(boolean) (isset($value['mandatory']) ? $value['mandatory'] : false);
				if($mandatory)
				{
					$fld_attr.=' required="required"';	
				}
				if($field_only===false)
				{
					$tooltip_html=self::set_tooltip($field_name,$base);
		?>
		<tr valign="top" <?php echo $form_toggler_child; ?>>
	        <th scope="row" >
	        	<label for="<?php echo $field_name;?>" style="float:left; width:100%;">
	        		<?php echo isset($value['label']) ? $value['label'] : ''; ?><?php echo ($mandatory ? '<span class="wt_pklist_required_field">*</span>' : ''); ?><?php echo $tooltip_html;?>
				</label>
			</th>
	        <td>
	        	<?php
	        	}
	        	$option_name=$value['option_name'];
	        	$vl=Wf_Woocommerce_Packing_List::get_option($option_name,$base);
	        	$vl=is_string($vl) ? stripslashes($vl) : $vl;
	        	if($type=='text')
				{
	        	?>
	            	<input type="text" <?php echo $fld_attr;?> name="<?php echo $field_name;?>" value="<?php echo $vl;?>" />
	            <?php
	        	}
	        	if($type=='number')
				{
				?>
	            	<input type="number" <?php echo $fld_attr;?> name="<?php echo $field_name;?>" value="<?php echo $vl;?>" />
	            <?php
				}
	        	elseif($type=='textarea')
				{
				?>
	            <textarea <?php echo $fld_attr;?> name="<?php echo $field_name;?>"><?php echo $vl;?></textarea>
	            <?php
				}elseif($type=='order_st_multiselect') //order status multi select
				{
					$order_statuses=isset($value['order_statuses']) ? $value['order_statuses'] : array();
					$field_vl=isset($value['field_vl']) ? $value['field_vl'] : array();
				?>
				<input type="hidden" name="<?php echo $field_name;?>_hidden" value="1" />
				<select class="wc-enhanced-select" id='<?php echo $field_name;?>_st' data-placeholder='<?php _e('Choose Order Status','print-invoices-packing-slip-labels-for-woocommerce');?>' name="<?php echo $field_name;?>[]" multiple="multiple" <?php echo $fld_attr;?>>
                    <?php
                    $Pdf_invoice=$vl ? $vl : array();
                    foreach($field_vl as $inv_key => $inv_value) 
                    {
            			echo "<option value=$inv_value".(in_array($inv_value, $Pdf_invoice) ? ' selected="selected"' : '').">$order_statuses[$inv_value]</option>";
                        
                    }
                    ?>
                </select>
				<?php
				}elseif($type=='checkbox') //checkbox
				{
					$field_vl=isset($value['field_vl']) ? $value['field_vl'] : "1";
				?>
					<input class="<?php echo $form_toggler_p_class;?>" type="checkbox" value="<?php echo $field_vl;?>" id="<?php echo $option_name;?>" name="<?php echo $field_name;?>" <?php echo ($field_vl==$vl ? ' checked="checked"' : '') ?> <?php echo $form_toggler_register;?> <?php echo $fld_attr;?>>
					<?php
				}
				elseif($type=='radio') //radio button
				{
					$radio_fields=isset($value['radio_fields']) ? $value['radio_fields'] : array();
					foreach ($radio_fields as $rad_vl=>$rad_label) 
					{
					?>
					<input type="radio" id="<?php echo $option_name.'_'.$rad_vl;?>" name="<?php echo $field_name;?>" class="<?php echo $form_toggler_p_class;?>" <?php echo $form_toggler_register;?> value="<?php echo $rad_vl;?>" <?php echo ($vl==$rad_vl) ? ' checked="checked"' : ''; ?> <?php echo $fld_attr;?> /> <?php echo $rad_label; ?>
					&nbsp;&nbsp;
					<?php
					}
					
				}elseif($type=='uploader') //uploader
				{
					?>
					<div class="wf_file_attacher_dv">
			            <input id="<?php echo $field_name; ?>"  type="text" name="<?php echo $field_name; ?>" value="<?php echo $vl; ?>" <?php echo $fld_attr;?>/>
						
						<input type="button" name="upload_image" class="wf_button button button-primary wf_file_attacher" wf_file_attacher_target="#<?php echo $field_name; ?>" value="<?php _e('Upload','print-invoices-packing-slip-labels-for-woocommerce'); ?>" />
					</div>
					<img class="wf_image_preview_small" src="<?php echo $vl ? $vl : Wf_Woocommerce_Packing_List::$no_image; ?>" />
					<?php
				}elseif($type=='select') //select
				{
					$select_fields=isset($value['select_fields']) ? $value['select_fields'] : array();
					?>
					<select name="<?php echo $field_name;?>" id="<?php echo $field_name;?>" class="<?php echo $form_toggler_p_class;?>" <?php echo $form_toggler_register;?> <?php echo $fld_attr;?>>
					<?php
					foreach ($select_fields as $sel_vl=>$sel_label) 
					{
					?>
						<option value="<?php echo $sel_vl;?>" <?php echo ($vl==$sel_vl) ? ' selected="selected"' : ''; ?>><?php echo $sel_label; ?></option>
					<?php
					}
					?>
					</select>
					<?php
				}
				if(isset($value['help_text']))
				{
	            ?>
	            <span class="wf_form_help"><?php echo $value['help_text']; ?></span>
	            <?php
	        	}
	        	if($field_only===false)
				{
	        	?>
	        </td>
	        <td></td>
	    </tr>
	    <?php
	    		}
	    	}
		}
	}

	/**
	 * Envelope settings tab content with tab div.
	 * relative path is not acceptable in view file
	 */
	public static function envelope_settings_tabcontent($target_id,$view_file="",$html="",$variables=array(),$need_submit_btn=0)
	{
		extract($variables);
	?>
		<div class="wf-tab-content" data-id="<?php echo $target_id;?>">
			<?php
			if($view_file!="" && file_exists($view_file))
			{
				include_once $view_file;
			}else
			{
				echo $html;
			}
			?>
			<?php 
			if($need_submit_btn==1)
			{
				include plugin_dir_path(WF_PKLIST_PLUGIN_FILENAME)."admin/views/admin-settings-save-button.php";
			}
			?>
		</div>
	<?php
	}

	/**
	 * Envelope settings subtab content with subtab div.
	 * relative path is not acceptable in view file
	 */
	public static function envelope_settings_subtabcontent($target_id,$view_file="",$html="",$variables=array(),$need_submit_btn=0)
	{
		extract($variables);
	?>
		<div class="wf_sub_tab_content" data-id="<?php echo $target_id;?>">
			<?php
			if($view_file!="" && file_exists($view_file))
			{
				include_once $view_file;
			}else
			{
				echo $html;
			}
			?>
			<?php 
			if($need_submit_btn==1)
			{
				include plugin_dir_path(WF_PKLIST_PLUGIN_FILENAME)."admin/views/admin-settings-save-button.php";
			}
			?>
		</div>
	<?php
	}

	/**
	 Registers modules: public+admin	 
	 */
	public function admin_modules()
	{ 
		$wt_pklist_admin_modules=get_option('wt_pklist_admin_modules');
		if($wt_pklist_admin_modules===false)
		{
			$wt_pklist_admin_modules=array();
		}
		foreach (self::$modules as $module) //loop through module list and include its file
		{
			$is_active=1;
			if(isset($wt_pklist_admin_modules[$module]))
			{
				$is_active=$wt_pklist_admin_modules[$module]; //checking module status
			}else
			{
				$wt_pklist_admin_modules[$module]=1; //default status is active
			}
			$module_file=plugin_dir_path( __FILE__ )."modules/$module/$module.php";
			if(file_exists($module_file) && $is_active==1)
			{
				self::$existing_modules[]=$module; //this is for module_exits checking
				require_once $module_file;
			}else
			{
				$wt_pklist_admin_modules[$module]=0;	
			}
		}
		$out=array();
		foreach($wt_pklist_admin_modules as $k=>$m)
		{
			if(in_array($k,self::$modules))
			{
				$out[$k]=$m;
			}
		}
		update_option('wt_pklist_admin_modules',$out);
	}

	public static function module_exists($module)
	{
		return in_array($module,self::$existing_modules);
	}

	/**
	*	@since 2.6.2
	* 	Save document settings ajax hook
	*/
	public function save_document_settings()
	{
		$out=array(
			'status'=>false,
			'msg'=>__('Error', 'print-invoices-packing-slip-labels-for-woocommerce'),
		);
		if(Wf_Woocommerce_Packing_List_Admin::check_write_access()) 
    	{
			do_action('wt_pklist_document_save_settings');
			$out['status']=true;
	        $out['msg']=__('Settings Updated', 'print-invoices-packing-slip-labels-for-woocommerce');
    	}
		echo json_encode($out);
		exit();
	}

	/**
	*	@since 2.6.2
	* 	Save admin settings and module settings ajax hook
	*/
	public function save_settings()
	{
		$out=array(
			'status'=>false,
			'msg'=>__('Error', 'print-invoices-packing-slip-labels-for-woocommerce'),
		);

		$base=(isset($_POST['wf_settings_base']) ? sanitize_text_field($_POST['wf_settings_base']) : 'main');
		$base_id=($base=='main' ? '' : Wf_Woocommerce_Packing_List::get_module_id($base));
		if(Wf_Woocommerce_Packing_List_Admin::check_write_access()) 
    	{
    		$the_options=Wf_Woocommerce_Packing_List::get_settings($base_id);
    		
    		//multi select form fields array. (It will not return a $_POST val if it's value is empty so we need to set default value)
	        $default_val_needed_fields=array();

	        /* this is an internal filter */
	        $default_val_needed_fields=apply_filters('wt_pklist_intl_alter_multi_select_fields', $default_val_needed_fields, $base_id);

	        $validation_rule=array(				
				'woocommerce_wf_packinglist_boxes'=>array('type'=>'text_arr'),
				'woocommerce_wf_packinglist_footer'=>array('type'=>'textarea'),
		    ); //this is for plugin settings default. Modules can alter

	        $validation_rule=apply_filters('wt_pklist_intl_alter_validation_rule', $validation_rule, $base_id);

	        foreach($the_options as $key => $value) 
	        {
	            if(isset($_POST[$key]))
	            {
	            	$the_options[$key]=$this->validate_settings_data($_POST[$key], $key, $validation_rule);
	            	if($key=='woocommerce_wf_packinglist_boxes')
	            	{
	            		$the_options[$key]=$this->validate_box_packing_field($_POST[$key]);
	            	}
	            }else
	            {
	            	if(array_key_exists($key,$default_val_needed_fields))
	            	{
	            		/* Set a hidden field for every multi-select field in the form. This will be used to populate the multi-select field with an empty array when it does not have any value. */
	            		if(isset($_POST[$key.'_hidden']))
	            		{
	            			$the_options[$key]=$default_val_needed_fields[$key];
	            		}
	            	}
	            }
	        }
	        Wf_Woocommerce_Packing_List::update_settings($the_options, $base_id);

	        do_action('wf_pklist_intl_after_setting_update', $the_options, $base_id);

	        $out['status']=true;
	        $out['msg']=__('Settings Updated', 'print-invoices-packing-slip-labels-for-woocommerce');
    	}
		echo json_encode($out);
		exit();
	}

	public static function strip_unwanted_tags($html)
	{
		$html=html_entity_decode(stripcslashes($html));
		$html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
		$html = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $html);
		$html = preg_replace('#<audio(.*?)>(.*?)</audio>#is', '', $html);
		$html = preg_replace('#<video(.*?)>(.*?)</video>#is', '', $html);
		return $html;
	}

	/**
	*	@since 2.6.6
	* 	List of all languages with locale name and native name
	* 	@return array An associative array of languages.
	*/
	public static function get_language_list()
	{
		include plugin_dir_path(__FILE__).'data/data.language-list.php';
		
		/**
		*	Alter language list.
		*	@param array An associative array of languages.
		*/
		$wt_pklist_language_list=apply_filters('wt_pklist_alter_language_list', $wt_pklist_language_list);

		return $wt_pklist_language_list;
	}

	/**
	*	@since 2.6.6 Get list of RTL languages
	*	@return array an associative array of RTL languages with locale name, native name, locale code, WP locale code
	*/
	public static function get_rtl_languages()
	{
		$rtl_lang_keys=array('ar', 'dv', 'he_IL', 'ps', 'fa_IR', 'ur');

		/**
		*	Alter RTL language list.
		*	@param array RTL language locale codes (WP specific locale codes)
		*/
		$rtl_lang_keys=apply_filters('wt_pklist_alter_rtl_language_list', $rtl_lang_keys);

		$lang_list=self::get_language_list(); //taking full language list		
		
		$rtl_lang_keys=array_flip($rtl_lang_keys);
		return array_intersect_key($lang_list, $rtl_lang_keys);
	}

	/**
	*	@since 2.6.6 Checks user enabled RTL and current language needs RTL support.
	*	@return boolean 
	*/
	public static function is_enable_rtl_support()
	{
		if(!is_null(self::$is_enable_rtl)) /* already checked then return the stored result */
		{
			return self::$is_enable_rtl;
		}
		$rtl_languages=self::get_rtl_languages();
		$current_lang=get_locale();
		
		self::$is_enable_rtl=isset($rtl_languages[$current_lang]); 
		return self::$is_enable_rtl;
	}
}
