<?if (file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/settings_solo.php")){
    require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/settings_solo.php");}


include ($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/.config.php");

define('IBCLICK_CATALOG_ID', 23);  // основной каталог товаров
define('PRICE_TYPE_ID', 3);
define('PAY_SISTEM_NDS', 11); // оплата со счета с НДС
define('PAY_SISTEM_CARD', 10); // оплата картой
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
define('STOCK_LOCATION_ID', 4592);  // склад в краснодаре
define('STOCK_LOCATION_ID_2', 4593);  // склад в самаре
define('SECTION_ID_FILM', 151);  // id раздела с пленкой
define('ELEMENT_ID_SPOOL', 1064);  // id товара шпуля
define('PROP_FOR_TERRAMIC', 732);  // id товара шпуля


define('NEW_ORDER_STATUS_INDIVIDUAL_CARD_PAY', 'a');  // статус для новых заказов физлиц оплачивающих картой
define('ORDER_STATUS_PAY_PROCESSING', 'G');  // статус для новых заказов физлиц оплачивающих картой





// added by HTMLS.OrderCommentPlus - start
AddEventHandler("sale", "OnSaleComponentOrderOneStepComplete", "OrderCommentPlus");
AddEventHandler("sale", "OnSaleComponentOrderComplete", "OrderCommentPlus");
function OrderCommentPlus($ID, $arFields){
    if(CModule::IncludeModuleEx("htmls.ordercomment") < 3){
        COrderCommentPlus::BuildComment($ID, $arFields);
    }
}
//\\ added by HTMLS.OrderCommentPlus - start

AddEventHandler("sale", "OnBeforeOrderAdd", "newOrderStatusIndividual"); //простановка статуса "A" для новых неоплаченых заказов физлиц
AddEventHandler("sale", "OnSalePayOrder", "orderStausChangeIndividualCardPay"); //простановка статуса "G" для оплаченых заказов физлиц с карты
function newOrderStatusIndividual(&$arFields){ //простановка статуса "A" для новых неоплаченых заказов физлиц
    if ($arFields['PERSON_TYPE_ID'] == PERSON_TYPE_1 && $arFields['PAY_SYSTEM_ID'] == PAY_SISTEM_CARD) {
        $arFields["STATUS_ID"] = NEW_ORDER_STATUS_INDIVIDUAL_CARD_PAY;
    }

}

function orderStausChangeIndividualCardPay($ID, $val) { //простановка статуса "G" для оплаченых заказов физлиц с карты
    if ($val == "Y") {
        $arOrder = Array("ID"=>"DESC");
        $arFilter = Array("ID" => $ID, "PERSON_TYPE_ID" => PERSON_TYPE_1, 'PAY_SYSTEM_ID' => PAY_SISTEM_CARD, "STATUS_ID" => NEW_ORDER_STATUS_INDIVIDUAL_CARD_PAY, 'PAYED' => "Y");

        $db_sale = CSaleOrder::GetList($arOrder, $arFilter);
        if ($ar_sale = $db_sale->Fetch()) {
            CSaleOrder::StatusOrder($ID, ORDER_STATUS_PAY_PROCESSING);
        }
    }
}

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
  function getOrderBillPdf($orderId){
   $payment = null;
   CModule::IncludeModule("sale");
   if(($order = \Bitrix\Sale\Order::load($orderId))
      && ($paymentCollection = $order->getPaymentCollection())
   ){
      foreach($paymentCollection as $p)
         if(!$p->isInner()){
            $payment = $p;
            break;
         }
   }
   
   if($payment
      && ($service = \Bitrix\Sale\PaySystem\Manager::getObjectById($payment->getPaymentSystemId()))
      && $service->isAffordPdf()
   ){
      $context = \Bitrix\Main\Application::getInstance()->getContext();
      $_REQUEST['pdf'] = 
      $_REQUEST['GET_CONTENT'] = 'Y';
      if(($res = $service->initiatePay($payment,$context->getRequest(),\Bitrix\Sale\PaySystem\BaseServiceHandler::STRING))
         && $res->isSuccess()
      ){
     //    return file_put_contents($pdfPath,$res->getTemplate());
      }
   }
}

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
               /* ob_start();
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
                );  */
                getOrderBillPdf($order->getId());

                $fid = $_SERVER["DOCUMENT_ROOT"].'/upload/order_'.$order->getId().'_1.pdf'; 
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

// разделяем товар раздела пленки и добавляем к ней шпулю
AddEventHandler("sale", "OnBeforeBasketAdd", "MontageBasketAdd");

function MontageBasketAdd(&$arFields) {
    // Выведем актуальную корзину для текущего пользователя
    //logger($arFields, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');        
        $res = CIBlockElement::GetByID($arFields["PRODUCT_ID"]);
        $getProp = CIBlockElement::GetProperty(
          IBCLICK_CATALOG_ID,
          $arFields["PRODUCT_ID"],
          Array("sort"=>"asc"),
          Array('CODE' => 'RAZMOTKA')
        );
        if($spoolProps = $getProp -> GetNext()){
            $getSpoolProps['VALUE'] = $spoolProps['VALUE'];             
        }
        logger($getSpoolProps, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt'); 
        if($ar_res = $res->GetNext()){
            if($ar_res["IBLOCK_SECTION_ID"] == SECTION_ID_FILM && $getSpoolProps['VALUE'] == PROP_FOR_TERRAMIC){                
                     $arProps = array(
                        "NAME" => 'Элемент товара',
                        "CODE" => "id_product",          
                        "VALUE" => $arFields["PRODUCT_ID"].rand(0,1000),
                      );
                      $arFields["PROPS"][] = $arProps;
                    $ratio = CCatalogMeasureRatio::getList(Array(), array('IBLOCK_ID'=>$arFields["IBLOCK_ID"], 'PRODUCT_ID'=>$arFields["PRODUCT_ID"]), false, false, array());
                    $ar_fields = $ratio->Fetch();                       
                    $prop_element = CIBlockElement::GetByID(ELEMENT_ID_SPOOL)->GetNext();  // берем необходимые данные из элемента шпуля
                    $rsPrices = CPrice::GetList(array(), array( 'PRODUCT_ID' => ELEMENT_ID_SPOOL,'CATALOG_GROUP_ID' => 3));
                    if ($arPrice = $rsPrices->Fetch()) {
                            $arField = array(
                            "PRODUCT_ID" => $prop_element["ID"],
                            "PRICE" => $arPrice["PRICE"],
                            "CURRENCY" => "RUB",
                            "QUANTITY" => 1,
                            "LID" => 's1',
                            "FUSER_ID" => $arFields["FUSER_ID"],
                            "WEIGHT" => 100,
                            "MODULE" => $arFields["MODULE"],
                            "CAN_BUY" => $arPrice["CAN_BUY"],
                            "NAME" => $prop_element["NAME"],
                            "PRODUCT_PROVIDER_CLASS" => $arFields["PRODUCT_PROVIDER_CLASS"],
                            'CATALOG_XML_ID' => '1689073f-67dc-4d78-ab3e-a6c2a9c69d6b',
                            'PRODUCT_XML_ID' => '6112f99d-c607-11e7-80e2-14dae9ec1402',
                          );        
                          $arField["PROPS"][] = array("NAME" => "ID", "VALUE" => $arFields["ID"]);
                         /* $arField["PROPS"][] =  array (
                              'NAME' => 'Catalog XML_ID',
                              'CODE' => 'CATALOG.XML_ID',
                              'VALUE' => '1689073f-67dc-4d78-ab3e-a6c2a9c69d6b',
                            );
                          $arField["PROPS"][] = array (
                              'NAME' => 'Product XML_ID',
                              'CODE' => 'PRODUCT.XML_ID',
                              'VALUE' => '6112f99d-c607-11e7-80e2-14dae9ec1402',
                            ); */
                           
                          if($arField["PRODUCT_ID"] != ELEMENT_ID_SPOOL && $arFields["QUANTITY"] > $ar_fields["RATIO"] * 10){ // проверяем коэффициент количества пленки с метрожом дя добавления
                            $id = CSaleBasket::Add($arField); // добавляем шпулю в корзине с привязкой по id элемента пленки 
                            //$id = Add2BasketByProductID($arField["PRODUCT_ID"], 1, array("NAME" => "ID", "VALUE" => $arFields["ID"]));
                          }
                    }
            } else {
                $ar_section = CIBlockSection::GetByID($ar_res["IBLOCK_SECTION_ID"]);
                if($section = $ar_section->GetNext()){
                    if($section["IBLOCK_SECTION_ID"] == SECTION_ID_FILM && $getSpoolProps['VALUE'] == PROP_FOR_TERRAMIC){
                      $arProps = array(
                        "NAME" => '"Элемент товара',
                        "CODE" => "id_product",          
                        "VALUE" => $arFields["PRODUCT_ID"].rand(0,1000)
                      );
                      $arFields["PROPS"][] = $arProps; 
                        $ratio = CCatalogMeasureRatio::getList(Array(), array('IBLOCK_ID'=>$arFields["IBLOCK_ID"], 'PRODUCT_ID'=>$arFields["PRODUCT_ID"]), false, false, array());
                        $ar_fields = $ratio->Fetch();
                        
                        $prop_element = CIBlockElement::GetByID(ELEMENT_ID_SPOOL)->GetNext();
                        $rsPrices = CPrice::GetList(array(), array( 'PRODUCT_ID' => ELEMENT_ID_SPOOL,'CATALOG_GROUP_ID' => 3));
                        if ($arPrice = $rsPrices->Fetch()) {
                                $arField = array(
                                "PRODUCT_ID" => $prop_element["ID"],
                                "PRICE" => $arPrice["PRICE"],
                                "CURRENCY" => "RUB",
                                "QUANTITY" => 1,
                                "LID" => 's1',
                                "FUSER_ID" => $arFields["FUSER_ID"],
                                "WEIGHT" => 100,
                                "MODULE" => $arFields["MODULE"],
                                "CAN_BUY" => $arPrice["CAN_BUY"],
                                "NAME" => $prop_element["NAME"],
                                "PRODUCT_PROVIDER_CLASS" => $arFields["PRODUCT_PROVIDER_CLASS"],
                              );
                              $arField["PROPS"][] = array("NAME" => "ID", "VALUE" => $arFields["ID"]); 
                              if($arFields["PRODUCT_ID"] != ELEMENT_ID_SPOOL && $arFields["QUANTITY"] > $ar_fields["RATIO"] * 10){
                                $id = CSaleBasket::Add($arField);  
                              } 
                        }  
                    }
                }
  
            }
        }
        
    }                                                    
// добааление гильзы при изменении количества товара пленки   
AddEventHandler("sale", "OnBasketUpdate", "MontageBasketUpdate");

function MontageBasketUpdate($ID, &$arFields){
        global $USER;

        if(!is_int($arFields["ORDER_ID"])){ // если заказа еще не создан
            $res = CIBlockElement::GetByID($arFields["PRODUCT_ID"]);
             $getProp = CIBlockElement::GetProperty(
                  IBCLICK_CATALOG_ID,
                  $arFields["PRODUCT_ID"],
                  Array("sort"=>"asc"),
                  Array('CODE' => 'RAZMOTKA')
              );
            if($spoolProps = $getProp -> GetNext()){
                $getSpoolProps['VALUE'] = $spoolProps['VALUE'];             
            }
            if($ar_res = $res->GetNext()){
                if($ar_res["IBLOCK_SECTION_ID"] == SECTION_ID_FILM && $getSpoolProps['VALUE'] == PROP_FOR_TERRAMIC){
                        $rsPrices = CPrice::GetList(array(), array( 'PRODUCT_ID' => $ar_res["ID"],'CATALOG_GROUP_ID' => 3));
                        if ($arPrice = $rsPrices->Fetch()) {
                            $ar_res = CCatalogProduct::GetByID($arFields["PRODUCT_ID"]);
                            $ratio = CCatalogMeasureRatio::getList(Array(), array('IBLOCK_ID'=>$arFields["IBLOCK_ID"], 'PRODUCT_ID'=>$arFields["PRODUCT_ID"]), false, false, array());
                            $ar_fields = $ratio->Fetch();
                            $prop_element = CIBlockElement::GetByID(ELEMENT_ID_SPOOL)->GetNext();
                            $summ_quantity = $arFields["QUANTITY"] / $ar_fields["RATIO"];
                                $quantity_text = "N";
                                                                                       
                                if($arFields["QUANTITY"] >= $ar_fields["RATIO"] * 10 ){                                    
                                    $arField = array(
                                    "PRODUCT_ID" => $prop_element["ID"],
                                    "PRICE" => $arPrice["PRICE"],
                                    "CURRENCY" => "RUB",
                                    "QUANTITY" => 1,
                                    "LID" => 's1',
                                    "FUSER_ID" => $arFields["FUSER_ID"],
                                    "WEIGHT" => 100,
                                    "MODULE" => $arFields["MODULE"],
                                    "CAN_BUY" => $arPrice["CAN_BUY"],
                                    "NAME" => $prop_element["NAME"],
                                    "PRODUCT_PROVIDER_CLASS" => $arFields["PRODUCT_PROVIDER_CLASS"],
                                    'CATALOG_XML_ID' => '1689073f-67dc-4d78-ab3e-a6c2a9c69d6b',
                                    'PRODUCT_XML_ID' => '6112f99d-c607-11e7-80e2-14dae9ec1402',
                                  );
                                  $arField["PROPS"][] = array("NAME" => "ID", "VALUE" => $arFields["ID"]);
                                  /*$arField["PROPS"][] =  array (
                                          'NAME' => 'Catalog XML_ID',
                                          'CODE' => 'CATALOG.XML_ID',
                                          'VALUE' => '1689073f-67dc-4d78-ab3e-a6c2a9c69d6b',
                                        );
                                      $arField["PROPS"][] = array (
                                          'NAME' => 'Product XML_ID',
                                          'CODE' => 'PRODUCT.XML_ID',
                                          'VALUE' => '6112f99d-c607-11e7-80e2-14dae9ec1402',
                                        ); */ 
                                   //logger($arField, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
                                  if($arFields["PRODUCT_ID"] != ELEMENT_ID_SPOOL ){
                                    $id = CSaleBasket::Add($arField);
                                  //$id = Add2BasketByProductID($arField["PRODUCT_ID"], 1, array("NAME" => "ID", "VALUE" => $arFields["ID"]));  
                                  }  
                                                        
                                } elseif($arFields["QUANTITY"] < $ar_fields["RATIO"] * 10 && $arFields["PRODUCT_ID"] != ELEMENT_ID_SPOOL && $arFields["QUANTITY"] >= $ar_fields["RATIO"] * 9){
                                        
                                        $dbBasketItems = CSaleBasket::GetList(
                                                array(
                                                        "NAME" => "ASC",
                                                        "ID" => "ASC"
                                                    ),
                                                array(
                                                        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                                                        "LID" => SITE_ID,
                                                        "ORDER_ID" => "NULL",
                                                     //   "PRODUCT_ID" => ELEMENT_ID_SPOOL,
                                                    ),
                                                false,
                                                false,
                                                array("ID","PRODUCT_ID", "QUANTITY")
                                            );
                                        while ($arItems = $dbBasketItems->Fetch()){
                                            $ratio_item = CCatalogMeasureRatio::getList(Array(), array('IBLOCK_ID'=>$arFields["IBLOCK_ID"], 'PRODUCT_ID'=>$arItems["PRODUCT_ID"]), false, false, array());
                                            $ar_itmem = $ratio_item->Fetch();
                                            if($arItems["PRODUCT_ID"] == ELEMENT_ID_SPOOL && $arItems["QUANTITY"] > 1){
                                                $ratio_update = array("QUANTITY" => $arItems["QUANTITY"] - 1);
                                                CSaleBasket::Update($arItems["ID"], $ratio_update); 
                                            } else if(!in_array($arFields["PRODUCT_ID"], $ar_indent_element) && $arItems["PRODUCT_ID"] == ELEMENT_ID_SPOOL){

                                                    $dbProp = CSaleBasket::GetPropsList(
                                                           Array(
                                                              "ID" => "DESC"
                                                              ), 
                                                           Array(
                                                              "BASKET_ID" => $arItems["ID"],
                                                           false,
                                                           false,
                                                           Array("NAME", "VALUE", "BASKET_ID")
                                                           
                                                     ));            
                                                  while($arProp = $dbProp -> GetNext()){
                                                      if($arProp["NAME"] == "ID" && $arProp["VALUE"] == $arFields["ID"]){  
                                                        CSaleBasket::Delete($arProp["BASKET_ID"]);
                                                      }
                                                  }
                                            }
                                            if($arItems["QUANTITY"] >= $ar_itmem["RATIO"] * 10){
                                                $ar_indent_element[] = $arItems["PRODUCT_ID"];
                                            }
                                        }
                                  }
                         }  

                } else {
                    $ar_section = CIBlockSection::GetByID($ar_res["IBLOCK_SECTION_ID"]);
                    if($section = $ar_section->GetNext()){
                        if($section["IBLOCK_SECTION_ID"] == SECTION_ID_FILM && $getSpoolProps['VALUE'] == PROP_FOR_TERRAMIC){
                        $rsPrices = CPrice::GetList(array(), array( 'PRODUCT_ID' => $ar_res["ID"],'CATALOG_GROUP_ID' => 3));
                            if ($arPrice = $rsPrices->Fetch()) {
                                $ar_res = CCatalogProduct::GetByID($arFields["PRODUCT_ID"]);
                                $ratio = CCatalogMeasureRatio::getList(Array(), array('IBLOCK_ID'=>$arFields["IBLOCK_ID"], 'PRODUCT_ID'=>$arFields["PRODUCT_ID"]), false, false, array());
                                $ar_fields = $ratio->Fetch();   
                                $prop_element = CIBlockElement::GetByID(ELEMENT_ID_SPOOL)->GetNext();
                            //    logger($arFields, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
                                $summ_quantity = $arFields["QUANTITY"] / $ar_fields["RATIO"];
                                
                                    if($arFields["QUANTITY"] == $ar_fields["RATIO"] * 10 ){
                                        $arField = array(
                                        "PRODUCT_ID" => $prop_element["ID"],
                                        "PRICE" => $arPrice["PRICE"],
                                        "CURRENCY" => "RUB",
                                        "QUANTITY" => 1,
                                        "LID" => 's1',
                                        "FUSER_ID" => $arFields["FUSER_ID"],
                                        "WEIGHT" => 100,
                                        "MODULE" => $arFields["MODULE"],
                                        "CAN_BUY" => $arPrice["CAN_BUY"],
                                        "NAME" => $prop_element["NAME"],
                                        "PRODUCT_PROVIDER_CLASS" => $arFields["PRODUCT_PROVIDER_CLASS"],
                                      );
                                      $arField["PROPS"][] = array("NAME" => "ID", "VALUE" => $arFields["ID"]);
                                      
                                      if($arFields["PRODUCT_ID"] != ELEMENT_ID_SPOOL ){
                                        $id = CSaleBasket::Add($arField);  
                                      }                           
                                } elseif($arFields["QUANTITY"] < $ar_fields["RATIO"] * 10 && $arFields["PRODUCT_ID"] != ELEMENT_ID_SPOOL && $arFields["QUANTITY"] >= $ar_fields["RATIO"] * 9){
                                            $dbBasketItems = CSaleBasket::GetList(
                                                    array(
                                                            "NAME" => "ASC",
                                                            "ID" => "ASC"
                                                        ),
                                                    array(
                                                            "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                                                            "LID" => SITE_ID,
                                                            "ORDER_ID" => "NULL",
                                                            "PRODUCT_ID" => ELEMENT_ID_SPOOL,
                                                        ),
                                                    false,
                                                    false,
                                                    array("ID","PRODUCT_ID", "QUANTITY")
                                                );
                                            while ($arItems = $dbBasketItems->Fetch()){
                                                $ratio_item = CCatalogMeasureRatio::getList(Array(), array('IBLOCK_ID'=>$arFields["IBLOCK_ID"], 'PRODUCT_ID'=>$arItems["PRODUCT_ID"]), false, false, array());
                                                $ar_itmem = $ratio_item->Fetch();
                                                if($arItems["PRODUCT_ID"] == ELEMENT_ID_SPOOL && $arItems["QUANTITY"] > 1){
                                                    $ratio_update = array("QUANTITY" => $arItems["QUANTITY"] - 1);
                                                    CSaleBasket::Update($arItems["ID"], $ratio_update); 
                                                } else if(!in_array($arFields["PRODUCT_ID"], $ar_indent_element) && $arItems["PRODUCT_ID"] == ELEMENT_ID_SPOOL){

                                                        $dbProp = CSaleBasket::GetPropsList(
                                                               Array(
                                                                  "ID" => "DESC"
                                                                  ), 
                                                               Array(
                                                                  "BASKET_ID" => $arItems["ID"],
                                                               false,
                                                               false,
                                                               Array("NAME", "VALUE", "BASKET_ID")
                                                               
                                                         ));            
                                                      while($arProp = $dbProp -> GetNext()){
                                                          if($arProp["NAME"] == "ID" && $arProp["VALUE"] == $arFields["ID"]){  
                                                            CSaleBasket::Delete($arProp["BASKET_ID"]);
                                                          }
                                                      }
                                                }
                                                if($arItems["QUANTITY"] >= $ar_itmem["RATIO"] * 10){
                                                    $ar_indent_element[] = $arItems["PRODUCT_ID"];
                                                }
                                            }
                                      }
                             } 
                        }
                    }
      
                }
            }
        }  
}

AddEventHandler("sale", "OnBeforeBasketDelete", "OnBeforeBasketDeleteHandler");

    // создаем обработчик события "OnBeforeIBlockElementDelete"
function OnBeforeBasketDeleteHandler($ID) {
        global $USER;
        $arItems = CSaleBasket::GetByID($ID);
      //  logger('-------arItems--------', $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
     //  logger($arItems, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
      //  logger('-------arItem--------', $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
        CModule::IncludeModule('basket');
        $dbBasketItem = CSaleBasket::GetList(
                array(
                        "NAME" => "ASC",
                        "ID" => "ASC"
                    ),
                array(
                        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                        "LID" => SITE_ID,
                        "ORDER_ID" => "NULL",
                       // "PRODUCT_ID" => 1064,
                    ),
                false,
                false,
                array("ID","PRODUCT_ID")
            );
        while ($arItem = $dbBasketItem->Fetch()){
            //logger($arItem, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
            if($arItems["PRODUCT_ID"] == ELEMENT_ID_SPOOL && $arItems["QUANTITY"] > 1){
                $ratio_update = array("QUANTITY" => $arItems["QUANTITY"] - 1);
                CSaleBasket::Update($arItems["ID"], $ratio_update); 
            } else if($arItem["PRODUCT_ID"] == ELEMENT_ID_SPOOL){
                    $dbProp = CSaleBasket::GetPropsList(
                               Array(
                                  "ID" => "DESC"
                                  ), 
                               Array(
                                  "BASKET_ID" => $arItem["ID"],
                               false,
                               false,
                               Array("*")
                               
                         ));            
                  while($arProp = $dbProp -> GetNext()){
                       //       logger('-------arProp--------', $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
                   //   logger($arProp, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
                      if($arProp["NAME"] == "ID" && $arProp["VALUE"] == $arItems["ID"]){
                        CSaleBasket::Delete($arProp["BASKET_ID"]);
                      }
                  }
            }
              
        }
     //   die();   
}
// <----- разделяем товар раздела пленки и добавляем к ней шпулю
/*AddEventHandler('sale', 'OnOrderAdd', 'updateSpoolProperty');
use Bitrix\Sale;
function updateSpoolProperty($ID, &$arFields){

\Bitrix\Main\Loader::includeModule('sale');    
    //logger($arFields, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
    if(!empty($ID)){
        $orderId = intval($ID);
       // logger($orderId, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
        if($orderId){
            $order = Sale\Order::load($orderId);
            
            $ordFields = $order->getAvailableFields();
           // logger('---------------------$ordFields----------------------------', $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');  
           // logger($ordFields, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
            
            $propertyCollection = $order->getPropertyCollection();
           // logger('---------------------$propertyCollection----------------------------', $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');  
           // logger($propertyCollection, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
            
            $basket = Sale\Basket::loadItemsForOrder($order);
            //logger('---------------------$basket----------------------------', $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');  
           // logger($basket, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');
        }
    }  
}*/
AddEventHandler("catalog", "OnBeforeProductUpdate", "OnBeforeProductAdd"); 
// обновляем ставку НДС на "без НДС"
function OnBeforeProductAdd($ID, &$arFields) { 
    $arFields['VAT_ID'] = "";
    $arFields["VAT_INCLUDED"] = "N";
 //   logger($arFields, $_SERVER["DOCUMENT_ROOT"].'/map/log.txt');

} 

// Обработчик события действий перед изменением товара в ИБ[ID] == 23.
// Сохранение свойства "Текстовое описание товара" елемента при выгрузке из 1С, если это поле отсутствует в выгрузке.
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "SaveTextDescriptionPoduct");
function SaveTextDescriptionPoduct(&$arFildes){
    //Получение значения ID информ. блока эл-та, значения св-ва "TEXT_DESCRIPTION_PRODUCT" и ID этого св-ва
    $item = CIBlockElement::GetList(
        array(),
        array("ID" => $arFildes["ID"]),
        false,
        false,
        array("IBLOCK_ID", "PROPERTY_TEXT_DESCRIPTION_PRODUCT")
    )->fetch();
    if($item["IBLOCK_ID"] == IBCLICK_CATALOG_ID){
        //Получение ID свойства "TEXT_DESCRIPTION_PRODUCT"
        $property = CIBlockProperty::GetList(
            array(),
            array("IBLOCK_ID" => IBCLICK_CATALOG_ID, "CODE" => "TEXT_DESCRIPTION_PRODUCT")
        )->fetch();
        $property_id = $property["ID"];
        $property_value_id = $item["PROPERTY_TEXT_DESCRIPTION_PRODUCT_VALUE_ID"];
        // Если массив с изменениями не содержит поля "TEXT_DESCRIPTION_PRODUCT", то перезаписываем существующее значение
        if(is_null($arFildes["PROPERTY_VALUES"][$property_id][$property_value_id]["VALUE"])){
            $arFildes["PROPERTY_VALUES"][$property_id][0]["VALUE"] = $item["PROPERTY_TEXT_DESCRIPTION_PRODUCT_VALUE"];
        }   
    }
}


AddEventHandler('main', 'OnEpilog', array('CMainHandlers', 'OnEpilogHandler'));  
class CMainHandlers { 
	public static function OnEpilogHandler() {
		$metaOld['title'] = $GLOBALS['APPLICATION']->GetTitle();
		$metaOld['descr'] = $GLOBALS['APPLICATION']->GetPageProperty('description');
		if ( empty($metaOld['descr']) AND !empty($metaOld['title']) ) {
			$GLOBALS['APPLICATION']->SetPageProperty('description', "Компания «Террамика» предлагает лучшие товары по лучшим ценам. " . $metaOld['title'] . ".");
		}
	}
}
?>
