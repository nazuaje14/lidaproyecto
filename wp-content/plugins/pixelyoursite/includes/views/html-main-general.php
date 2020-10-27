<?php

namespace PixelYourSite;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

?>

<!-- Pixel IDs -->
<div class="card card-static">
    <div class="card-header">
        Pixel IDs
    </div>
    <div class="card-body">

        <?php if ( Facebook()->enabled() ) : ?>

            <div class="row align-items-center mb-3">
                <div class="col-3">
                    <img class="tag-logo" src="<?php echo PYS_FREE_URL; ?>/dist/images/facebook-small-square.png">
                </div>
                <div class="col-7">
                    <h4 class="label">Facebook Pixel ID:</h4>
                    <?php Facebook()->render_pixel_id( 'pixel_id', 'Facebook Pixel ID' ); ?>
                    <small class="form-text">
                        <a href="https://www.pixelyoursite.com/pixelyoursite-free-version/add-your-facebook-pixel?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-ids"
                           target="_blank">How to get it?</a>
                    </small>
                    <p class="mt-3 mb-0">Add multiple Facebook Pixels with the <a href="https://www.pixelyoursite.com/?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-ids"
                                target="_blank">pro version</a>.</p>
                </div>
            </div>

                <div class="row align-items-center mb-3">
                    <div class="col-3">

                    </div>
                    <div class="col-7">
                        <h4 class="label">Conversion API (recommended):</h4>
                        <?php Facebook()->render_checkbox_input(
                            "use_server_api",
                            "Send events directly from your web server to Facebook through the Conversion API. This can help you capture more events. An access token is required to use the server-side API. <a href='https://www.pixelyoursite.com/documentation/configure-server-side-events' target='_blank'>Generate Access Token</a>"
                        ); ?>
                        <?php Facebook()->render_text_area_array_item("server_access_api_token","Api token") ?>
                        <small class="form-text">
                            This is an experimental feature and works only for the automatilly fired standard events. We plan to expand it to all events soon.
                        </small>
                    </div>
                </div>

                <div class="row align-items-center mb-3">
                    <div class="col-3"></div>
                    <div class="col-7">
                        <h4 class="label">test_event_code :</h4>
                        <?php Facebook()->render_text_input_array_item("test_api_event_code","Code"); ?>
                        <small class="form-text">
                            Use this if you need to test the server-side event. <strong>Remove it after testing</strong>
                        </small>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(isWPMLActive()) : ?>
                <div class="row mb-3">
                    <div class="col-3"></div>
                    <div class="col-7">
                        <strong>WPML Detected. </strong> With the <a target="_blank" href="https://www.pixelyoursite.com/plugins/pixelyoursite-professional?utm_medium=plugin&utm_campaign=multilingual">Advanced and Agency</a> licenses, you can fire a different pixel for each language.
                    </div>
                </div>
            <?php endif; ?>
            <hr>



	    <?php if ( GA()->enabled() ) : ?>

            <div class="row align-items-center mb-3">
                <div class="col-3">
                    <img class="tag-logo" src="<?php echo PYS_FREE_URL; ?>/dist/images/analytics-square-small.png">
                </div>
                <div class="col-7">
                    <h4 class="label">Google Analytics tracking ID:</h4>
                    <?php GA()->render_pixel_id( 'tracking_id', 'Google Analytics tracking ID' ); ?>
                    <small class="form-text">
                        <a href="https://www.pixelyoursite.com/pixelyoursite-free-version/add-your-google-analytics-code?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-ids"
                           target="_blank">How to get it?</a>
                    </small>
                    <p class="mt-3 mb-0">Add multiple Google Analytics tags with the <a href="https://www.pixelyoursite.com/?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-ids"
                                target="_blank">pro version</a>.</p>
                </div>
            </div>
            <?php if(isWPMLActive()) : ?>
                <div class="row mb-3">
                    <div class="col-3"></div>
                    <div class="col-7">
                        <strong>WPML Detected. </strong> With the <a target="_blank" href="https://www.pixelyoursite.com/plugins/pixelyoursite-professional?utm_medium=plugin&utm_campaign=multilingual">Advanced and Agency</a> licenses, you can fire a different pixel for each language.
                    </div>
                </div>
            <?php endif; ?>
            <hr>

	    <?php endif; ?>

        <?php do_action( 'pys_admin_pixel_ids' ); ?>

        <div class="row align-items-center">
            <div class="col-3 py-4">
                <img class="tag-logo" src="<?php echo PYS_FREE_URL; ?>/dist/images/google-ads-square-small.png">
            </div>
            <div class="col-7">
                Add the Google Ads tag with the <a
                        href="https://www.pixelyoursite.com/google-ads-tag?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-ids"
                        target="_blank">pro version</a>.
            </div>
        </div>

    </div>
