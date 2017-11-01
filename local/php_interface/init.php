<?if (file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/settings_solo.php")){
	require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/settings_solo.php");}

// added by HTMLS.OrderCommentPlus - start
AddEventHandler("sale", "OnSaleComponentOrderOneStepComplete", "OrderCommentPlus");
AddEventHandler("sale", "OnSaleComponentOrderComplete", "OrderCommentPlus");
function OrderCommentPlus($ID, $arFields){
	if(CModule::IncludeModuleEx("htmls.ordercomment") < 3){
		COrderCommentPlus::BuildComment($ID, $arFields);
	}
}
//\\ added by HTMLS.OrderCommentPlus - start

function xprint($t) {
    echo '<pre>';
    print_r($t);
    echo '</pre>';
}
// AddEventHandler("sale", "OnSaleComponentOrderOneStepProcess", "OrderDelivery");


// //удаление стоимости доставки из заказа, с сохранением примерной стоимости( обработчи удаляет стоимость доставки из визуальной части корзины) 
// \Bitrix\Main\EventManager::getInstance()->addEventHandler('sale','OnSaleComponentOrderResultPrepared','OrderDelivery');

// function OrderDelivery($order, $arUserResult, $request, &$arParams, &$arResult){

// 	$arResult['ORDER_TOTAL_PRICE'] = $arResult['ORDER_TOTAL_PRICE'] - $arResult['DELIVERY_PRICE'];
// 	$arResult['ORDER_TOTAL_PRICE_FORMATED'] = $arResult['ORDER_PRICE_FORMATED'];

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
//     	$price = $price - $delivery;
//     }
//     $order->setField('PRICE', $price);
//     $order->setField('BASE_PRICE_DELIVERY', 0);

//     $fields = $order->getAvailableFields();
//     $paymentCollection = $order->getPaymentCollection();
// 	$sum = array();
// 	foreach ($paymentCollection as $payment) {
// 	    if(($payment->getPaymentSystemId()!=9)&&($delivery>0)){
// 	    	$payment->setField('SUM', ($payment->getSum())-$delivery);
// 	    	$sum[ $payment->getPaymentSystemId()] = $payment->getSum(); // сумма к оплате
// 	    }
// 	}

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


?>