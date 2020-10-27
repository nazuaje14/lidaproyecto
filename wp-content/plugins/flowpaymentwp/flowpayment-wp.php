<?php

/**
 * Prohibits the execution of this file directly from the browser.
 */
defined('ABSPATH') or die ('Nope!');

/**
 * Plugin Name: Flow Payment (webpay)
 * Plugin URI: http://flow.cl
 * Version: 2.0.0
 * Author: Flow
 * Author URI: http://flow.cl
 * Description: Flow Payment Gateway for webpay
 */

define('PLUGIN_DIR_webpay', dirname(__FILE__).'/');
include PLUGIN_DIR_webpay."lib/FlowApiwp.php";

add_action('plugins_loaded', 'woocommerce_flow_webpay_init', 0);

function woocommerce_flow_webpay_init(){

    class WC_Gateway_Flow_webpay extends WC_Payment_Gateway {

        protected $medio_pago;

        public function __construct(){

            $this->medio_pago = 'webpay';
            $this->id = 'flow_'.$this->medio_pago;
            
            if (file_exists(PLUGIN_DIR_webpay."images/logo-small.png")) {
                if (!file_exists(PLUGIN_DIR_webpay."images/custom-logo-small.png")) {
                    copy(PLUGIN_DIR_webpay."images/logo-small.png", PLUGIN_DIR_webpay."images/custom-logo-small.png");
                }
            }            
            $this->icon = plugins_url('images/custom-logo-small.png', __FILE__);
            $this->method_title  = 'Flow webpay';
            $this->method_description = __('Pago electr&oacute;nico via ' .$this->medio_pago, 'woocommerce');
            $this->has_fields = false;
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');

            add_action('woocommerce_api_confirm_' .$this->id, array($this, 'callback_confirm' ));
            add_action('woocommerce_api_return_'.$this->id, array($this, 'callback_return'));
            add_action('woocommerce_api_custom_error_'.$this->id, array($this, 'callback_custom_error'));
            add_action('woocommerce_api_coupon_generated_'.$this->id, array($this, 'callback_coupon_generated'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'flow_process_admin_options'));
        }

        /**
         * Displays a page with a generic error in case any exception is thrown in the payment process.
         *
         * @return void
         */
        public function callback_custom_error(){
            global $woocommerce;
            $checkout_page_id = wc_get_page_id('checkout');
            //In case the url for the checkout is not found, we redirect to the home page.
            $url_return = ($checkout_page_id) !== -1  ? get_permalink($checkout_page_id) : get_home_url();

            wp_die(wc_get_template( 'error.php', array('url_return' => $url_return), 'flowpaymentwp/', plugin_dir_path( __FILE__ ) .'templates/')); 
        }

        /**
         * Displays a page with a message for the user when they generate a coupon in multicaja or servipag.
         *
         * @return void
         */
        public function callback_coupon_generated(){
            $url_return = get_home_url();
            wp_die(wc_get_template( 'coupon-generated.php', array('url_return' => $url_return), 'flowpaymentwp/', plugin_dir_path( __FILE__ ) .'templates/')); 
        }

        public function init_form_fields(){

            $this->form_fields = array(

                'enabled'   =>  array(
                    'title'     =>  __('Activar/Desactivar', 'woocommerce'),
                    'type'      =>  'checkbox',
                    'label'     =>  __('Activar o Desactivar el plugin', 'woocommerce'),
                    'default'   => 'yes'   
                ),
                'mode'  =>  array(
                    'title'    => __( 'Selector de plataforma de Flow', 'woocommerce' ),
                    'desc'     => __( 'Selector de plataforma de Flow', 'woocommerce' ),
                    'id'       => 'platform_select',
                    'default'  => 'all',
                    'type'     => 'select',
                    'class'    => 'wc-enhanced-select',
                    'css'      => 'min-width: 350px;',
                    'desc_tip' => true,
                    'options'  => array(
                        'TEST' => __( 'Plataforma sandbox Flow', 'woocommerce' ),
                        'PROD' => __( 'Plataforma de producci&oacute;n Flow', 'woocommerce' )
                    ),

                ),
                'title' =>  array(
                    'title'         =>  __('T&iacute;tulo', 'woocommerce'),
                    'type'          =>  'text',
                    'desc_tip'      =>  true,
                    'description'   =>  __('Medio de pago utilizado', 'woocommerce'),
                    'placeholder'   =>  __('Pago v&iacute;a webpay', 'woocommerce'),
                    'default'       =>  __('Flow webpay', 'woocommerce')
                ),
                'description' => array(
                    'title' => __('Descripci&oacute;n', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('Descripci&oacute;n Medio de Pago.', 'woocommerce'),
                    'placeholder' => __('Pago electr&oacute;nico a trav&eacute;s de webpay'),
                    'desc_tip'  =>  true,
                ),
                'api_key'    =>  array(
                    'title' =>  __('Api Key', 'woocommerce'),
                    'type'  =>  'text',
                    'desc_tip' => true,
                    'description' => __('El Api Key corresponde al identificador &uacute;nico de seguridad para ser usado en la integración de tu comercio con Flow.', 'woocommerce'),
                    'placeholder' => __('Api Key'),
                ),
                'secret_key' =>  array(
                    'title' =>  'Secret Key',
                    'type'  =>  'text',
                    'desc_tip' => true,
                    'description' => __('El Secret Key corresponde a una clave de seguridad para asegurar que la información que se está trasmitiendo viene de una fuente confiable', 'woocommerce'),
                    'placeholder' => __('Clave de Seguridad'),
                ),
                'logo-small' => array(
                    'title' => __('Logo a mostrar', 'woocommerce'),
                    'type' => 'file',
                    'description' => __('Corresponde al logo a mostrar al momento de pagar para este método de pago', 'woocommerce'),
                    'placeholder' => __('Logo'),
                    'desc_tip' => true
                )
            );

            //If the current plugin is not the webpay one, we add a url return field.
            if(!$this->isWebpay() && !$this->isOnepay()){
                $this->form_fields['return_url'] = array(
                    'title' => __('Return Url', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Ingrese su url de retorno', 'woocommerce'),
                    'desc_tip' => true
                );
            }
        }

        /**
         * Called after checkout. Creates the payment and redirects to Flow
         *
         * @param int $order_id
         * @return void
         */
        public function process_payment($order_id){

            $this->log('Entering process_payment', 'info');
            global $woocommerce;
            
            $order = $this->getOrder($order_id);

            $customer = new WC_Customer();
            $concept = 'Orden: '.$order_id." - ". urldecode(get_bloginfo('name'));
            $amount = (int) number_format($order->get_total(), 0, ',', '');
            $service = 'payment/create';
            $method = 'POST';
            $email = $this->getCustomerEmail($order);

            $confirm_url = add_query_arg('wc-api', 'confirm_'.$this->id, home_url('/')); 
            $return_url = add_query_arg('wc-api', 'return_'.$this->id, home_url('/'));

            $params = array(
                "commerceOrder" => $order_id,
                "subject" => $concept,
                "amount" => $amount,
                "email" => $email,
                "urlConfirmation" => $confirm_url,
                "urlReturn" => $return_url,
                "paymentMethod"=>$this->getPaymentMethod()
            );

            try{

                $this->log('Calling Flow service: '.$service." with params: ".json_encode($params));
                $flowApi = $this->getFlowApi();
                $result = $flowApi->send($service, $params, $method);    
                $this->log('Flow response: '.json_encode($result));
                $url_to_redirect = $result['url']."?token=".$result['token'];
                
                return array(
                    'result'   => 'success',
                    'redirect' => $url_to_redirect,
                );

            }catch(Exception $e){
                error_log($e->getMessage());
                $this->log('Unexpected error: '.$e->getCode(). ' - '.$e->getMessage(), 'error');
            }
            
        }

        /**
         * Called by flow asynchronously in order to confirm the payment.
         *
         * @return void
         */
        public function callback_confirm(){

            $this->log('Entering the confirm callback', 'info');

            try{

                $flowApi = $this->getFlowApi();
                $service = 'payment/getStatus';
                $method = 'GET';

                $token = filter_input(INPUT_POST, 'token');
                $params = array(
                    "token" => $token
                );
                
                $this->log('Calling the flow service: '.$service);
                $this->log('With params: '.json_encode($params));
                $result = $flowApi->send($service, $params, $method);
                $this->log('Flow response: '.json_encode($result));

                $order_id = $result['commerceOrder'];
                $order = $this->getOrder($order_id);
                $status = $result['status'];

                /*if($this->isTesting($result)){
                    $this->setProductionEnvSimulation($status, $result);
                }*/

                if($this->isPendingInFlow($status)){
                    $this->setOrderAsPending($order);
                }
                elseif($this->isPaidInFlow($status)){
                    $this->payOrder($order);
                }
                elseif($this->isCancelledInFlow($status)){
                    $this->cancelOrder($order);
                }
                else{
                    $this->rejectOrder($order);
                }

            }catch(Exception $e){
                error_log($e->getMessage());
                $this->log('Unexpected error: '.$e->getCode(). ' - '.$e->getMessage(), 'error');
            }
        }

        /**
         * Redirect by Flow after the payment process is complete.
         *
         * @return void
         */
        public function callback_return(){

            $this->log('Entering callback_return', 'info');
            $flowApi = $this->getFlowApi();
            $service = 'payment/getStatus';
            $method = 'GET';

            $token = filter_input(INPUT_POST, 'token');
            $params = array(
                "token" => $token
            );

            try{

                $this->log('Calling the flow service: '.$service);
                $this->log('With params: '.json_encode($params));
                $result = $flowApi->send($service, $params, $method);
                $this->log('Flow result: '.json_encode($result));

                $order_id = $result['commerceOrder'];
                $status = $result['status'];

                $order = $this->getOrder($order_id);

                $order_status = $order->get_status();

                if($this->userCanceledPayment($status, $result)){
                    $this->log('User canceled the payment. Redirecting to checkout...', 'info');
                    $this->redirectToCheckout();
                }

                /*if($this->isTesting($result)){
                    $this->log('Setting up the production simulation...');
                    $this->setProductionEnvSimulation($status, $result);
                }*/
                
                if($this->isPendingInFlow($status)){

                    $this->clearCart();

                    if($this->userGeneratedCoupon($status, $result)){

                        if(!empty( $this->get_option('return_url'))){
                            $this->redirectTo($this->get_option('return_url'));
                        }
                    }

                    $this->redirectToCouponGenerated($order);
                }
                elseif($this->isPaidInFlow($status)){

                    if($this->isPendingInStore($order_status)){
                        $this->payOrder($order);
                    }

                    $this->redirectToSuccess($order);
                }
                else{

                    if($this->isRejectedInFlow($status)){
                        if($this->isPendingInStore($order_status)){
                            $this->rejectOrder($order);
                        }
                    }

                    if($this->isCancelledInFlow($status)){

                        if($this->isPendingInStore($order_status)){
                            $this->cancelOrder($order);
                        }
                    }

                    $this->redirectToFailure($order);
                }

            }catch(Exception $e){
                $this->log('Unexpected error: '.$e->getCode(). ' - '.$e->getMessage(), 'error');
                $this->redirectToError();
            }
            
            wp_die();
        }

        /**
         * Renders the options for the admin and any errors in case there are any.
         *
         * @return void
         */
        public function admin_options(){
            
            if(!get_option('woocommerce_flow_api_key_valid'))
                $this->add_error('La llave de api es obligatoria.');

            if(!get_option('woocommerce_flow_secret_key_valid')){
                $this->add_error('La llave secreta es obligatoria.');
            }

            if($this->is_valid_for_use()){
                $this->display_errors();
                parent::admin_options();
            }
            else{
                ?>
                <div class="inline error">
                    <p>
                        <strong><?php esc_html_e( 'Gateway disabled', 'woocommerce' ); ?></strong>: <?php esc_html_e( 'Flow no soporta el tipo de moneda (' .get_woocommerce_currency() .').', 'woocommerce' ); ?>
                    </p>
                </div>
                <?php
            }
            
        }

        /**
         * Processes and validates the admin options
         *
         * @return void
         */
        public function flow_process_admin_options() {

            $hasFile = false;
            $nombrePlugin = basename(__DIR__);
            $idFileInput = "woocommerce_" . $this->id . "_logo-small";
            if (isset($_FILES[$idFileInput])) {
                $file = $_FILES[$idFileInput];
                $hasFile = $file['size']>0;
                if ($hasFile) {
                    move_uploaded_file($file['tmp_name'], PLUGIN_DIR_webpay."images/custom-logo-small.png");
                }
            }

            $post_data = $this->get_post_data();
            $anyErrors = false;

            if(empty($post_data['woocommerce_'.$this->id.'_api_key'])){
                update_option('woocommerce_flow_api_key_valid', false);
                $anyErrors = true;
            }
            else{
                update_option('woocommerce_flow_api_key_valid', true);
            }

            if(empty($post_data['woocommerce_'.$this->id.'_secret_key'])){
                update_option('woocommerce_flow_secret_key_valid', false);
                $anyErrors = true;
            }
            else{
                update_option('woocommerce_flow_secret_key_valid', true);
            }

            if(!$anyErrors)
                $this->process_admin_options();

            return ;

        }

        private function redirectToSuccess($order){
            $this->log('Redirecting to the success page...', 'info');
            $this->redirectTo($this->get_return_url($order));
        }
        
        private function redirectToFailure($order){
            $this->log('Redirecting to the failure page...', 'info');
            $failUrl = $order->get_cancel_order_url();
            $error_message = 'Su pedido ha fallado debido a un error en el pago. Intente nuevamente.';
            wc_add_notice( __($error_message, 'woocommerce'), 'error' );
            
            $this->redirectTo($failUrl);
        }

        private function redirectToCouponGenerated(){
            $url = add_query_arg('wc-api', 'coupon_generated_'.$this->id, home_url('/'));
            $this->redirectTo($url);
        }

        private function redirectToError(){
            $url = add_query_arg('wc-api', 'custom_error_'.$this->id, home_url('/'));
            $this->redirectTo($url);
        }

        private function redirectToCheckout(){
            global $woocommerce;
            $checkout_page_id = wc_get_page_id('checkout');
            //In case the url for the checkout is not found, we redirect to the home page.
            $url = ($checkout_page_id) !== -1  ? get_permalink($checkout_page_id) : get_home_url();
            $this->redirectTo($url);
        }

        private function redirectTo($url){
            wp_redirect($url);
            die();
        }

        /**
         * Checks if the user canceled the payment (webpay or onepay)
         *
         * @param int $status
         * @param array $flowData
         * @return boolean
         */
        private function userCanceledPayment($status, $flowData){
            return $this->isPendingInFlow($status)
            && empty($flowData['paymentData']['media'])
            && empty($flowData['pending_info']['media']);
        }
    
        /**
         * Checks if the user generated a coupon
         *
         * @param int $status
         * @param array $flowData
         * @return boolean
         */
        private function userGeneratedCoupon($status, $flowData){
            return $this->isPendingInFlow($status)
            && !empty($flowData['pending_info']['media']
            && empty($flowData['paymentData']['media']));
        }

        /**
         * Returns the numeric representation of the payment method
         *
         * @return void
         */
        private function getPaymentMethod(){

            $paymentMethod = 1;
            switch($this->medio_pago){
                case 'webpay': {
                    $paymentMethod = 1;
                    break;
                }
                case 'servipag': {
                    $paymentMethod = 2;
                    break;
                }
                case 'multicaja': {
                    $paymentMethod = 3;
                    break;
                }
                case 'onepay': {
                    $paymentMethod = 5;
                    break;
                }
                case 'flow': {
                    $paymentMethod = 9;
                    break;
                }
                default : {
                    $paymentMethod = 1;
                    break;
                }               
            }

            return $paymentMethod;
        }

        /**
         * Checks if the current plugin is webpay
         *
         * @param array $flowData
         * @return boolean
         */
        private function isWebpay(){
            return $this->getPaymentMethod() == 1;
        }

        /**
         * Checks if the current plugin is onepay
         *
         * @return boolean
         */
        private function isOnepay(){
            return $this->getPaymentMethod() == 5;
        }

        /**
         * Checks if the current payment method is multicaja
         *
         * @return boolean
         */
        private function isMulticaja(){
            return $this->getPaymentMethod() == 3;
        }

        /**
         * Checks if the current payment method is Servipag
         *
         * @return boolean
         */
        private function isServipag(){
            return $this->getPaymentMethod() == 2;
        }

        /**
         * Checks if the current payment method is Flow.
         *
         * @return boolean
         */
        private function isFlow(){
            return $this->getPaymentMethod() == 9;
        }

        /**
         * Checks if the order is paid in Flow
         *
         * @param int $status
         * @return boolean
         */
        private function isPaidInFlow($status){
            return $status == 2;
        }

        /**
         * Checks if the order is rejected in Flow.
         *
         * @param int $status
         * @return boolean
         */
        private function isRejectedInFlow($status){
            return $status == 3;
        }

        /**
         * Checks if the order is canceled in Flow.
         *
         * @param int $status
         * @return boolean
         */
        private function isCancelledInFlow($status){
            return $status == 4;
        }

        /**
         * Checks if the order is pending in Flow.
         *
         * @param int $status
         * @return boolean
         */
        private function isPendingInFlow($status){
            return $status == 1;
        }

        /**
         * Checks if the order in the store has a pending status
         *
         * @param int $orderStatus
         * @return boolean
         */
        private function isPendingInStore($orderStatus){
            return $orderStatus == 'pending';
        }

        /**
         * Checks if the order in the store has a paid status.
         *
         * @param int $orderStatus
         * @return boolean
         */
        private function isPaidInStore($orderStatus){
            return $orderStatus == 'completed';
        }

        /**
         * Checks if the order in the store has a processing status.
         *
         * @param int $orderStatus
         * @return boolean
         */
        private function isProcessingInStore($orderStatus){
            return $orderStatus == 'processing';
        }

        private function clearCart(){
            global $woocommerce;
            $woocommerce->cart->empty_cart();
        }

        /**
         * Checks if the current plugin is in testing mode (sandbox)
         *
         * @param array $flowData
         * @return boolean
         */
        private function isTesting($flowData){
            return ($this->get_option('mode') === 'TEST'
                    && (strtolower($flowData['paymentData']['media']) === 'servipag'
                    || strtolower($flowData['paymentData']['media']) === 'multicaja')
                    );
        }

        /**
         * Sets some variables in order to the emulate the production environment (since both environment return different results)
         *
         * @param int $status
         * @param array $flowData
         * @return void
         */
        private function setProductionEnvSimulation(&$status, &$flowData){

            $status = 1;
            $flowData['pending_info']['media'] = $flowData['paymentData']['media'];
            $flowData['paymentData']['media'] = '';
        }

        /**
         * Pays the order in the store
         *
         * @param WC_Order $order
         * @return void
         */
        private function payOrder($order){
            $this->log('Paying order #'.$order->get_order_number());
            $this->addOrderNote($order, 'Pagado con flow');
            $order->payment_complete();
        }

        /**
         * Rejects the order in the store.
         *
         * @param WC_Order $order
         * @return void
         */
        private function rejectOrder($order){
            $this->log('Rejecting order #'.$order->get_order_number());
            $this->addOrderNote($order, 'La orden fue rechazada por Flow');
            $order->update_status('failed');
        }
        
        /**
         * Cancels the order in the store
         *
         * @param WC_Order $order
         * @return boolean
         */
        private function cancelOrder($order){
            $this->log('Cancelling order #'.$order->get_order_number());
            $this->addOrderNote($order, 'La orden fue cancelada por Flow');
            $order->update_status('cancelled');
        }

        /**
         * Adds a pending note to the order, since the pending status is the default
         *
         * @param WC_Order $order
         * @return void
         */
        private function setOrderAsPending($order){
            $this->log('Setting as pending order #'.$order->get_order_number());
            //Since the default status of the order is pending, we only add a note here.
            $this->addOrderNote($order, 'La orden se encuentra pendiente.');
        }

        private function addOrderNote($order, $note){
            $order->add_order_note(__($note, 'woocommerce'));
        }

        private function is_valid_for_use()
        {
            if (!in_array(get_woocommerce_currency(), apply_filters('woocommerce_' . $this->id . '_supported_currencies', array('CLP')))) {
                return false;
            }
            return true;
        }

        /**
         * Returns an order object. Depending on the version of woocommerce, it will use one method or another.
         *
         * @param int $order_id
         * @return void
         */
        private function getOrder($order_id){

            if($this->flow_woocommerce_version_check()){
                return new WC_Order($order_id);
            }
            else{
                return wc_get_order($order_id);
            }
        }


        /**
         * Checks if the woocommerce's version is at least 3.0 or more
         *
         * @param string $version
         * @return void
         */
        private function flow_woocommerce_version_check($version = '3.0.0') {
            if (class_exists('WooCommerce')) {
                global $woocommerce;
                if (version_compare($woocommerce->version, $version, ">=")) {
                    return true;
                }

                return false;
            }
            return false;
        }

        private function getCustomerEmail($order){

            if($this->flow_woocommerce_version_check()){
                $email = $order->get_billing_email();
            }
            else{
                $orderData = get_post_meta($order->id);
                $email = $orderData['_billing_email'][0];
            }

            return $email;
        }

        private function getFlowApi(){
            
            $apiKey = $this->get_option('api_key');
            $secretKey = $this->get_option('secret_key');
            $mode = $this->get_option('mode');
            $endpoint = $mode == 'PROD' ? 'https://www.flow.cl/api' : 'https://sandbox.flow.cl/api';
            $flowApi = new FlowApiwp($apiKey, $secretKey, $endpoint);         
            return $flowApi;
        }

        private function log($message, $type = 'info'){

            //if the version of wocommerce >= 3
            if($this->flow_woocommerce_version_check())
            {
                $logger = wc_get_logger();
                $logger->{$type}($message, array('source' => 'flow_webpay'));
            }
            else{
                $logger = new WC_Logger('flow_webpay');
                $logger->add('flow_webpay', $message);
            }
           
        }
        
    }

    function woocommerce_add_flow_webpay_class($methods){
        $methods[] = 'WC_Gateway_Flow_webpay';
        return $methods;
    }

    function woocommerce_add_flow_webpay_clp_currency($currencies)
    {
        $currencies["CLP"] = __('Pesos Chilenos');
        return $currencies;
    }

    function woocommerce_add_flow_webpay_clp_currency_symbol($currency_symbol, $currency)
    {
        switch ($currency) {
            case 'CLP':
                $currency_symbol = '$';
                break;
        }
        return $currency_symbol;
    }

    add_filter('woocommerce_currencies', 'woocommerce_add_flow_webpay_clp_currency', 10, 1);
    add_filter('woocommerce_currency_symbol', 'woocommerce_add_flow_webpay_clp_currency_symbol', 10, 2);
    add_filter('woocommerce_payment_gateways', 'woocommerce_add_flow_webpay_class');
}