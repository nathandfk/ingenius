<?php

require_once("personnalized.php");

    
    function my_script(){
        wp_enqueue_style('personalized-style', get_theme_file_uri()."/custom-table/assets/css/tabloide-personalized-style.css");
        wp_enqueue_style('personalized-responsive-style', get_theme_file_uri()."/custom-table/assets/css/personalized-responsive.css");
        wp_enqueue_script('table-personnalize-script', get_theme_file_uri()."/custom-table/assets/js/tabloide-personalized-script.js", ["jquery"], "1.0.0", true);
        wp_enqueue_script('table-personalized-additional-script', get_theme_file_uri()."/custom-table/assets/js/personalized-additional-script.js", ["jquery"], "1.0.0", true);
        wp_enqueue_script('custom-fontawesome', "https://kit.fontawesome.com/06fe12cea6.js", [], "", false);
        
        wp_localize_script('table-personnalize-script', 'ajax_object', ['ajaxurl' => admin_url('admin-ajax.php')]);
    }

    
    // Enqueue Style and Script
    add_action("wp_enqueue_scripts", "my_script", 999);

    // Ajax for checking picture uploaded
    add_action("wp_ajax_tabloide_check_picture_uploaded", 'tabloide_check_picture_uploaded');
    add_action("wp_ajax_nopriv_tabloide_check_picture_uploaded", "tabloide_check_picture_uploaded");

    // Ajax to add to cart custom table
    add_action("wp_ajax_tabloide_add_to_cart_product_personalized", 'tabloide_add_to_cart_product_personalized');
    add_action("wp_ajax_nopriv_tabloide_add_to_cart_product_personalized", "tabloide_add_to_cart_product_personalized");

    // Ajax get price and variation id of product
    add_action("wp_ajax_tabloide_get_price_and_variation_id", 'tabloide_get_price_and_variation_id');
    add_action("wp_ajax_nopriv_tabloide_get_price_and_variation_id", "tabloide_get_price_and_variation_id");


    // Create shortcode for page custom table
    function add_personalized_form($atts){
        $atts = shortcode_atts( array(
            'id' => '0'
        ), $atts, 'add_personalized_form' );
        $id = "{$atts['id']}";

        $products = wc_get_product($id); 
        $size = $products->default_attributes['pa_taille'];
        $support = $products->default_attributes['pa_support']; 
        $bgImage = wp_get_attachment_image_url($products->image_id, "");
        require ("partials/view_personalized_form.php");
        

    }
    //
    add_shortcode("personalized", "add_personalized_form");

//
function personalized_move_uploaded_file($tmp_name, $uploadfile){
    if(move_uploaded_file($tmp_name, $uploadfile)){
        return true;
    }
    return false;
}

// Check image quality
function personalized_check($api, $id, $generate, $name, $picture, $uploadfile){
    switch ($api) {
        case 'failure':
            $output = '{"id":"'.$id.'", "status":"error", "type":"failure", "message":"Ocorreu um problema inesperado ao carregar a imagem, atualize sua página e tente novamente", "picture_id":"'.$generate.'", "name":"'.$name.'", "picture":"'.$picture.'"}';
            unlink($uploadfile);
            break;
        case 'sharpness':
            $output = '{"id":"'.$id.'", "status":"error", "type":"sharpness", "message":"Sua imagem não é de boa qualidade", "picture_id":"'.$generate.'", "name":"'.$name.'", "picture":"'.$picture.'"}';
            unlink($uploadfile);
            break;
        case 'success':
            $output = '{"id":"'.$id.'", "status":"success", "type":"success", "message":"Sua foto esta correta", "picture_id":"'.$generate.'", "name":"'.$name.'", "picture":"'.$picture.'"}';
            break;
    }
    return $output;
}
// Upscale Image
function personalized_upscaling(string $picture, int $level) {
    $perso = new Perso();
    $img = file_get_contents($picture);
    $data = base64_encode($img);
    $json = $perso->upscale($data, $level);
    $output = json_decode($json);
    return $output->output_url;
}



/**
 * Check picture uploaded
 */
