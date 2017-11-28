<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
//payment from personal account
CModule::IncludeModule("catalog");
include_once(GetLangFileName(dirname(__FILE__)."/", "/payment.php"));
$form_url = CSalePaySystemAction::GetParamValue("TMG_PK_SERVER_ADDR");
$secret = CSalePaySystemAction::GetParamValue("TMG_PK_SECRET_KEY");
$total_sum = number_format(floatval($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["SHOULD_PAY"]), 2, ".", ""); 
$orderid = $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"];
$clientid = $GLOBALS["SALE_INPUT_PARAMS"]["PROPERTY"]["FIO"];
$client_email = $GLOBALS["SALE_INPUT_PARAMS"]["PROPERTY"]["EMAIL"];
$client_phone = $GLOBALS["SALE_INPUT_PARAMS"]["PROPERTY"]["PHONE"];
$service_name = "";
$cart = array();
$basket_item_params = array();
$tax = "none";
$tax_sum = 0;
$dbBasket = CSaleBasket::GetList(
    array("NAME" => "ASC"),
    array("ORDER_ID" => $orderid)
);
while($basket_item_params = $dbBasket->Fetch()) {
    $product_params = CCatalogProduct::GetById($basket_item_params["PRODUCT_ID"]);
    //DEBUG
    //var_dump($product_params);
    //echo "<br><br>";
    //var_dump($basket_item_params);
    $price = (float)$basket_item_params["PRICE"];
    $quantity = (int)$basket_item_params["QUANTITY"];
    $sum = $price*$quantity;
    if ( (int)$product_params["VAT_ID"] != 0) {
        //vat0
        if ( (int)$product_params["VAT_ID"] == 1)
            $tax = "vat0";
        else
            switch((float)$basket_item_params["VAT_RATE"]*100) {
                case 10:
                    $tax = "vat10";
                    $tax_sum = round((float)(($sum/110)*10), 2);
                    break;
                case 18:
                    $tax = "vat18";
                    $tax_sum = round((float)(($sum/118)*18), 2);
                    break;
            }
    }
    $name = $basket_item_params["NAME"];
    $name = str_replace("\n ", "", $name);
    $name = str_replace("\r ", "", $name);
    $name = str_replace(" ", "&nbsp;", $name);
    $cart[] = array(
        "name" => $name,
        "price" => number_format($price, 2, ".", ""),
        "quantity" => $quantity,
        "sum" => number_format($sum, 2, ".", ""),
        "tax" => $tax,
        "tax_sum" => number_format($tax_sum, 2, ".", "")
    );
    $tax = "none";
    $tax_sum = 0;
}
$order_params = CSaleOrder::GetById($orderid);
//DEBUG
//var_dump($order_params);
$dbSaleDelivery = CSaleDelivery::GetById($order_params["DELIVERY_ID"]);
//DEBUG
//var_dump($dbSaleDelivery);
$name = $dbSaleDelivery["NAME"];
$name = str_replace("\n ", "", $name);
$name = str_replace("\r ", "", $name);
$name = str_replace(" ", "&nbsp;", $name);
$price = number_format($dbSaleDelivery["PRICE"], 2, ".", "");
if ((int)$price > 0) {
    $cart[] = array(
        "name" => $name,
        "price" => $price,
        "quantity" => 1,
        "sum" => $price,
        "tax" => "vat18",
        "tax_sum" => round((float)(($price/118)*18), 2)
    );
}
$to_hash = $total_sum    .
           $clientid     .
           $orderid      .
           $service_name .
           $client_email .
           $client_phone .
           $secret;
$sign = hash ('sha256' , $to_hash);
//Encode cart to utf-8 for json_encode
$cart_encoded = array();
foreach ($cart as $product) {
    $product_ar = array();
    foreach ($product as $key => $value) {
        $enc = mb_detect_encoding($value, 'ASCII, UTF-8, windows-1251', false);
        $product_ar[$key] = ($enc == "UTF-8") ? $value : iconv($enc, "UTF-8", $value);
    }
    $cart_encoded[] = $product_ar;
}
//DEBUG
//var_dump($cart_encoded);
$cart = json_encode($cart_encoded);
//DEBUG
//var_dump($cart);
//var_dump(json_last_error());
//var_dump(json_last_error_msg());
//payment form language
switch (LANGUAGE_ID) {
    case "en":
        $pay_button = "Pay online";
        $message = "You will be redirected to bank payment gateway now.";
        $lang = "en";
        break;
    default:
        $pay_button = "Оплатить";
        $message = "Сейчас Вы будете перенаправлены на страницу банка.";
        $lang = "ru";
        break;
}
$pay_button = (LANG_CHARSET == "UTF-8") ? $pay_button : \
          iconv("UTF-8", LANG_CHARSET, $pay_button);
$form = '
    <form id="pay_form" action="' . $form_url . '" accept-charset="utf-8" method="post">
    <input type="hidden" name="sum" value = "'.$total_sum.'"/>
    <input type="hidden" name="orderid" value = "'.$orderid.'"/>
    <input type="hidden" name="clientid" value = "'.$clientid.'"/>
    <input type="hidden" name="client_email" value = "'.$client_email.'"/>
    <input type="hidden" name="client_phone" value = "'.$client_phone.'"/>
    <input type="hidden" name="service_name" value = "'.$service_name.'"/>
    <input type="hidden" name="cart" value = '.$cart.' />
    <input type="hidden" name="lang" value = '.$lang.' />
    <input type="hidden" name="sign" value = "'.$sign.'"/>
    <input type="submit" class="btn btn-default" value="'.$pay_button.'"/>
    </form>';
if (LANG_CHARSET != "utf-8" and $lang == "ru")
    $message = iconv("UTF-8", LANG_CHARSET, $message);
if ($form  == "")
  $form = "<h3>Произошла ошибка при инциализации платежа</h3><p>$err_num: ".htmlspecialchars($err_text)."</p>";
?>
<div id='tmg_pk_form_container'>
<?php echo "<h3>" . $message . "</h3>";?>
<br>
<?php echo $form;?>
</div>
<script type="text/javascript">
    window.onload=function(){
        setTimeout(fSubmit, 2000);
    }
    function fSubmit() {
        document.forms["pay_form"].submit();
    }
</script>
