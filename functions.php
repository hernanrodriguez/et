<?php

/* ---------------------------------------------------------------------------
 * Child Theme URI | DO NOT CHANGE
 * --------------------------------------------------------------------------- */
define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );


/* ---------------------------------------------------------------------------
 * Define | YOU CAN CHANGE THESE
 * --------------------------------------------------------------------------- */

// White Label --------------------------------------------
define( 'WHITE_LABEL', false );

// Static CSS is placed in Child Theme directory ----------
define( 'STATIC_IN_CHILD', false );


/* ---------------------------------------------------------------------------
 * Enqueue Style
 * --------------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'mfnch_enqueue_styles', 101 );
function mfnch_enqueue_styles() {
    
    // Enqueue the parent stylesheet
//  wp_enqueue_style( 'parent-style', get_template_directory_uri() .'/style.css' );     //we don't need this if it's empty
    
    // Enqueue the parent rtl stylesheet
    if ( is_rtl() ) {
        wp_enqueue_style( 'mfn-rtl', get_template_directory_uri() . '/rtl.css' );
    }
    
    // Enqueue the child stylesheet
    wp_dequeue_style( 'style' );
    wp_enqueue_style( 'style', get_stylesheet_directory_uri() .'/style.css' );
    
}


/* ---------------------------------------------------------------------------
 * Load Textdomain
 * --------------------------------------------------------------------------- */
add_action( 'after_setup_theme', 'mfnch_textdomain' );
function mfnch_textdomain() {
    load_child_theme_textdomain( 'betheme',  get_stylesheet_directory() . '/languages' );
    load_child_theme_textdomain( 'mfn-opts', get_stylesheet_directory() . '/languages' );
}


/* ---------------------------------------------------------------------------
 * Override theme functions
 * 
 * if you want to override theme functions use the example below
 * --------------------------------------------------------------------------- */
// require_once( get_stylesheet_directory() .'/includes/content-portfolio.php' );

function write_soaplog ( $log )  {
    if ( true === WP_DEBUG ) {
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }
}

add_filter('show_admin_bar', '__return_false');
add_filter( 'woocommerce_cart_needs_shipping_address', '__return_true', 50 );

add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
    if (is_user_logged_in() && $args->theme_location == 'social-menu') {
        $items .= '<li><a href="'. wp_logout_url() .'">SALIR</a></li>';
    }
    elseif (!is_user_logged_in() && $args->theme_location == 'social-menu') {
        $items .= '<li><a href="'. site_url('mi-cuenta') .'">ENTRAR</a></li><li><a class="signup" href="'. site_url('mi-cuenta') .'">REGÍSTRATE</a></li>';
    }
    return $items;
}
// add_action('um_after_user_is_approved', 'format_userdata', 99 );
// function format_userdata( $user_id ) {
//     global $wpdb;

//     class CurrentUser {
//         public $display_name;
//         public $email;
//         public $password;
//         public $nickname;
//         public $firstname;
//         public $lastname;
//         public $company;
//         public $address;
//         public $postalcode;
//         public $city;
//         public $county;
//         public $phonenumber;
//         public $mobilenumber;
//         public $dnicif;
//         public $country;
//         public $usercode;
//         public $paymentmethod;
//         public $grabadorapid;
//         public $trackingnumber;
//         public $createddate;
//         public $updated;
//     }

//     $currentUser = new CurrentUser();
//     $user = new WP_User( $user_id );
//  $currentUser->display_name = $user->display_name;
//     $currentUser->email = $user->user_email;
//     $currentUser->password = $user->user_pass;
//     $currentUser->company = "";
//     $currentUser->usercode = strval(70000+intval($user_id));
//     $currentUser->grabadorapid = "";
//     $currentUser->trackingnumber = 0;
//     $now = new DateTime();
//     $currentUser->updated = $now->getTimestamp();
//     $currentUser->paymentmethod = "";

//     $querystr = "SELECT * FROM et_usermeta WHERE user_id = $user_id";

//     $userdataset = $wpdb->get_results($querystr, OBJECT);

//     foreach ($userdataset as $key => $row) {
//         $metakey = $row->meta_key;
//         $metavalue = $row->meta_value;   

//         switch ($metakey) {
//             case 'nickname':
//                 $currentUser->nickname = $metavalue;
//                 break;
            
//             case 'first_name':
//                 $currentUser->firstname = $metavalue;
//                 break;

//             case 'last_name':
//                 $currentUser->lastname = $metavalue;
//                 break;

//             case 'direccion':
//                 $currentUser->address = $metavalue;
//                 break;

//             case 'codigo-postal':
//                 $currentUser->postalcode = $metavalue;
//                 break;

//             case 'ciudad':
//                 $currentUser->city = $metavalue;
//                 break;

//             case 'provincia':
//                 $currentUser->county = $metavalue;
//                 break;

//             case 'phone_number':
//                 $currentUser->phonenumber = $metavalue;
//                 break;

//             case 'mobile_number':
//                 $currentUser->mobilenumber = $metavalue;
//                 break;

//             case 'dni':
//                 $currentUser->dnicif = $metavalue;
//                 break;
//         }

