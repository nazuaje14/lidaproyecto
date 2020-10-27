<div class="wfte_rtl_main wfte_invoice-main">
  <div class="wfte_invoice-header wfte_invoice-header_color clearfix">
      <div class="wfte_invoice-header_top clearfix">
        <div class="wfte_company_logo float_left">
          <div class="wfte_company_logo_img_box">
            <img src="[wfte_company_logo_url]" class="wfte_company_logo_img">
          </div>
          <div class="wfte_company_name wfte_hidden">[wfte_company_name]</div>
          <div class="wfte_company_logo_extra_details">__[]__</div>
        </div>
        <div class="float_right" style="width:28%;">
          <div class="wfte_invoice_data wfte_invoice-header_color">
            <div class="wfte_invoice_number">
              <span class="wfte_invoice_number_label">__[Invoice No:]__</span> 
              <span class="wfte_invoice_number_val">[wfte_invoice_number]</span>
            </div>
            <div class="wfte_order_date" data-order_date-format="d/M/Y">
              <span class="wfte_order_date_label">__[Order Date:]__</span> 
              <span class="wfte_order_date_val">[wfte_order_date]</span>
            </div>
            <div class="wfte_dispatch_date" data-dispatch_date-format="d/M/Y">
              <span class="wfte_dispatch_date_label">__[Dispatch Date:]__</span> 
              <span class="wfte_dispatch_date_val">[wfte_dispatch_date]</span>
            </div>
          </div>
        </div>
      </div>
      <div class="wfte_addrss_field_main clearfix">
         <div class="wfte_addrss_fields wfte_from_address wfte_text_left float_left">
           <div class="wfte_address-field-header wfte_from_address_label">__[From Address:]__</div>
           <div class="wfte_from_address_val">[wfte_from_address]</div>
         </div>
         <div class="wfte_addrss_fields wfte_billing_address wfte_text_left float_left">
           <div class="wfte_address-field-header wfte_billing_address_label">__[Billing Address:]__</div>
           [wfte_billing_address]
         </div>
         <div class="wfte_addrss_fields wfte_shipping_address wfte_text_left float_left">
           <div class="wfte_address-field-header wfte_shipping_address_label">__[Shipping Address:]__</div>
           [wfte_shipping_address]
         </div>
         <div class="wfte_addrss_fields float_left">
          <div class="wfte_invoice_data">  
              <div class="wfte_vat_number">
                <span class="wfte_vat_number_label">__[VAT:]__</span>
                <span class="wfte_vat_number_val">[wfte_vat_number]</span>
              </div>
              <div class="wfte_ssn_number">
                <span class="wfte_ssn_number_label">__[SSN:]__</span>
                <span class="wfte_ssn_number_val">[wfte_ssn_number]</span>
              </div>
              <div class="wfte_email">
                <span class="wfte_email_label">__[Email:]__</span>
                <span class="wfte_email_val">[wfte_email]</span>
              </div>
              <div class="wfte_tel">
                <span class="wfte_tel_label">__[Tel:]__</span>
                <span class="wfte_tel_val">[wfte_tel]</span>
              </div>
              <div class="wfte_shipping_method">
                <span class="wfte_shipping_method_label">__[Shipping Method:]__</span>
                <span class="wfte_shipping_method_val">[wfte_shipping_method]</span>
              </div>
              <div class="wfte_tracking_number">
                <span class="wfte_tracking_number_label">__[Tracking number:]__</span>
                <span class="wfte_tracking_number_val">[wfte_tracking_number]</span>
              </div>
          </div>
          <div class="wfte_order_item_meta">[wfte_order_item_meta]</div>
          [wfte_extra_fields]
         </div>
      </div>
  </div>
  <div class="wfte_invoice-body clearfix">
  [wfte_product_table_start]
    <table class="wfte_product_table">
        <thead class="wfte_product_table_head">
          <tr>
            <th class="wfte_product_table_head_image wfte_product_table_head_bg wfte_table_head_color" col-type="image">__[Image]__</th>
            <th class="wfte_product_table_head_sku wfte_product_table_head_bg wfte_table_head_color" col-type="sku">__[SKU]__</th>
            <th class="wfte_product_table_head_product wfte_product_table_head_bg wfte_table_head_color" col-type="product">__[Product]__</th>
            <th class="wfte_product_table_head_quantity wfte_product_table_head_bg wfte_table_head_color" col-type="quantity">__[Quantity]__</th>
            <th class="wfte_product_table_head_price wfte_product_table_head_bg wfte_table_head_color" col-type="price">__[Price]__</th>
            <th class="wfte_product_table_head_total_price wfte_right_column wfte_product_table_head_bg wfte_table_head_color" col-type="total_price">__[Total Price]__</th>     
          </tr>
        </thead>
        <tbody class="wfte_product_table_body wfte_table_body_color">
        </tbody>
      </table>   
  [wfte_product_table_end]   
  <table class="wfte_payment_summary_table wfte_product_table">
    <tbody class="wfte_payment_summary_table_body wfte_table_body_color">
      <tr class="wfte_payment_summary_table_row wfte_product_table_subtotal">
        <td class="wfte_left_column"></td>
        <td class="wfte_product_table_subtotal_label wfte_text_right">__[Subtotal]__</td>
        <td class="wfte_right_column">[wfte_product_table_subtotal]</td>
      </tr>
      <tr class="wfte_payment_summary_table_row wfte_product_table_shipping">
        <td class="wfte_left_column"></td>
        <td class="wfte_product_table_shipping_label wfte_text_right">__[Shipping]__</td>
        <td class="wfte_right_column">[wfte_product_table_shipping]</td>
      </tr>
      <tr class="wfte_payment_summary_table_row wfte_product_table_cart_discount">
        <td class="wfte_left_column"></td>
        <td class="wfte_product_table_cart_discount_label wfte_text_right">__[Cart Discount]__</td>
        <td class="wfte_right_column">[wfte_product_table_cart_discount]</td>
      </tr>
      <tr class="wfte_payment_summary_table_row wfte_product_table_order_discount">
        <td class="wfte_left_column"></td>
        <td class="wfte_product_table_order_discount_label wfte_text_right">__[Order Discount]__</td>
        <td class="wfte_right_column">[wfte_product_table_order_discount]</td>
      </tr>
      <tr data-row-type="wfte_tax_items" class="wfte_payment_summary_table_row wfte_product_table_tax_item">
        <td class="wfte_left_column"></td>
        <td class="wfte_product_table_tax_item_label wfte_text_right">[wfte_product_table_tax_item_label]</td>
        <td class="wfte_right_column">[wfte_product_table_tax_item]</td>
      </tr>
      <tr class="wfte_payment_summary_table_row wfte_product_table_total_tax">
        <td class="wfte_left_column"></td>
        <td class="wfte_product_table_total_tax_label wfte_text_right">__[Total Tax]__</td>
        <td class="wfte_right_column">[wfte_product_table_total_tax]</td>
      </tr>
      <tr class="wfte_payment_summary_table_row wfte_product_table_fee">
        <td class="wfte_left_column"></td>
        <td class="wfte_product_table_fee_label wfte_text_right">__[Fee]__</td>
        <td class="wfte_right_column">[wfte_product_table_fee]</td>
      </tr>
      <tr class="wfte_payment_summary_table_row wfte_product_table_coupon">
        <td class="wfte_left_column"></td>
        <td class="wfte_product_table_coupon_label wfte_text_right">__[Coupon Used]__</td>
        <td class="wfte_right_column">[wfte_product_table_coupon]</td>
      </tr>
      <tr class="wfte_payment_summary_table_row wfte_product_table_payment_method">
        <td class="wfte_left_column"></td>
        <td class="wfte_product_table_payment_method_label wfte_text_right">__[Payment Method]__</td>
        <td class="wfte_right_column">[wfte_product_table_payment_method]</td>
      </tr>
      <tr class="wfte_payment_summary_table_row wfte_product_table_payment_total">
        <td class="wfte_left_column"></td>
        <td class="wfte_product_table_payment_total_label wfte_product_table_head_bg wfte_table_head_color wfte_text_right">__[Total]__</td>
        <td class="wfte_product_table_payment_total_val wfte_product_table_head_bg wfte_table_head_color wfte_right_column">[wfte_product_table_payment_total]</td>
      </tr>
    </tbody>
  </table>
    <div class="wfte_addrss_field_main clearfix">
       <div class="wfte_addrss_fields float_left">
         <div class="wfte_return_address wfte_text_left">
           <div class="wfte_address-field-header wfte_return_address_label">__[Return Address:]__</div>
           [wfte_return_address]
         </div>
       </div>
    </div>
    <div class="wfte_return_policy clearfix wfte_text_left">
      [wfte_return_policy]
    </div>
    <div class="wfte_footer clearfix wfte_text_left">
      [wfte_footer]
    </div>
  </div>