function tabloide_check_picture_uploaded(){
    $output = '{"id":"", "status":"error", "error":"007", "message":"Ocorreu um erro inesperado, atualize sua página e tente novamente", "picture_id":"", "name":"", "picture":""}';
    if (isset($_POST['tabloide-personalized-id'])) {
    $id = $_POST['tabloide-personalized-id'];
    $generate = wp_rand(100000, 9999999999);
    $uploaddir = WP_CONTENT_DIR.'/uploads/personalized/';
    $name = $file = $picture = "";


    if (isset($_POST["tabloide-link-upload"]) && !empty($_POST["tabloide-link-upload"])) {
        $inputFileValue = $_POST["tabloide-link-upload"];
        if (!filter_var($inputFileValue, FILTER_VALIDATE_URL)) {
            echo '{"id":"", "status":"error", "error":"004", "message":"O link que você forneceu está incorreto", "picture_id":"", "name":"", "picture":""}';
            die;
        }

        $imageInfo = getimagesize($inputFileValue);
        $image = explode("/",$imageInfo['mime']);
        $name = $generate.".".$image[1];
        $uploadfile = $uploaddir . $name;
        $file = $uploadfile;
        file_put_contents($uploadfile, file_get_contents($inputFileValue));
        $picture = site_url('/wp-content/uploads/personalized/') . $name;
    } else if (isset($_FILES['tabloide-upload']) && !empty($_FILES['tabloide-upload'])) {
        $inputFile = $_FILES['tabloide-upload'];
        $inputFileValue = $inputFile['name'];
        $name = $generate."_".basename($inputFileValue);
        $type = $_FILES['tabloide-upload']['type'];
        $uploadfile = $uploaddir.$name;
        personalized_move_uploaded_file($inputFile['tmp_name'], $uploadfile);
        if ($type == "image/heic" || $type == "image/webp") {
            $perso = new Perso();
            $format = ($type == "image/heic") ? "heic" : "webp";
            $perso->convert($uploaddir.$name, $uploaddir, $format);
            unlink($uploadfile);
            $justName = explode(".", $name);
            $name = $justName[0].".jpg";
            $uploadfile = $uploaddir.$name;
        }
        $file = $uploadfile;
        $picture = site_url('/wp-content/uploads/personalized/').$name;
    }

    // On vérifie si la valeur des champs renseignés par l'utilisateur n'est pas vide
    if (!empty($inputFileValue)) {
        if (!empty($file) && !empty($picture)) {
            $getImage = getimagesize($picture);

            // On upscale si la taille de l'image est comprise entre 500 et 2000
            if ($getImage[0] >= 500 && $getImage[0] <= 1920) {
                if ($getImage[1] >= 500 && $getImage[1] <= 1080) {
                    if ($getImage['mime'] == "image/png" || $getImage['mime'] == "image/jpeg"
                    || $getImage['mime'] == "image/jpg") {
                            $json = personalized_upscaling($file, 4);
                            if ($json) {
                                unlink($uploadfile);
                                $image = explode("/",$getImage['mime']);
                                $name = $generate.".".$image[1];
                                $uploadfile = $uploaddir.$name;
                                file_put_contents($uploadfile, file_get_contents($json));

                                $picture = site_url('/wp-content/uploads/personalized/').$name;
                                $getImage = getimagesize($picture);
                            }
                    }
                }
            }
            

            // On vérifie la qualité, la taille et le format de l'image
            if ($getImage[0] >= 1920 && $getImage[1] >= 1080) {

                $perso = new Perso(0.40);
                $api = $perso->check($uploadfile);
                if ($api == "success") {
                    if ($getImage['mime'] == "image/png" || $getImage['mime'] == "image/jpeg"
                        || $getImage['mime'] == "image/jpg") {
                            echo personalized_check($api, $id, $generate, $name, $picture, $uploadfile);
                            die;
                    } else {
                        unlink($uploadfile);
                        $picture = get_template_directory_uri()."/pictures/default-placeholder.png";
                        $output = '{"id":"'.$id.'", "status":"error", "error":"001", "message":"O formato do seu arquivo não é aceito", "picture_id":"'.$generate.'", "name":"'.$name.'", "picture":"'.$picture.'"}';
                    }
                } else {
                    unlink($uploadfile);
                    $picture = get_template_directory_uri()."/pictures/default-placeholder.png";
                    $output = '{"id":"'.$id.'", "status":"error", "error":"007", "message":"Sua imagem não é de boa qualidade", "picture_id":"'.$generate.'", "name":"'.$name.'", "picture":"'.$picture.'"}';
                }
            } else {
                unlink($uploadfile);
                $picture = get_template_directory_uri()."/pictures/default-placeholder.png";
                $output = '{"id":"'.$id.'", "status":"error", "error":"002", "message":"O tamanho da sua imagem é muito pequeno", "picture_id":"'.$generate.'", "name":"'.$name.'", "picture":"'.$picture.'"}';
            }
        } else {
            unlink($uploadfile);
            $picture = get_template_directory_uri()."/pictures/default-placeholder.png";
            $output = '{"id":"", "status":"error", "error":"006", "message":"Ocorreu um problema desconhecido, atualize sua página e tente novamente", "picture_id":"", "name":"", "picture":""}';
        }
    } else {
        unlink($uploadfile);
        $picture = get_template_directory_uri()."/pictures/default-placeholder.png";
        $output = '{"id":"", "status":"error", "error":"003", "message":"Nenhuma imagem foi carregada", "picture_id":"", "name":"", "picture":""}';
    }
    }
    echo $output;
    die;
}