//     };

//     $currentUser->country = "España";
//     write_log($currentUser);     

//     // $myfile = file_put_contents('logs.txt', $currentUser , FILE_APPEND | LOCK_EX);
//     send_userdata ( $currentUser );
// }
// function send_userdata ( $currentUser ) {
//     global $wpdb;
    
//     write_log ("send_userdata");

//     $tpvClientes = new wpdb('envios3color', 'Envios_2017', 'envios3color', 'www.enviostricolor.net');

//     $userCode = $currentUser->usercode;
//     $tpvClientes->insert ('tpv_clientes', array(
//         'COMPANIA' => $currentUser->company,
//         'DIR1' => $currentUser->address,
//         'DP' => $currentUser->postalcode,
//         'CIUDAD' => $currentUser->city,
//         'NOMPAIS' => $currentUser->country,
//         'CONTACTO1' => $currentUser->display_name,
//         'TEL' => $currentUser->phonenumber,
//         'MOVIL' => $currentUser->mobilenumber,
//         'EMAIL' => $currentUser->email,
//         'CONTASENA' => $currentUser->password,
//         'CODIGO' => $userCode,
//         'DNICIF' => $currentUser->dnicif,
//         'FORMAPAGO' => $currentUser->paymentmethod,
//         'GRABADORAPID' => $currentUser->grabadorapid,
//         'FECHAALTA' => date('Y-m-d'),
//         'NUMEROENVIO' => $currentUser->trackingnumber,
//         'ACTUALIZADO' => $currentUser->updated
//         ));

//     write_log ("Insert tpvClientes");
    
//     $rows = $tpvClientes->get_results("SELECT * FROM tpv_clientes");  
//     $resp = "";
//     foreach ($rows as $key => $obj) {
//         //$myfile = file_put_contents('logs.txt', $obj , FILE_APPEND | LOCK_EX);
//         $resp .= $obj->COMPANIA . " - ";
//         $resp .= $obj->DIR1 . " - ";
//         $resp .= $obj->DP . " - ";
//         $resp .= $obj->CIUDAD . " - ";
//         $resp .= $obj->NOMPAIS . " - ";
//         $resp .= $obj->CONTACTO1 . " - ";
//         $resp .= $obj->TEL . " - ";
//         $resp .= $obj->MOVIL . " - ";
//         $resp .= $obj->EMAIL . " - ";
//         $resp .= $obj->CONTASENA . " - ";
//         $resp .= $obj->CODIGO . " - ";
//         $resp .= $obj->DNICIF . " - ";
//         $resp .= $obj->FORMAPAGO . " - ";
//         $resp .= $obj->GRABADORAPID . " - ";
//         $resp .= $obj->FECHAALTA . " - ";
//         $resp .= $obj->NUMEROENVIO . " - ";
//         $resp .= $obj->ACTUALIZADO . " - ";
//     }
//     $myfile = file_put_contents('logs.txt', $resp , FILE_APPEND | LOCK_EX);

//     write_log ("Confirm tpvClientes");

//     send_userconfirmation( $userCode );
// }
// function send_userconfirmation ( $userCode ) {
//     write_log ("send_userconfirmation");

//     $url = "https://www.enviostricolor.net/altas.php?codigo=". $userCode;
//     #$data = array ('codigo' => urlencode($userCode));

//     $ch = curl_init( $url );
//     #curl_setopt( $ch, CURLOPT_POST, 1);
//     #curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
//     #curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
//     #curl_setopt( $ch, CURLOPT_HEADER, 0);
//     curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

//     $response = curl_exec( $ch );
//     $info = curl_getinfo( $ch );

//     write_log ($response);
//     if ($response === false || $info['http_code'] != 200) {
//       $response = "No cURL data returned for $url [". $info['http_code']. "]";
//       if (curl_error($ch))
//         $response .= "\n". curl_error($ch);
//       }
//     else {
//         $redirect = "http://www.enviostricolor.com/confirmregister";
//         wp_redirect($redirect);
//         exit;
//     };

    
// }

function disable_shipping_calc_on_cart( $show_shipping ) {
    if( is_cart() ) {
        return false;
    }
    return $show_shipping;
}
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99 );


