<?php

namespace PixelYourSite;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function adminGetPromoNoticesContent() {
    return [
        'woo' => [

          [
              'disabled' => false,
            //  'from'     => 1,
            //  'to'       => 2,
              'content'  => '</br>PixelYourSite & WooCommerce: track both PROFIT and TOTAL for your Facebook Ads: <a href="https://www.pixelyoursite.com/cost-of-goods-purchase-and-completeregistration" target="_blank">LEARN HOW</a></br></br>'
          ],


        ],
        'edd' => [

          [
              'disabled' => false,
            //  'from'     => 0,
            //  'to'       => 1,
             'content'  => '</br>PixelYourSite: Learn how to do Facebook Dynamic Ads for NORMAL WordPress posts: <a href="https://www.pixelyoursite.com/facebook-dynamic-product-ads-for-wordpress" target="_blank">CLICK HERE</a></br></br>'
          ],

        ],
        'no_woo_no_edd' => [

          [
              'disabled' => false,
          //    'from'     => 0,
          //    'to'       => 1,
              'content'  => '</br>PixelYourSite: Learn how to do Facebook Dynamic Ads for NORMAL WordPress posts: <a href="https://www.pixelyoursite.com/facebook-dynamic-product-ads-for-wordpress" target="_blank">CLICK HERE</a></br></br>'
          ],

        ],
    ];
}