// Display custom cart item meta data (in cart and checkout)
add_filter( 'woocommerce_get_item_data', 'display_cart_item_custom_meta_data', 10, 2 );
function display_cart_item_custom_meta_data( $item_data, $cart_item ) {
    $meta_key_PIC = 'Image';
    $meta_key_PDF = 'Pdf';
    if ( isset($cart_item['personalized_picture']) && isset($cart_item['personalized_picture'][$meta_key_PIC]) ) {
        $item_data[] = array(
            'key'       => $meta_key_PIC,
            'value'     => $cart_item['personalized_picture'][$meta_key_PIC],
        );
    }
    if ( isset($cart_item['personalized_pdf']) && isset($cart_item['personalized_pdf'][$meta_key_PDF]) ) {
        $item_data[] = array(
            'key'       => $meta_key_PDF,
            'value'     => $cart_item['personalized_pdf'][$meta_key_PDF],
        );
    }
    return $item_data;
}


// Save cart item custom meta as order item meta data and display it everywhere on orders and email notifications.
add_action( 'woocommerce_checkout_create_order_line_item', 'save_cart_item_custom_meta_as_order_item_meta', 10, 4 );
function save_cart_item_custom_meta_as_order_item_meta( $item, $cart_item_key, $values, $order ) {
    $meta_key_PIC = 'Image';
    $meta_key_PDF = 'Pdf';
    if ( isset($values['personalized_picture']) && isset($values['personalized_picture'][$meta_key_PIC]) ) {
        $item->update_meta_data( $meta_key_PIC, $values['personalized_picture'][$meta_key_PIC] );
    }
    if ( isset($values['personalized_pdf']) && isset($values['personalized_pdf'][$meta_key_PDF]) ) {
        $item->update_meta_data( $meta_key_PDF, $values['personalized_pdf'][$meta_key_PDF] );
    }
}



// Get price by product variation
function get_price_by_variation(int $product_id, array $variationArray, string $key) {
    $args = array(
        'post_type'     => 'product_variation',
        'post_status'   => array( 'private', 'publish' ),
        'numberposts'   => -1,
        'orderby'       => 'menu_order',
        'order'         => 'asc',
        'post_parent'   => $product_id
    );
    $data = "error";
    $variations = get_posts( $args );
    foreach ( $variations as $variation ) {
        // get variation ID
        $variation_ID = $variation->ID;

        // get variations meta
        $product_variation = new WC_Product_Variation( $variation_ID );

        if ($variationArray[0] == $product_variation->attributes['pa_taille']
            && $variationArray[1] == $product_variation->attributes['pa_support']) {

                // get variation price
                $variation_price = $product_variation->get_price();

                // get_post_meta( $variation_ID , '_text_field_date_expire', true );
                if ($key == "_price") {
                    $data = "error";
                    if (!empty($variation_price)) {
                        $data = $variation_price;
                    }
                } else if ($key == "_variation_id"){
                    $data = "error";
                    if (!empty($variation_ID)) {
                        $data = $variation_ID;
                    }
                }
        }
    }
    return $data;
} 


// Get price and variation id by the product id
function tabloide_get_price_and_variation_id() {
    $support = $_POST['tabloide-support-input'];
    $size = $_POST['tabloide-size-input'];
    $product_id = $_POST['tabloide-personalized-id'];
    $output = '{"status":"error", "message":""}';
    
    $price = get_price_by_variation($product_id, [$size, $support], "_price");
    $variation_id = get_price_by_variation($product_id, [$size, $support], "_variation_id");
    $output = '{"status":"success", "message":"", "price":"'.$price.'", "variation_id":"'.$variation_id.'"}';

    echo $output;
    die;
}

/**
 * Check picture uploaded
 */
function tabloide_add_to_cart_product_personalized(){
    $product_id = $_POST['tabloide-personalized-id'];
    $variation_id = $_POST["tabloide-personalized-attribute-id"];
    $quantity = $_POST['tabloide-personalized-quantity'];
    $personalized_id = $_POST['personalized_id'];
    $personalized_picture = $_POST['personalized_picture'];
    $position = "landscape";
    if ($_POST['tabloide-disposition-check']) {
        $position = "portrait";
    }
    $personalized_pdf = get_permalink( get_page_by_path( 'generate-personalized-pdf' ) )."?picture=$personalized_picture"."&position=$position";
    
    if (!empty($product_id) && !empty($variation_id) && !empty($quantity) && !empty($personalized_picture)) {
        if(WC()->cart->add_to_cart($product_id, $quantity, $variation_id, [
            'id' => $product_id
        ], [
            "personalized_id" => ['ID' => $personalized_id],
            "personalized_picture" => ['Image' => $personalized_picture],
            "personalized_pdf" => ['Pdf' => $personalized_pdf]
        ]
        )){
            $redirect = wc_get_cart_url();
            $output = '{"id":"'.$product_id.'", "status":"success", "message":"Sua pintura foi adicionada com sucesso ao seu carrinho", "redirect": "'.$redirect.'"}';
        } else {
            $output = '{"id":"'.$product_id.'", "status":"success", "message":"Sua pintura não pôde ser inserida em sua cesta, tente novamente", "redirect":""}';
        }
    } else {
        $output = '{"id":"'.$product_id.'", "status":"error", "message":"Ocorreu um erro inesperado, atualize sua página e tente novamente", "redirect":""}';
    }

    echo $output;
    die;
}