add_action( 'woocommerce_before_add_to_cart_button', 'woocommerce_local_product_price', 31 );
function woocommerce_local_product_price() {
    global $woocommerce, $product;
    // let's setup our divs
    echo sprintf('<div id="product_picking_up" style="margin-bottom:20px;">%s</div>','<span class="picking_up">Recogida: 0€</span>');
    echo sprintf('<div id="product_subtotal_price" style="margin-bottom:20px;">%s</div>','<span class="subtotal">Subtotal: 0€</span>');
    echo sprintf('<div id="product_total_price" style="margin-bottom:20px;">%s</div>','<span class="price">Precio final: 12,00€</span>');
    ?>
        <script>
            jQuery(function($){
                
                $("input[type='number']").on('input',function(e){
                    if (($('[name=ancho]').val() > 0) && ($('[name=alto]').val() > 0) && ($('[name=largo]').val() > 0) && ($('[name=peso]').val() > 0) ) {
                        calculatePrice();
                    }
                });
                $("[name=origen]").on('change', function() {
                    calculatePrice();
                });

                function calculatePrice(){

                    document.cookie = "subtotal_price=;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
                    document.cookie = "peso=;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
                    var price = <?php echo $product->get_price(); ?>,
                        currency = '<?php echo get_woocommerce_currency_symbol(); ?>';
                    var now = new Date();
                    var time = now.getTime();
                    var expireTime = time + 1000*36000;
                    now.setTime(expireTime);
                    var origen = $('[name=origen]').val();

                    alto = parseFloat($('[name=alto]').val());
                    largo = parseFloat($('[name=largo]').val());
                    ancho = parseFloat($('[name=ancho]').val());
                    peso = parseFloat($('[name=peso]').val());

                    console.log (alto,largo,ancho,peso);

                    peso = Math.ceil(peso);
                    kgVol = Math.ceil(alto*ancho*largo/6000);


                    console.log (kgVol);

                    if (kgVol>peso) {
                        if (kgVol <= 1) product_subtotal = kgVol*12;
                        if ((kgVol > 1) && (kgVol <=10)) product_subtotal = kgVol*8;
                        if (kgVol > 10) product_subtotal = kgVol*7;
                    } else {
                        if (peso <= 1) product_subtotal = peso*12;
                        if ((peso > 1) && (peso <=10)) product_subtotal = peso*8;
                        if (peso > 10) product_subtotal = peso*7;
                    }

                    if ((peso<5) && (origen!=='Madrid')) {
                        $('#product_picking_up .picking_up').html( "Recogida: 5€");
                        document.cookie = "picking_up=5.00;path=/";
                        product_total = product_subtotal + 5;
                    } else {
                        $('#product_picking_up .picking_up').html( "Recogida: 0€");
                        document.cookie = "picking_up=0.00;path=/";
                        product_total = product_subtotal;
                    }

                    $('#product_subtotal_price .subtotal').html( "Subtotal: " + product_subtotal.toFixed(2) + currency);
                    $('#product_total_price .price').html( "Precio final: " + product_total.toFixed(2) + currency);
                    //$('.woocommerce-Price-amount.amount').html( currency + product_total.toFixed(2));

                    document.cookie = "subtotal_price=" + product_subtotal.toFixed(2) + ";path=/";
                    document.cookie = "peso=" + peso + ";expires=" + now.toGMTString() + ";path=/";
                }
            });
        </script>
    <?php
}

