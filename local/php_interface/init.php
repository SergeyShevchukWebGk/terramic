<?if (file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/settings_solo.php")){
    require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/settings_solo.php");}


include ($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/.config.php");

define('IBCLICK_CATALOG_ID', 23);  // основной каталог товаров
define('PRICE_TYPE_ID', 3);
define('PAY_SISTEM_NDS', 11); // оплата со счета с НДС
define('LOCATION_ID_1', 34);  // свйоство города физ лица
define('LOCATION_ID_2', 18);   // свйоство города юр лица
define('LOCATION_ID_3', 31);  // свйоство города ип
define('PERSON_TYPE_1', 1);  // тип плательщика 1
define('PERSON_TYPE_2', 2);  // тип плательщика 2
define('PERSON_TYPE_3', 3);  // тип плательщика 3
define('DELIVERY_STOCK', 3);  // самовывоз


define('DELIVERY_BAYKAL_1', 31);  // служба доставки байкал сервис
define('DELIVERY_BAYKAL_2', 28);  // служба доставки байкал сервис
define('DELIVERY_PEK_1', 29);  // служба доставки пэк
define('DELIVERY_PEK_2', 26);  // служба доставки пэк
define('DELIVERY_KIT_1', 30);  // служба доставки КИТ
define('DELIVERY_KIT_2', 27);  // служба доставки КИТ
define('DELIVERY_DL_1', 34);  // служба доставки Деловые линии
define('DELIVERY_DL_2', 35);  // служба доставки Деловые линии


// added by HTMLS.OrderCommentPlus - start
AddEventHandler("sale", "OnSaleComponentOrderOneStepComplete", "OrderCommentPlus");
AddEventHandler("sale", "OnSaleComponentOrderComplete", "OrderCommentPlus");
function OrderCommentPlus($ID, $arFields){
    if(CModule::IncludeModuleEx("htmls.ordercomment") < 3){
        COrderCommentPlus::BuildComment($ID, $arFields);
    }
}
//\\ added by HTMLS.OrderCommentPlus - start

function logger($data, $file) {
file_put_contents(
    $file,
    var_export($data, 1)."\n",
    FILE_APPEND
);
}

function arshow($t) {
    echo '<pre>';
    print_r($t);
    echo '</pre>';
}

function object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}
// AddEventHandler("sale", "OnSaleComponentOrderOneStepProcess", "OrderDelivery");


// //удаление стоимости доставки из заказа, с сохранением примерной стоимости( обработчи удаляет стоимость доставки из визуальной части корзины)
// \Bitrix\Main\EventManager::getInstance()->addEventHandler('sale','OnSaleComponentOrderResultPrepared','OrderDelivery');

// function OrderDelivery($order, $arUserResult, $request, &$arParams, &$arResult){

//     $arResult['ORDER_TOTAL_PRICE'] = $arResult['ORDER_TOTAL_PRICE'] - $arResult['DELIVERY_PRICE'];
//     $arResult['ORDER_TOTAL_PRICE_FORMATED'] = $arResult['ORDER_PRICE_FORMATED'];

// }

// //удаление стоимости доставки из заказа, с сохранением примерной стоимости( обработчи удаляет стоимость доставки из способов оплаты и из самого заказа, при этом сохраняет визуальное представления стомисоти)
// \Bitrix\Main\EventManager::getInstance()->addEventHandler('sale','OnSaleOrderBeforeSaved','myFunction');
// function myFunction(Main\Event $event)
// {
//     /** @var Order $order */
//     $order = $event->getParameter("ENTITY");
//     // $oldValues = $event->getParameter("VALUES");

//     $price = $order->getPrice();
//     $delivery = $order->getDeliveryPrice();
//     if( $delivery){
//         $price = $price - $delivery;
//     }
//     $order->setField('PRICE', $price);
//     $order->setField('BASE_PRICE_DELIVERY', 0);

//     $fields = $order->getAvailableFields();
//     $paymentCollection = $order->getPaymentCollection();
//     $sum = array();
//     foreach ($paymentCollection as $payment) {
//         if(($payment->getPaymentSystemId()!=9)&&($delivery>0)){
//             $payment->setField('SUM', ($payment->getSum())-$delivery);
//             $sum[ $payment->getPaymentSystemId()] = $payment->getSum(); // сумма к оплате
//         }
//     }

// }

 //удаление стоимости доставки из заказа, с сохранением примерной стоимости( обработчи удаляет стоимость доставки из способов оплаты и из самого заказа, при этом сохраняет визуальное представления стомисоти)
\Bitrix\Main\EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderBeforeSaved', 'myFunction');
function myFunction(\Bitrix\Main\Event $event)
{
    /** @var Order $order */
    $order = $event->getParameter("ENTITY");
    if ($order instanceof \Bitrix\Sale\Order) {
        $paymentCollection = $order->getPaymentCollection();
        foreach ($paymentCollection as $payment) {
            if (($payment->getPaymentSystemId() == 8) && intval($order->getId()) > 0) {
                ob_start();
                $_REQUEST["ORDER_ID"] = $order->getId();
                global $APPLICATION;
                $APPLICATION->IncludeComponent("bitrix:sale.order.payment", "", Array());
                $pdf_content = ob_get_contents();
                ob_clean();

                $fid = CFile::SaveFile(
                    array(
                        'name'      => 'bill_'.$order->getId().'.html',
                        'size'      => strlen($pdf_content),
                        'type'      => 'application/html',
                        'MODULE_ID' => 'sale',
                        'content'   => $pdf_content,
                    ),
                    'bills'
                );

                $propertyCollection = $order->getPropertyCollection();

                $emailPropValue = $propertyCollection->getUserEmail()->getValue();

                $event = new CEvent;
                $event->Send("SDK_BILL_ORDER_SEND", "s1", array("EMAIL_TO" => $emailPropValue), "N", "", array($fid));
            }
        }
    }
}
function export_city(){
    $apikey = array(
        "appkey" => "C7DCD1FA-235F-11E7-B703-00505683A6D3",  // ключ регистрации модуля
    );
    $data_string = json_encode($apikey);

    $ch = curl_init('https://api.dellin.ru/v2/public/terminals.json');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
     'Content-Type: application/json',
     'Content-Length: ' . strlen($data_string))
    );
    $filename = $_SERVER["DOCUMENT_ROOT"]. '/local/php_interface/include/city_base.txt';
    $result_terminal = object_to_array(json_decode(curl_exec($ch)));
    // Запись.
    $data = serialize($result_terminal);      // PHP формат сохраняемого значения.
    //$data = json_encode($bookshelf);  // JSON формат сохраняемого значения.
    file_put_contents($filename, $data);

    return "export_city();";
}


 
?>