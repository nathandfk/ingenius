<?php
/**
 * Loads parent and child theme scripts.
 */
function shoptimizer_child_enqueue_scripts() {
	$parent_style    = 'shoptimizer-style';
	$parent_base_dir = 'shoptimizer';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array(), wp_get_theme( $parent_base_dir ) ? wp_get_theme( $parent_base_dir )->get( 'Version' ) : '' );
    wp_enqueue_style( 'shoptimizer-child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version') );
}

add_action( 'wp_enqueue_scripts', 'shoptimizer_child_enqueue_scripts' );


// remove dashicons in frontend to non-admin 
function wpdocs_dequeue_dashicon() {
    if (current_user_can( 'update_core' )) {
        return;
    }
    wp_deregister_style('dashicons');
}
add_action( 'wp_enqueue_scripts', 'wpdocs_dequeue_dashicon' );


remove_action('admin_notices', 'woothemes_updater_notice');


function pixeltracking( $order_id ) {

	$order = wc_get_order( $order_id );

$cart_value = number_format( (float) $order->get_total() - $order->get_total_tax() - $order->get_total_shipping() - $order->get_shipping_tax(), wc_get_price_decimals(), '.', '' );
$_billing_email = get_post_meta($order_id, '_billing_email', true);
// Google tag
echo '<script>gtag("event", "conversion", {"send_to": "AW-10790906988/HpVZCPHf-PwCEOzYwJko","value": '. $cart_value .',"currency": "EUR","transaction_id": "'. $order->get_order_number() . '"});</script>';
// Bing Tag
//echo '<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"17359958"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>';
//echo '<script>window.uetq = window.uetq || [];window.uetq.push("event", "vente", {"event_category": "vente", "event_label": "vente", "event_value": "1", "revenue_value": "'. $cart_value .'", "currency": "EUR"});</script>';
}
add_action( 'woocommerce_thankyou', 'pixeltracking' );


function add_remarketing_event()
{
    if(is_product())
    {
        global $product;
        $id = $product->get_id();
        echo '<script>gtag("event", "page_view", {"send_to": "AW-10790906988","ecomm_prodid": "woocommerce_gpf_'. $id .'"});</script>';
        echo '<script>window.uetq = window.uetq || [];window.uetq.push("event", "panier", {"event_label":"panier","event_category":"panier"});</script>';
    }
}
add_action('wp_footer', 'add_remarketing_event');

function pixeltrackingpanier() {

	// Google tag
	echo '<script>gtag("event", "conversion", {"send_to": "AW-10790906988/JA3GCNzl-PwCEOzYwJko"});</script>';
	
	}
	add_action( 'woocommerce_after_cart', 'pixeltrackingpanier' );



// EXTRA CHARGE PAYPAL

add_action( 'woocommerce_cart_calculate_fees', 'bbloomer_add_checkout_fee_for_gateway' );
  
function bbloomer_add_checkout_fee_for_gateway() {
    $chosen_gateway = WC()->session->get( 'chosen_payment_method' );
     if ( $chosen_gateway == 'ppcp-gateway' ) {
      WC()->cart->add_fee( 'Commission Paypal', 2 );
   }
}
  
// Part 2: reload checkout on payment gateway change
  
add_action( 'woocommerce_review_order_before_payment', 'bbloomer_refresh_checkout_on_payment_methods_change' );
  
function bbloomer_refresh_checkout_on_payment_methods_change(){
    ?>
    <script type="text/javascript">
        (function($){
            $( 'form.checkout' ).on( 'change', 'input[name^="payment_method"]', function() {
                $('body').trigger('update_checkout');
            });
        })(jQuery);
    </script>
    <?php
}


    /* Afficher “À partir de” pour les produits variables */
    add_filter( 'woocommerce_variable_sale_price_html', 'ai_variation_price_format', 10, 2 );
    add_filter( 'woocommerce_variable_price_html', 'ai_variation_price_format', 10, 2 );

    function ai_variation_price_format( $price, $product ) {
    $min_price = $product->get_variation_price( 'min', true );
    $max_price = $product->get_variation_price( 'max', true );

    if ($min_price != $max_price){
    //$price = sprintf( __( 'À partir de %1$s', 'woocommerce' ), wc_price( $min_price ) );
    $price = '';
    return $price;
    } else {
    $price = sprintf( __( '%1$s', 'woocommerce' ), wc_price( $min_price ) );
    return $price;
    }
    }


     function custom_override_checkout_fields( $fields ) {
        unset($fields['billing']['billing_state']);
        return $fields;
    }

    add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );


    add_action( 'woocommerce_single_product_summary' , 'add_below_promo', 1 );
 
function add_below_promo() {
    echo '<div class="special-offer"><span class="coupon">-'.do_shortcode( "[dashboard_promo value='AMOUNT']" ).'</span><span style="padding-left: 10px;"> Met de Code <strong>'.do_shortcode( "[dashboard_promo value='CODE']" ).'</strong></span></div>';
}


//add_action( 'woocommerce_after_single_product_summary' , 'add_stamped', 20 );
 
update_option( 'medium_large_size_w', 768, true );
update_option( 'medium_large_size_h', 768, true );

// Forcer la description
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );

function woo_new_product_tab( $tabs ) {
	// Ajout tab description
	$tabs['description'] = array(
		'title' 	=> __( 'Description', 'woocommerce' ),
		'priority' 	=> 1,
		'callback' 	=> 'woo_new_product_tab_content'
	);
	return $tabs;
}

function woo_new_product_tab_content() {

    $template = get_post_meta( get_the_ID(), 'template', true );

	if ($template == 'personnalise') {
		echo the_content();
	}
	else {
	echo do_shortcode('[elementor-template id="67133"]');
	}
}

add_action( 'wp', 'ts_remove_zoom_lightbox_gallery_support', 99 );
   
function ts_remove_zoom_lightbox_gallery_support() { 
   remove_theme_support( 'wc-product-gallery-zoom' );
   remove_theme_support( 'wc-product-gallery-lightbox' );

}

add_filter('yith_wccl_enable_handle_variation_gallery','__return_false');

require_once("custom-table/tabloide-personalized-functions.php");