function woocommerce_total_product_price( $cart_object ) {
    if( !WC()->session->__isset( "reload_checkout" )) {
        $subtotal_price = $_COOKIE["subtotal_price"];
        foreach ( $cart_object->cart_contents as $key => $value ) {             
                if( isset( $value['data']->price ) ) {
                    //$orgPrice = floatval( $value['data']->get_price() );
                    $value['data']->set_price( $subtotal_price );
                }           
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'woocommerce_total_product_price', 99, 1 );

add_action('woocommerce_calculate_totals', 'calculate_totals', 10, 1);
function calculate_totals( $cart_object ){
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( !WC()->cart->is_empty() ):
        $recogidaFee = 0;
        $insuranceValue = 0;
        if( isset($_COOKIE["picking_up"]) ) {
            $recogidaFee = floatval($_COOKIE["picking_up"]);
        };
        if( isset($_COOKIE["insurance_value"]) ) {
            $insuranceValue = floatval($_COOKIE["insurance_value"]);
        };
        ## Displayed subtotal (+10%)
        // $cart_object->subtotal *= 1.1;

        ## Displayed TOTAL (+10%)
        // $cart_object->total *= 1.1;

        ## Displayed TOTAL CART CONTENT (+10%)
        $cart_object->cart_contents_total += $recogidaFee + $insuranceValue;

    endif;
}

add_filter( 'woocommerce_get_order_item_totals', 'et_get_order_item_totals', 10, 2 );
 
function et_get_order_item_totals( $total_rows, $order ) {

    $new_total_rows['cart_subtotal'] = $total_rows['cart_subtotal'];

    if (isset($_COOKIE["picking_up"])) {

        $recogida = floatval($_COOKIE["picking_up"]) .",00€";
        $new_total_rows['shipping'] = array (
            'label' => __( 'Recogida:', 'woocommerce' ),
            'value' => $recogida
            );
    }

    if (isset($_COOKIE["insurance_value"])) {

        $insuranceValue = $_COOKIE["insurance_value"];
        $insuranceValue = number_format((float)$insuranceValue, 2, ',', '') ."€";
        $new_total_rows['insurance'] = array (
            'label' => __( 'Seguro de mercancia:', 'woocommerce' ),
            'value' => $insuranceValue
            );
    }
     
    $new_total_rows['order_total'] = $total_rows['order_total'];

    return $new_total_rows;
}


add_action( 'woocommerce_cart_totals_before_order_total', 'et_review_cart_before_order_total');
function et_review_cart_before_order_total() {

    if (isset($_COOKIE["picking_up"])) {

        $recogida = floatval($_COOKIE["picking_up"]) .",00€";
        ?>
        <tr class="fee">
            <th>Recogida</th>
            <td><?php echo $recogida;?>
        </tr>
        <?php
    }

}
add_filter( 'woocommerce_review_order_before_order_total', 'et_review_order_before_order_total');
function et_review_order_before_order_total() {

    if (isset($_COOKIE["picking_up"])) {

        $recogida = floatval($_COOKIE["picking_up"]) .",00€";
        ?>
        <tr class="fee">
            <th>Recogida</th>
            <td><?php echo $recogida;?>
        </tr>
        <?php
    }
        ?>
        <script>
            jQuery(function($){
                var iValue = $('#shipping_insurance_value').val();
                console.log ("Paso por aquí");
                $('#insuranceValue').html("2,00€");
            });
        </script>

        <tr class="fee">
            <th>Seguro de mercancia</th>
            <td id="insuranceValue"></td>
        </tr>
        <?php

}

add_filter( 'woocommerce_package_rates', 'et_package_rates', 10, 2 );  
function et_package_rates( $rates, $package ) {
     
    $peso = floatval($_COOKIE["peso"]);
    if ( $peso < 5 ) {
        if ( isset( $rates['flat_rate:3'] ) ) unset( $rates['flat_rate:5'] );
    } elseif ( $peso >= 5 ) {
        if ( isset( $rates['flat_rate:5'] ) ) unset( $rates['flat_rate:3'] );
    }
    return $rates;   
}

function add_custom_validations( $passed ) { 
if ( $_REQUEST['alto'] == 0 ) {
    wc_add_notice( __( 'Por favor, completa el campo Alto del paquete.', 'woocommerce' ), 'error' );
    $passed = false;
}
if ( $_REQUEST['ancho'] == 0 ) {
    wc_add_notice( __( 'Por favor, completa el campo Ancho del paquete.', 'woocommerce' ), 'error' );
    $passed = false;
}
if ( $_REQUEST['largo'] == 0 ) {
    wc_add_notice( __( 'Por favor, completa el campo Largo del paquete.', 'woocommerce' ), 'error' );
    $passed = false;
}
if ( $_REQUEST['peso'] == 0 ) {
    wc_add_notice( __( 'Por favor, completa el campo Peso del paquete.', 'woocommerce' ), 'error' );
    $passed = false;
}
return $passed;
}
add_action( 'woocommerce_add_to_cart_validation', 'add_custom_validations', 10, 5 );  

function save_custom_product_fields( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['ancho'] ) ) {
        $cart_item_data[ 'ancho' ] = $_REQUEST['ancho'];
        /* below statement make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_action( 'woocommerce_add_cart_item_data', 'save_custom_product_fields', 10, 2 );




add_action('wp_ajax_wdm_add_user_custom_data_options', 'wdm_add_user_custom_data_options_callback');
add_action('wp_ajax_nopriv_wdm_add_user_custom_data_options', 'wdm_add_user_custom_data_options_callback');

function wdm_add_user_custom_data_options_callback()
{
      //Custom data - Sent Via AJAX post method
      $product_id = $_POST['id']; //This is product ID
      $user_custom_data_values =  $_POST['user_data']; //This is User custom value sent via AJAX
      session_start();
      $_SESSION['wdm_user_custom_data'] = $user_custom_data_values;
      die();
}
add_filter('woocommerce_add_cart_item_data','wdm_add_item_data',1,2);
 
if(!function_exists('wdm_add_item_data'))
{
    function wdm_add_item_data($cart_item_data,$product_id)
    {
        /*Here, We are adding item in WooCommerce session with, wdm_user_custom_data_value name*/
        global $woocommerce;
        session_start();    
        if (isset($_SESSION['wdm_user_custom_data'])) {
            $option = $_SESSION['wdm_user_custom_data'];       
            $new_value = array('wdm_user_custom_data_value' => $option);
        }
        if(empty($option))
            return $cart_item_data;
        else
        {    
            if(empty($cart_item_data))
                return $new_value;
            else
                return array_merge($cart_item_data,$new_value);
        }
        unset($_SESSION['wdm_user_custom_data']); 
        //Unset our custom session variable, as it is no longer needed.
    }
}

add_filter("woocommerce_checkout_fields", "order_fields");