</div>
<style type="text/css">
.clearfix::after {
    display: block;
    clear: both;
  content: "";}
.wfte_invoice-main{ color:#73879C; font-family:"Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif; font-size:12px; font-weight:400; line-height:14px; box-sizing:border-box; width:100%; margin:0px; padding:0px;}
.wfte_invoice-main *{ box-sizing:border-box;}
.wfte_invoice-header{ background:#445aa8; color:#ffffff; padding:15px 20px; width:100%; }
.wfte_invoice-header_color{ color:#ffffff;  font-family:"Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;  }
.wfte_invoice-header_top{ padding:15px 0px; padding-bottom:25px; border-bottom:solid 1px rgba(255, 255, 255, 0.2); width:100%; height:auto;}
.wfte_company_logo{width:40%;}
.wfte_company_logo_img{ width:150px; max-width:100%; }
.wfte_company_name{ font-size:18px; font-weight:bold; }
.wfte_company_logo_extra_details{ font-size:12px; }
.wfte_invoice_number{ font-size:12px; font-weight:bold; }
.wfte_order_number{ font-size:12px; font-weight:bold; }
.wfte_invoice_date{ font-size:12px;}
.wfte_order_date{ font-size:12px;}
.wfte_invoice_data{ width:100%; }
.wfte_addrss_field_main{width:100%; font-size:12px; padding-top:25px; }
.wfte_addrss_fields{ width:24%; padding:0px 2px;}
.wfte_address-field-header{ font-weight:bold; }
.wfte_invoice-body{background:#ffffff; color:#23272c; padding:15px 20px; width:100%;}
.wfte_product_table{ width:100%; border-collapse:collapse; margin:0px; }
.wfte_product_table .wfte_right_column{ width:25%; }
.wfte_payment_summary_table .wfte_left_column{ width:50%; }

.wfte_product_table_head{}
.wfte_product_table_head th{height:36px; padding:0px 5px; font-size:.75rem; text-align:center; line-height:10px; font-family:"Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;}
.wfte_product_table_body td, .wfte_payment_summary_table_body td{ text-align:center; font-size:12px; line-height:10px; font-family:"Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;}
.wfte_product_table_body td{padding:2px 5px;}
.wfte_payment_summary_table_body td{padding:8px 5px;}

.wfte_product_table_head_bg{background-color:#212529;}
.wfte_table_head_color{ color:#ffffff; }

.wfte_product_table_payment_total{ font-size:12px; height:36px; padding:0px;line-height:10px;}
td.wfte_product_table_payment_total_label{height:36px; padding:0px 5px; line-height:10px;}
.wfte_product_table_payment_total_val{}
.wfte_return_policy{width:100%; height:auto; border-top:solid 1px #dfd5d5; padding:5px 0px; margin-top:15px; }
.wfte_footer{width:100%; height:auto; border-top:solid 1px #dfd5d5; padding:5px 0px; margin-top:15px;}

.wfte_invoice_data td, .wfte_order_data td, .wfte_extra_fields td{font-size:12px; padding:0px; font-family:"Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;}
.wfte_payment_summary_table_row td{ }

.float_left{ float:left; }
.float_right{ float:right; }
</style>