</div>

<div class="panel panel-primary">
    <div class="row">
        <div class="col">
            <p class="text-center">Learn how to use Facebook Pixel like a genuine expert. Download this Facebook
                Pixel Essential Guide:</p>
            <p class="text-center mb-0">
                <a href="https://www.pixelyoursite.com/facebook-pixel-pdf-guide?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-facebook-guide" class="btn btn-sm btn-save" target="_blank">Click to get the free guide</a>
            </p>
        </div>
    </div>
</div>

<div class="card" >
    <div class="card-header" style="background-color:#cd6c46;color:white;">
        Dynamic Ads for Blog Setup <?php cardCollapseBtn(); ?>
    </div>
    <div class="card-body">
        <div class="row mt-3">
            <div class="col-11">
                This setup will help you to run Facebook Dynamic Product Ads for your blog content.
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <a href="https://www.pixelyoursite.com/facebook-dynamic-product-ads-for-wordpress" target="_blank">Click here to learn how to do it</a>
            </div>
        </div>
        <?php if ( Facebook()->enabled() ) : ?>
            <hr/>
            <div class="row mt-3">
                <div class="col">
                    <?php Facebook()->render_switcher_input( 'fdp_use_own_pixel_id',false,true ); ?>
                    <h4 class="switcher-label">
                        Fire this events just for this Pixel ID with the
                        <a href="https://www.pixelyoursite.com/?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-ids" target="_blank">pro version</a>
                    </h4>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <label>Facebook Pixel ID:</label>
                    <?php Facebook()->render_text_input( 'fdp_pixel_id',"",true ); ?>
                </div>
            </div>

            <hr/>

            <div class="row mt-3">
                <div class="col">
                    <label>Content_type</label><?php
                    $options = array(
                        'product'    => 'Product',
                        ''           => 'Empty'
                    );
                    Facebook()->render_select_input( 'fdp_content_type',$options ); ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <label>Currency:</label><?php
                    $options = array();
                    $cur = getPysCurrencySymbols();
                    foreach ($cur as  $key => $val) {
                        $options[$key]=$key;
                    }
                    Facebook()->render_select_input( 'fdp_currency',$options ); ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <?php Facebook()->render_switcher_input( 'fdp_view_content_enabled' ); ?>
                    <h4 class="switcher-label">Enable the ViewContent on every blog page</h4>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <?php Facebook()->render_switcher_input( 'fdp_view_category_enabled' ); ?>
                    <h4 class="switcher-label">Enable the ViewCategory on every blog categories page</h4>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-11">
                    <?php Facebook()->render_switcher_input( 'fdp_add_to_cart_enabled' ); ?>
                    <h4 class="switcher-label">Enable the AddToCart event on every blog page</h4>
                </div>

                <div class="col-11 form-inline col-offset-left">
                    <label>Value:</label>
                    <?php Facebook()->render_number_input( 'fdp_add_to_cart_value',"Value" ); ?>
                </div>

                <div class="col-11 form-inline col-offset-left">
                    <label>Fire the AddToCart when scroll to</label>
                    <?php Facebook()->render_number_input( 'fdp_add_to_cart_event_fire_scroll',50 ); ?>
                    <label>%</label>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-11">
                    <?php Facebook()->render_switcher_input( 'fdp_purchase_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Purchase event on every blog page</h4>
                </div>
                <div class="col-11 form-inline col-offset-left">
                    <label>Value:</label>
                    <?php Facebook()->render_number_input( 'fdp_purchase_value',"Value" ); ?>
                </div>
                <div class="col-11 form-inline col-offset-left">
                    <label>Fire the Purchase event</label>

                    <?php
                    $options = array(
                        'scroll_pos'    => 'Page Scroll',
                        'comment'     => 'User commented',
                        'css_click'     => 'Click on CSS selector',
                        //Default event fires
                    );
                    Facebook()->render_select_input( 'fdp_purchase_event_fire',$options ); ?>
                    <span id="fdp_purchase_event_fire_scroll_block">
                        <?php Facebook()->render_number_input( 'fdp_purchase_event_fire_scroll',50 ); ?> <span>%</span>
                    </span>

                    <?php Facebook()->render_text_input( 'fdp_purchase_event_fire_css',"CSS selector"); ?>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <strong>You need to upload your blog posts into a Facebook Product Catalog.</strong> You can do this with our dedicated plugin:
                    <a href="https://www.pixelyoursite.com/wordpress-feed-facebook-dpa" target="_blank">Click Here</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<h2 class="section-title">Global Events</h2>

<!-- GeneralEvent -->
<div class="card">
    <div class="card-header">
        The GeneralEvent <?php cardCollapseBtn(); ?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-11">
                <p>The GeneralEvent will be fired on all your website pages, tracking important information as
                    parameters. Use it on Facebook or Pinterest for Custom Audiences and Custom Conversions.</p>
            </div>
            <div class="col-1">
                <?php renderPopoverButton( 'general_event' ); ?>
            </div>
        </div>

	    <?php if ( Facebook()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Facebook()->render_switcher_input( 'general_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable on Facebook</h4>
                </div>
            </div>
	    <?php endif; ?>

	    <?php if ( Pinterest()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Pinterest()->render_switcher_input( 'general_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable on Pinterest</h4>
                    <?php Pinterest()->renderAddonNotice(); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col">
                <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable on Google Ads</h4>
	            <?php renderProBadge('https://www.pixelyoursite.com/google-ads-tag/?utm_source=pys-free-plugin&utm_medium=pro-badge&utm_campaign=pro-feature') ?>
            </div>
        </div>

        <?php if ( Bing()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Bing()->render_switcher_input( 'general_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable on Bing</h4>
                    <?php Bing()->renderAddonNotice(); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="row my-3">
            <div class="col form-inline">
                <label>Custom name</label>
			    <?php PYS()->render_text_input( 'general_event_name' ); ?>
                <label>and delay</label>
			    <?php PYS()->render_number_input( 'general_event_delay' ); ?>
                <label>seconds</label>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <p>Fire on the following post types:</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php PYS()->render_switcher_input( 'general_event_on_posts_enabled' ); ?>
                <h4 class="switcher-label">Posts</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
			    <?php PYS()->render_switcher_input( 'general_event_on_pages_enabled' ); ?>
                <h4 class="switcher-label">Pages</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
			    <?php PYS()->render_switcher_input( 'general_event_on_tax_enabled' ); ?>
                <h4 class="switcher-label">Taxonomies</h4>
            </div>
        </div>

	    <?php if ( isWooCommerceActive() ) : ?>

            <div class="row">
                <div class="col">
				    <?php PYS()->render_switcher_input( 'general_event_on_woo_enabled' ); ?>
                    <h4 class="switcher-label">WooCommerce Products</h4>
                </div>
            </div>

	    <?php endif; ?>

	    <?php if ( isEddActive() ) : ?>

            <div class="row">
                <div class="col">
				    <?php PYS()->render_switcher_input( 'general_event_on_edd_enabled' ); ?>
                    <h4 class="switcher-label">Easy Digital Downloads Products</h4>
                </div>
            </div>

	    <?php endif; ?>

	    <?php foreach ( get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' ) as $post_type ) : ?>

		    <?php

		    // skip product post type when WC is active
		    if ( isWooCommerceActive() && $post_type->name == 'product' ) {
			    continue;
		    }

		    // skip download post type when EDD is active
		    if ( isEddActive() && $post_type->name == 'download' ) {
			    continue;
		    }

		    ?>

            <div class="row">
                <div class="col">
				    <?php PYS()->render_switcher_input( "general_event_on_{$post_type->name}_enabled" ); ?>
                    <h4 class="switcher-label"><?php esc_html_e( ucfirst( $post_type->name ) ); ?></h4>
                </div>
            </div>

	    <?php endforeach; ?>

	    <?php if ( GA()->enabled() ) : ?>
            <div class="row mt-3">
                <div class="col">
                    <p class="mb-0">* The GeneralEvent is not required on Google Analytics, because their script tracks this
                        type of data by default.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Search -->
<div class="card">
    <div class="card-header">
        Track Searches <?php cardCollapseBtn(); ?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-11">
                <p>This event will be fired when a search is performed on your website.</p>
            </div>
            <div class="col-1">
			    <?php renderPopoverButton( 'search_event' ); ?>
            </div>
        </div>

	    <?php if ( Facebook()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Facebook()->render_switcher_input( 'search_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Search event on Facebook</h4>
                </div>
            </div>
        <?php endif; ?>

	    <?php if ( GA()->enabled() ) : ?>
            <div class="row mb-1">
                <div class="col">
                    <?php GA()->render_switcher_input( 'search_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the search event on Google Analytics</h4>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col col-offset-left">
				    <?php GA()->render_checkbox_input( 'search_event_non_interactive',
					    'Non-interactive event' ); ?>
                </div>
            </div>
	    <?php endif; ?>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the search event on Google Ads</h4>
			    <?php renderProBadge('https://www.pixelyoursite.com/google-ads-tag/?utm_source=pys-free-plugin&utm_medium=pro-badge&utm_campaign=pro-feature') ?>
            </div>
        </div>

        <?php if ( Bing()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Bing()->render_switcher_input( 'search_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Search event on Bing</h4>
                    <?php Bing()->renderAddonNotice(); ?>
                </div>
            </div>
        <?php endif; ?>

	    <?php if ( Pinterest()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Pinterest()->render_switcher_input( 'search_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Search event on Pinterest</h4>
	                <?php Pinterest()->renderAddonNotice(); ?>
                </div>
            </div>
	    <?php endif; ?>
    </div>
</div>

<!-- Form -->
<div class="card">
    <div class="card-header">
        Track Forms <?php cardCollapseBtn(); ?>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-11">
                <p>This event will be fired when a form is submitted.</p>
            </div>
            <div class="col-1">
                <?php renderPopoverButton( 'form_event' ); ?>
            </div>
        </div>

        <?php if ( Facebook()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Facebook()->render_switcher_input( 'form_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Form event on Facebook</h4>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( GA()->enabled() ) : ?>
            <div class="row mb-1">
                <div class="col">
                    <?php GA()->render_switcher_input( 'form_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Form event on Google Analytics</h4>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col col-offset-left">
                    <?php GA()->render_checkbox_input( 'form_event_non_interactive',
                        'Non-interactive event' ); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col">
                <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the Form event on Google Ads</h4>
                <?php renderProBadge('https://www.pixelyoursite.com/google-ads-tag/?utm_source=pys-free-plugin&utm_medium=pro-badge&utm_campaign=pro-feature') ?>
            </div>
        </div>

        <?php if ( Pinterest()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Pinterest()->render_switcher_input( 'form_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Form event on Pinterest</h4>
                    <?php Pinterest()->renderAddonNotice(); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( Bing()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Bing()->render_switcher_input( 'form_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Form event on Bing</h4>
                    <?php Bing()->renderAddonNotice(); ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Comment -->
<div class="card">
    <div class="card-header">
        Track Comments <?php cardCollapseBtn(); ?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-11">
                <p>This event will be fired when a comment is posted on your website.</p>
            </div>
            <div class="col-1">
				<?php renderPopoverButton( 'comment_event' ); ?>
            </div>
        </div>

		<?php if ( Facebook()->enabled() ) : ?>
            <div class="row">
                <div class="col">
					<?php Facebook()->render_switcher_input( 'comment_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Comment event on Facebook</h4>
                </div>
            </div>
		<?php endif; ?>

		<?php if ( GA()->enabled() ) : ?>
            <div class="row mb-1">
                <div class="col">
					<?php GA()->render_switcher_input( 'comment_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Comment event on Google Analytics</h4>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col col-offset-left">
					<?php GA()->render_checkbox_input( 'comment_event_non_interactive',
						'Non-interactive event' ); ?>
                </div>
            </div>
		<?php endif; ?>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the Comment event on Google Ads</h4>
			    <?php renderProBadge('https://www.pixelyoursite.com/google-ads-tag/?utm_source=pys-free-plugin&utm_medium=pro-badge&utm_campaign=pro-feature') ?>
            </div>
        </div>

		<?php if ( Pinterest()->enabled() ) : ?>
            <div class="row">
                <div class="col">
					<?php Pinterest()->render_switcher_input( 'comment_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Comment event on Pinterest</h4>
					<?php Pinterest()->renderAddonNotice(); ?>
                </div>
            </div>
		<?php endif; ?>

        <?php if ( Bing()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Bing()->render_switcher_input( 'comment_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Comment event on Bing</h4>
                    <?php Bing()->renderAddonNotice(); ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- DownloadDocs -->
<div class="card">
    <div class="card-header">
        Track Downloads <?php cardCollapseBtn(); ?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-11">
                <p>This event will be fired when a file is downloaded. You can configure what file formats will count as
                    a download.</p>
            </div>
            <div class="col-1">
                <?php renderPopoverButton( 'download_docs_event' ); ?>
            </div>
        </div>

        <?php if ( Facebook()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Facebook()->render_switcher_input( 'download_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Download event on Facebook</h4>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( GA()->enabled() ) : ?>
            <div class="row mb-1">
                <div class="col">
                    <?php GA()->render_switcher_input( 'download_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Download event on Google Analytics</h4>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col col-offset-left">
                    <?php GA()->render_checkbox_input( 'download_event_non_interactive',
                        'Non-interactive event' ); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col">
                <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the Download event on Google Ads</h4>
                <?php renderProBadge('https://www.pixelyoursite.com/google-ads-tag/?utm_source=pys-free-plugin&utm_medium=pro-badge&utm_campaign=pro-feature') ?>
            </div>
        </div>

        <?php if ( Pinterest()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Pinterest()->render_switcher_input( 'download_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Download event on Pinterest</h4>
                    <?php Pinterest()->renderAddonNotice(); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ( Bing()->enabled() ) : ?>
            <div class="row">
                <div class="col">
                    <?php Bing()->render_switcher_input( 'download_event_enabled' ); ?>
                    <h4 class="switcher-label">Enable the Download event on Bing</h4>
                    <?php Bing()->renderAddonNotice(); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="row mt-3">
            <div class="col">
                <h4 class="label">Extension of files to track as downloads:</h4>
                <?php PYS()->render_tags_select_input( 'download_event_extensions' ); ?>
            </div>
        </div>
    </div>
</div>

<h2 class="section-title mt-3">PRO Events</h2>

<div class="panel">
    <div class="row">
        <div class="col text-center">
            <p>Thousands of business owners have decided to upgrade to the PRO version already.</p>
            <p class="mb-0"><a target="_blank" href="https://www.pixelyoursite.com/free-versus-pro?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-comparison">Click here for a
                    FREE versus PRO comparison</a></p>
        </div>
    </div>
</div>

<!-- ClickEvent -->
<div class="card card-disabled">
    <div class="card-header">
        Track Clicks <?php renderProBadge(); ?><?php cardCollapseBtn(); ?>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-11">
                <p>This event will be fired everytime a click is performed on your website.</p>
            </div>
            <div class="col-1">
			    <?php renderPopoverButton( 'click_event' ); ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the ClickEvent on Facebook</h4>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the ClickEvent on Google Analytics</h4>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col col-offset-left">
	            <?php renderDummyCheckbox( 'Non-interactive event' ); ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the ClickEvent on Google Ads</h4>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the ClickEvent on Pinterest</h4>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the ClickEvent on Bing</h4>
            </div>
        </div>

    </div>
</div>

<!-- WatchVideo -->
<div class="card card-disabled">
    <div class="card-header">
        Track Embedded Video Views <?php renderProBadge(); ?><?php cardCollapseBtn(); ?>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-11">
                <p>This event will be fired when an embedded YouTube or Vimeo video is watched on your website.</p>
            </div>
            <div class="col-1">
			    <?php renderPopoverButton( 'watch_video_event' ); ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the WatchVideo event on Facebook</h4>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the WatchVideo event on Google Analytics</h4>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col col-offset-left">
			    <?php renderDummyCheckbox( 'Non-interactive event' ); ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the WatchVideo event on Google Ads</h4>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the WatchVideo event on Pinterest</h4>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the ClickEvent on Bing</h4>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col col-offset-left">
                <div class="indicator indicator-off">OFF</div>
                <h4 class="indicator-label">YouTube embedded videos</h4>
            </div>
        </div>
        <div class="row">
            <div class="col col-offset-left">
                <div class="indicator indicator-off">OFF</div>
                <h4 class="indicator-label">Vimeo embedded videos</h4>
            </div>
        </div>

    </div>
</div>

<!-- CompleteRegistration -->
<div class="card card-disabled">
    <div class="card-header">
        Track User Sign-ups <?php renderProBadge(); ?><?php cardCollapseBtn(); ?>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-11">
                <p>This event will be fired after a new user account is created on your website.</p>
            </div>
            <div class="col-1">
			    <?php renderPopoverButton( 'complete_registration_event' ); ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the CompleteRegistration event on Facebook</h4>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the sign_up event on Google Analytics</h4>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col col-offset-left">
			    <?php renderDummyCheckbox( 'Non-interactive event' ); ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the sign_up event on Google Ads</h4>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the Signup event on Pinterest</h4>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the ClickEvent on Bing</h4>
            </div>
        </div>

    </div>
</div>

<!-- AdSense -->
<div class="card card-disabled">
    <div class="card-header">
        Track AdSense Clicks <?php renderProBadge(); ?><?php cardCollapseBtn(); ?>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-11">
                <p>This event will be fired by clicks on AdSense ads. Is designed to be used for retargeting with Custom
                    Audiences, or for ads optimization with Custom Conversions.</p>
            </div>
            <div class="col-1">
				<?php renderPopoverButton( 'adsense_event' ); ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the AdSense event on Facebook</h4>
            </div>
        </div>

        <div class="row">
            <div class="col">
			    <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the AdSense event on Pinterest</h4>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <?php renderDummySwitcher(); ?>
                <h4 class="switcher-label">Enable the ClickEvent on Bing</h4>
            </div>
        </div>

        <?php if ( GA()->enabled() ) : ?>
            <div class="row mt-3">
                <div class="col">
                    <p class="mb-0">* This event is not required on Google Analytics, because you have a complete integration with
                        AdSense available there.</p>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<h2 class="section-title mt-3">Global Settings</h2>

<div class="panel">
    <div class="row">
        <div class="col">
			<?php PYS()->render_switcher_input( 'debug_enabled' ); ?>
            <h4 class="switcher-label">Debugging mode. You will be able to see details about the events inside your
                browser console (developer tools).</h4>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php renderDummySwitcher(); ?>
            <h4 class="switcher-label">Track UTMs</h4>
            <?php renderProBadge(); ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <?php renderDummySwitcher(); ?>
            <h4 class="switcher-label">Track traffic source</h4>
            <?php renderProBadge(); ?>
        </div>
    </div>
    <div class="row form-group">
        <div class="col">
            <h4 class="label">Ignore these user roles from tracking:</h4>
			<?php PYS()->render_multi_select_input( 'do_not_track_user_roles', getAvailableUserRoles() ); ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h4 class="label">Permissions:</h4>
			<?php PYS()->render_multi_select_input( 'admin_permissions', getAvailableUserRoles() ); ?>
        </div>
    </div>
</div>

<div class="panel">
    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-between">
                <span class="mt-2">Track more key actions with the PRO version:</span>
                <a target="_blank" class="btn btn-sm btn-primary float-right" href="https://www.pixelyoursite.com/facebook-pixel-plugin/buy-pixelyoursite-pro?utm_source=pixelyoursite-free-plugin&utm_medium=plugin&utm_campaign=free-plugin-upgrade-blue">UPGRADE</a>
            </div>
        </div>
    </div>
</div>

<hr>
<div class="row justify-content-center">
    <div class="col-4">
        <button class="btn btn-block btn-save">Save Settings</button>
    </div>
</div>