function order_fields( $fields ) {

    unset($fields['billing']['billing_company']);
    unset($fields['shipping']['shipping_company']);

    $fields['billing']['billing_postcode']['class'] = array('form-row-last');
    $fields['billing']['billing_phone']['required'] = false;
    $fields['billing']['billing_phone']['class'] = array('form-row-first');
    $fields['billing']['billing_mobile_phone']['class'] = array('form-row-last');
    $fields['billing']['billing_email']['class'] = array('form-row-wide');

    $fields['shipping']['shipping_postcode']['class'] = array('form-row-first'); 
    $fields['shipping']['shipping_city']['class'] = array('form-row-last');
    $fields['shipping']['shipping_state']['class'] = array('form-row-wide');
   

    $fields['billing']['billing_dni'] = array(
        'label'         => __('DNI', 'woocommerce'),
        'placeholder'   => _x('DNI', 'placeholder', 'woocommerce'),
        'required'      => true,
        'class'         => array('form-row-first'),
        'clear'         => true
     );

    $fields['billing']['billing_email'] = array(
        'label'         => __('Correo electrónico', 'woocommerce'),
        'placeholder'   => _x('Correo electrónico', 'placeholder', 'woocommerce'),
        'required'      => false,
        'validate'      => array( 'email' ), 
        'class'         => array('form-row-wide'),
        'clear'         => true
     );

    $fields['shipping']['shipping_dni'] = array(
        'label'         => __('Cédula', 'woocommerce'),
        'placeholder'   => _x('Cédula', 'placeholder', 'woocommerce'),
        'required'      => true,
        'class'         => array('form-row-first'),
        'clear'         => true
     );

    $fields['billing']['billing_mobile_phone'] = array(
        'label'         => __('Teléfono móvil', 'woocommerce'),
        'placeholder'   => _x('Teléfono móvil', 'placeholder', 'woocommerce'),
        'type'          => 'tel',
        'validate'      => array( 'phone' ),        
        'required'      => true,
        'class'         => array('form-row-last'),
        'clear'         => true
     );    

    $fields['shipping']['shipping_mobile_phone'] = array(
        'label'         => __('Teléfono móvil', 'woocommerce'),
        'placeholder'   => _x('Teléfono móvil', 'placeholder', 'woocommerce'),
        'type'          => 'tel',
        'validate'      => array( 'phone' ),
        'required'      => true,
        'class'         => array('form-row-last'),
        'clear'         => true
     );      

    $fields['shipping']['shipping_email'] = array(
        'label'         => __('Correo electrónico', 'woocommerce'),
        'placeholder'   => _x('Correo electrónico', 'placeholder', 'woocommerce'),
        'type'          => 'email',
        'validate'      => array( 'email' ),
        'required'      => true,
        'class'         => array('form-row-wide'),
        'clear'         => true
     );     

    $fields['shipping']['shipping_phone'] = array(
        'label'         => __('Teléfono', 'woocommerce'),
        'placeholder'   => _x('Teléfono', 'placeholder', 'woocommerce'),
        'required'      => true,
        'class'         => array('form-row-first'),
        'clear'         => true
     );  

    $fields['billing']['billing_city'] = array(
        'label'         => __('Localidad/Ciudad', 'woocommerce'),
        'placeholder'   => _x('Localidad o Ciudad', 'placeholder', 'woocommerce'),
        'required'      => true,
        'class'         => array('form-row-first'),
        'clear'         => true
     );   

    $fields['shipping']['shipping_contact_person_name'] = array(
        'label'         => __('Persona de contacto', 'woocommerce'),
        'placeholder'   => _x('Nombre y apellidos', 'placeholder', 'woocommerce'),
        'required'      => true,
        'class'         => array('form-row-first'),
        'clear'         => true
     );            

    $fields['shipping']['shipping_contact_person_id'] = array(
        'label'         => __('Cédula persona de contacto', 'woocommerce'),
        'placeholder'   => _x('Cédula', 'placeholder', 'woocommerce'),
        'required'      => true,
        'type'          => 'number',
        'class'         => array('form-row-last'),
        'clear'         => true
     );     

    $fields['shipping']['shipping_country'] = array(
        'type'          => 'select',
        'label'         => __('País destino', 'woocommerce'),
        'placeholder'   => _x('Selecciona el país destino de la lista', 'placeholder', 'woocommerce'),
        'required'      => true,
        'options'       => array('VE' => 'Venezuela'),
        'class'         => array('form-row-wide'),
        'clear'         => true
    );

    // global $woocommerce;
    // $countries_obj   = new WC_Countries();
    // $countries   = $countries_obj->__get('countries');
    // //print_r ($countries);
    // $default_country = 'VE';
    // print_r($default_country);
    // $default_county_states = $countries_obj->get_states( $default_country );
    // print_r($default_county_states);
    // $fields['shipping']['shipping_state'] = array(
    //     'type'          => 'select',
    //     'label'         => __('Provincia destino', 'woocommerce'),
    //     'placeholder'   => _x('Selecciona la provincia destino de la lista', 'placeholder', 'woocommerce'),
    //     'required'      => true,
    //     'options'       => $default_county_states,
    //     'class'         => array('form-row-wide'),
    //     'clear'         => true
    // );
    $fields['shipping']['shipping_state'] = array(
        'type'          => 'text',
        'label'         => __('Provincia destino', 'woocommerce'),
        'placeholder'   => _x('Selecciona la provincia destino de la lista', 'placeholder', 'woocommerce'),
        'required'      => true,
        'class'         => array('form-row-wide'),
        'clear'         => true
    );

    $fields['billing']['billing_phone']['priority'] = 92;
    $fields['billing']['billing_mobile_phone']['priority'] = 93;
    $fields['billing']['billing_email']['priority'] = 94;
    $fields['billing']['billing_dni']['priority'] = 22;
    $fields['billing']['shipping_address_1']['maxlength'] = 70;
    $fields['billing']['shipping_address_2']['maxlength'] = 70;

    $fields['shipping']['shipping_first_name']['priority'] = 10;
    $fields['shipping']['shipping_last_name']['priority'] = 20;
    $fields['shipping']['shipping_dni']['priority'] = 30;
    $fields['shipping']['shipping_country']['priority'] = 35;
    $fields['shipping']['shipping_address_1']['priority'] = 50;
    $fields['shipping']['shipping_address_1']['maxlength'] = 70;
    $fields['shipping']['shipping_address_2']['priority'] = 60;
    $fields['shipping']['shipping_address_2']['maxlength'] = 70;
    $fields['shipping']['shipping_postcode']['priority'] = 65;
    $fields['shipping']['shipping_city']['priority'] = 70;
    $fields['shipping']['shipping_state']['priority'] = 90;    
    $fields['shipping']['shipping_phone']['priority'] = 92;
    $fields['shipping']['shipping_mobile_phone']['priority'] = 93;
    $fields['shipping']['shipping_email']['priority'] = 94;    
    $fields['shipping']['shipping_contact_person_name']['priority'] = 97;
    $fields['shipping']['shipping_contact_person_id']['priority'] = 98;  

    return $fields;

}

// save the extra field when checkout is processed
function save_extra_checkout_fields( $order_id ){
    // don't forget appropriate sanitization if you are using a different field type
    if( isset($_COOKIE["peso"]) ) {
        update_post_meta( $order_id, 'Peso mercancia', sanitize_text_field( $_COOKIE["peso"] ) );
    }
    if ( $_POST['pickup_time_from'] ) {
        update_post_meta( $order_id, 'Hora recogida desde', sanitize_text_field( $_POST['pickup_time_from'] ) );
    }
    if ( $_POST['pickup_time_to'] ) {
        update_post_meta( $order_id, 'Hora recogida hasta', sanitize_text_field( $_POST['pickup_time_to'] ) );
    }
    if ($_POST['billing_dni']) {
        update_post_meta( $order_id, 'DNI cliente', sanitize_text_field( $_POST['billing_dni'] ) );
    }
    if ( $_POST['billing_city'] ) {
        update_post_meta( $order_id, 'Ciudad cliente', sanitize_text_field( $_POST['billing_city'] ) );
    }
    if ( $_POST['billing_mobile_phone'] ) {
        update_post_meta( $order_id, 'Móvil cliente', sanitize_text_field( $_POST['billing_mobile_phone'] ) );
    }
    if ( $_POST['shipping_value'] ) {
        update_post_meta( $order_id, 'Valor de la mercancia', sanitize_text_field( $_POST['shipping_value'] ) );
    }
    if ( $_POST['shipping_insurance'] ) {
        update_post_meta( $order_id, 'Asegurar mercancia', sanitize_text_field( $_POST['shipping_insurance'] ) );
    }
    if ( $_POST['shipping_insurance_value'] ) {
        update_post_meta( $order_id, 'Valor del seguro de la mercancia', sanitize_text_field( $_POST['shipping_insurance_value'] ) );
    }
    if ( $_POST['shipping_dni'] ) {
        update_post_meta( $order_id, 'Cédula destino', sanitize_text_field( $_POST['shipping_dni'] ) );
    }
    if ( $_POST['shipping_mobile_phone'] ) {
        update_post_meta( $order_id, 'Móvil destino', sanitize_text_field( $_POST['shipping_mobile_phone'] ) );
    }
    if ( $_POST['shipping_email'] ) {
        update_post_meta( $order_id, 'Email destino', sanitize_text_field( $_POST['shipping_email'] ) );
    }
    if ( $_POST['shipping_phone'] ) {
        update_post_meta( $order_id, 'Teléfono destino', sanitize_text_field( $_POST['shipping_phone'] ) );
    }
    if ( $_POST['shipping_contact_person_name'] ) {
        update_post_meta( $order_id, 'Persona contacto', sanitize_text_field( $_POST['shipping_contact_person_name'] ) );
    }
    if ( $_POST['shipping_contact_person_id'] ) {
        update_post_meta( $order_id, 'Cédula persona contacto', sanitize_text_field( $_POST['shipping_contact_person_id'] ) );
    }
    if ( $_POST['shipping_description'] ) {
        update_post_meta( $order_id, 'Descripción mercancia', sanitize_text_field( $_POST['shipping_description'] ) );
    }
    if ( $_POST['shipping_notes'] ) {
        update_post_meta( $order_id, 'Notas del envío', sanitize_text_field( $_POST['shipping_notes'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'save_extra_checkout_fields', 10, 1 );

add_filter( 'default_checkout_billing_country', 'change_default_checkout_billing_country' );
function change_default_checkout_billing_country() {
  return 'ES'; // country code
}

// Ship to a different address opened by default
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_true' );

function woocommerce_button_proceed_to_checkout() {
       $checkout_url = WC()->cart->get_checkout_url();
       ?>
       <a href="<?php echo $checkout_url; ?>" class="checkout-button button alt wc-forward"><?php _e( 'Confirmar Envío', 'woocommerce' ); ?></a>
       <?php
     }

add_action('woocommerce_multistep_checkout_after_shipping', 'add_shipping_detail_custom_fields');
function add_shipping_detail_custom_fields( $checkout ) {
  wc_get_template( 'checkout/shipping-details.php', array( 'checkout' => $checkout ) );
}

add_filter( 'woocommerce_get_price_html', 'price_prefix_suffix', 100, 2 );
function price_prefix_suffix( $price, $product ){
  
    if (has_term('Paquete', 'product_cat')) {
        $price = 'Desde ' . $price;
    }  
    return apply_filters( 'woocommerce_get_price', $price );
}

add_action( 'woocommerce_before_checkout_shipping_form', 'before_shipping');
function before_shipping( $checkout ) {

    // check the user credentials here and if the condition is met set the shipping method

    WC()->session->set('chosen_shipping_methods', array( 'aereo' ) );

}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_product_thumbnails', 'custom_single_product_short_description', 40 );
function custom_single_product_short_description(){
    the_excerpt();
}

function getLockerCode() {
    $length = 6;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

function et_save_extra_register_fields( $customer_id ) {
    $locker_code = getLockerCode();
    echo $locker_code;
    update_user_meta( $customer_id, 'billing_locker', sanitize_text_field( $locker_code ) );
}
add_action( 'woocommerce_created_customer', 'et_save_extra_register_fields',10,1 );

add_action( 'show_user_profile', 'et_user_profile_fields' );
add_action( 'edit_user_profile', 'et_user_profile_fields' );

function et_user_profile_fields( $user ) { ?>
    <h3><?php _e("Número de casillero", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="casillero"><?php _e("Casillero"); ?></label></th>
        <td>
            <input type="text" name="casillero" id="casillero" value="<?php echo esc_attr( get_the_author_meta( 'billing_locker', $user->ID ) ); ?>" class="regular-text" /><br />
        </td>
    </tr>
    </table>
<?php }

function et_show_locker_number( $userid ) {
    global $locker_number;
    $locker_number = get_the_author_meta( 'billing_locker', $userid );
    echo $locker_number;
}

add_action( 'personal_options_update', 'et_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'et_save_extra_profile_fields' );
function et_save_extra_profile_fields( $user_id ) {

    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
    update_usermeta( $user_id, 'billing_locker', $_POST['casillero'] );
}

add_action( 'woocommerce_edit_account_form', 'et_woocommerce_edit_account_form' );
function et_woocommerce_edit_account_form() {
 
  $user_id = get_current_user_id();
 
  $locker = get_user_meta( $user_id, 'billing_locker', true );
 
  ?>
 
  <fieldset>
    <p class="form-row form-row-thirds">
      <label for="locker">Número de casillero:</label>
      <input type="text" readonly name="casillero" value="<?php echo esc_attr( $locker ); ?>" class="input-text" />
    </p>
  </fieldset>
 
  <?php
 
}

add_filter('woocommerce_states', 'et_custom_states');
function et_custom_states( $states ) {
 
    $exclude = array('TF','PM','CE','ML','GC');
 
    foreach( $exclude as $item ) {
        unset($states['ES'][$item]);
    }
 
    return $states;
}

add_filter( 'wpo_wcpdf_paper_orientation', 'wcpdf_landscape', 10, 2 );
function wcpdf_landscape($paper_orientation, $template_type) {
    if ($template_type == 'shippinglabel') {
        $paper_orientation = 'landscape';
    }
    return $paper_orientation;
}

add_filter( 'wpo_wcpdf_paper_format', 'wcpdf_a5_packing_slips', 10, 2 );
function wcpdf_a5_packing_slips($paper_format, $template_type) {
    if ($template_type == 'shippinglabel') {
        $paper_format = 'a6';
    }
 
    return $paper_format;
}

// Envios a Cacesa
add_filter( "bulk_actions-edit-shop_order", function($actions) {
    $actions['send_to_cacesa'] = __( 'Enviar a Cacesa', 'send_to_cacesa');
    return $actions;
}, 9 );
add_action( 'woocommerce_order_actions', 'et_action_send_order_cacesa' ); 
function et_action_send_order_cacesa($actions) {
    $actions['send_to_cacesa'] = __( 'Enviar a Cacesa', 'send_to_cacesa');
    return $actions;
}

function obj2array($obj) {
  $out = array();
  foreach ($obj as $key => $val) {
    switch(true) {
        case is_object($val):
         $out[$key] = obj2array($val);
         break;
      case is_array($val):
         $out[$key] = obj2array($val);
         break;
      default:
        $out[$key] = $val;
    }
  }
  return $out;
}

add_filter( 'admin_action_send_to_cacesa', 'et_action_send_to_cacesa', 10, 3 );
function et_action_send_to_cacesa( $redirect_to ) {
    // if ( 'send_to_cacesa' === $action_name ) {
    //     foreach ($post_ids as $post_id) {
    //         $post = get_post($post_id);
    //     }
    //     $redirect_to = add_query_arg( 'bulk_posts_processed', count( $post_ids ), $redirect_to );
    //     return $redirect_to;
    // }
    // return $redirect_to;

    $response = "En et_action_send_to_cacesa";
    $myfile = file_put_contents('logs.txt', $response , FILE_APPEND | LOCK_EX);

    if( !isset( $_REQUEST['post'] ) && !is_array( $_REQUEST['post'] ) )
        return;

    foreach ( $_REQUEST['post'] as $order_id) {

        $usercacesa = "tricolor";
        $pwdcacesa = "p45dd33";
        $service = "http://tws1.cacesa.com/wstest/wscacesa.asmx?WSDL";

        $ns = "http://tws1.cacesa.com/wsTest/";
        $headerbody = array (
            'User' => $usercacesa,
            'Password' => $pwdcacesa );
        $header = new SoapHeader( $ns, 'Authentication', $headerbody );

        $client = new SoapClient( $service );
        $client->__setSoapHeaders( $header );

        $response = $client->isAuthenticated();

        if( $response->isAuthenticatedResult ) {
            $shParams = getOrderData( $order_id );
            $response = $client->InsertShipment( $shParams );
        } else {
            Mensaje de error
        }

        $order_note = "Datos enviados a Cacesa" . serialize($response);
        $order->update_status( 'sent_cacesa', $order_note, true );
    }

    $redirect_to = add_query_arg( 'bulk_posts_processed', count( $_REQUEST['post'] ), $redirect_to );
    return $redirect_to;
}

function getOrderData( $order_id ) {

        $order = new WC_Order( $order_id );

        $order_data = $order->get_data();

        $order_id = $order_data['id'];
        $order_status = $order_data['status'];

        $order_number = '';
        $order_origin = 'MAD';
        $order_destination = 'CCS'
        $order_shname = $order_data['billing_first_name'] . $order_data['billing_last_name'];
        $order_shaddr1 = $order_data['billing_address_1'];
        $order_shaddr2 = $order_data['billing_address_2'];
        $order_shtown = $order_data['billing_city'];
        $order_shcp = $order_data['billing_postcode'];
        $order_shprov = '28';
        $order_shcountry = 'ES';
        $order_shtaxno = $order_data['billing_dni'];
        $order_shcontact = $order_data['billinging_first_name'] . $order_data['billing_last_name'];
        $order_shtel = $order_data['billing_phone'];
        $order_shmobile = $order_data['billing_mobile_phone'];
        $order_shmail = $order_data['billing_email'];
        $order_shfax = '';
        $order_shref = $order_data[''];
        $order_conname = $order_data['shipping_first_name'] . $order_data['shipping_last_name'];
        $order_conaddr1 = $order_data['shipping_address_1'];
        $order_conaddr2 = $order_data['shipping_address_2'];
        $order_contown = $order_data['shipping_city'];
        $order_concp = $order_data['shipping_postcode'];
        $order_conprov = $order_data['shipping_postcode'];
        $order_concountry = 'VE';
        $order_contaxno = $order_data['shipping_dni'];
        $order_concontact = $order_data['shipping_contact_person_name'];
        $order_contel = $order_data['shipping_phone'];
        $order_conmobile = $order_data['shipping_mobile_phone'];
        $order_conmail = $order_data['shipping_email'];
        $order_confax = '';
        $order_conref = '';
        $order_congooddesc = $order_data['shipping_description'];
        $order_packages = $order_data['shipping_p'];
        $order_weight = $order_data[''];
        $order_col = $order_data[''];
        $order_deliverydesc = $order_data['shipping_notes'];
        $order_declval = $order_data[''];
        $order_servtype = $order_data[''];
        $order_currency = 'EUR';
        $order_shiptype = 'DTD';
        $order_incoterm = 'FCA';
        $order_assured = $order_data[''];
        $order_assuredvalue = $order_data[''];
        $order_dimensions = array( 'dimensions' => array(
                            'Packages' => $order_data[''],
                            'Weight' => $order_data[''],
                            'Length' => $order_data[''],
                            'Width' => $order_data[''],
                            'Height' => $order_data[''] ));
        $order_packtype = 'SPX';
        $order_resulttype = "XML";

}

function register_sent_cacesa_order_status() {
    register_post_status( 'wc-sent_cacesa', array(
        'label'                     => 'Enviado a Cacesa',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Enviado a Cacesa <span class="count">(%s)</span>', 'Enviado a Cacesa <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_sent_cacesa_order_status' );

// Add to list of WC Order statuses
function add_sent_cacesa_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-sent_cacesa'] = 'Enviado a Cacesa';
        }
    }
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_sent_cacesa_to_order_statuses' );

function et_order_status_styling() {
    ?>
    <style>
    /* Sent to Cacesa */
    mark.sent_cacesa::after {
        content: '\e011';
        color: #ff9800;

        font-family: WooCommerce;
        speak: none;
        font-weight: 400;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        margin: 0;
        text-indent: 0;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        text-align: center;
    }
    </style>
    <?php
}
add_action('admin_head', 'et_order_status_styling');
