<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog"))
    return;
//DELIVERY_LOGOTIP//
foreach($arResult["DELIVERY"] as $key => $arDelivery) {
    if(is_array($arDelivery["LOGOTIP"])) {
        if($arDelivery["LOGOTIP"]["WIDTH"] > 80 || $arDelivery["LOGOTIP"]["HEIGHT"] > 31) {
            $arFileTmp = CFile::ResizeImageGet(
                $arDelivery["LOGOTIP"],
                array("width" => 150, "height" => 61),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
            $arResult["DELIVERY"][$key]["LOGOTIP"] = array(
                "SRC" => $arFileTmp["src"],
                "WIDTH" => $arFileTmp["width"],
                "HEIGHT" => $arFileTmp["height"],
            );
        }
    }
}

//PAY_SYSTEM_LOGOTIP//
foreach($arResult["PAY_SYSTEM"] as $key => $arPaySystem) {
    if(is_array($arPaySystem["PSA_LOGOTIP"])) {
        if($arPaySystem["PSA_LOGOTIP"]["WIDTH"] > 80 || $arPaySystem["PSA_LOGOTIP"]["HEIGHT"] > 31) {
            $arFileTmp = CFile::ResizeImageGet(
                $arPaySystem["PSA_LOGOTIP"],
                array("width" => 80, "height" => 31),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
            $arResult["PAY_SYSTEM"][$key]["PSA_LOGOTIP"] = array(
                "SRC" => $arFileTmp["src"],
                "WIDTH" => $arFileTmp["width"],
                "HEIGHT" => $arFileTmp["height"],
            );
        }
    }
}

//PICTURE//
foreach($arResult["BASKET_ITEMS"] as $key => $arBasketItems) {
    $ar = CIBlockElement::GetList(
        array(), 
        array("ID" => $arBasketItems["PRODUCT_ID"]), 
        false, 
        false, 
        array("ID", "IBLOCK_ID", "DETAIL_PICTURE")
    )->Fetch();        
    if($ar["DETAIL_PICTURE"] > 0) {
        $arResult["BASKET_ITEMS"][$key]["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    } else {
        $mxResult = CCatalogSku::GetProductInfo($ar["ID"]);
        if(is_array($mxResult)) {
            $ar = CIBlockElement::GetList(
                array(), 
                array("ID" => $mxResult["ID"]), 
                false, 
                false, 
                array("ID", "IBLOCK_ID", "DETAIL_PICTURE")
            )->Fetch();
            if($ar["DETAIL_PICTURE"] > 0) {
                $arResult["BASKET_ITEMS"][$key]["DETAIL_PICTURE"] = CFile::ResizeImageGet($ar["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true);
            }
        }
    }
}

//AUTH_SERVICES//
$arResult["AUTH_SERVICES"] = false;
if(!$USER->IsAuthorized() && CModule::IncludeModule("socialservices")) {
    $oAuthManager = new CSocServAuthManager();
    $arServices = $oAuthManager->GetActiveAuthServices($arResult);

    if(!empty($arServices)) {
        $arResult["AUTH_SERVICES"] = $arServices;
    }
}

// Выведем актуальную корзину для текущего пользователя
CModule::IncludeModule('iblock');
foreach ($arResult["BASKET_ITEMS"] as $arItems){
    $k = 0;
    while( $k < 12){  // перебираем все свойства с объемами товара
        if($k == 0){
            $params_width = 'VES_KG'; 
        } else {
            $params_width = "VES_KG_".$k;
        }
        $width_number = CIBlockElement::GetProperty(IBCLICK_CATALOG_ID, $arItems["PRODUCT_ID"], array(), array("CODE" => $params_width));
            while ($am = $width_number->Fetch()){
                if(!empty($am["VALUE_ENUM"])){  //  проверим чтобюы они были 
                    $number = floatval(str_replace(",", ".", $am["VALUE_ENUM"]));   
                    $number = $number * $arItems["QUANTITY"];                            
                    $arResult["WIDTH"] += $number;   
                }
            } 
    $k++;
    }
    $i = 0;
    while( $i < 12){  // перебираем все свойства с объемами товара
        if($k == 0){
            $params_amount = 'OBEM_M3'; 
        } else {
            $params_amount = "OBEM_M3_".$i;
        }
        $amount_number = CIBlockElement::GetProperty(IBCLICK_CATALOG_ID, $arItems["PRODUCT_ID"], array(), array("CODE" => $params_amount));
            while ($am = $amount_number->Fetch()){
                if(!empty($am["VALUE_ENUM"])){  //  проверим чтобюы они были 
                    $number_am = floatval(str_replace(",", ".", $am["VALUE_ENUM"])) * 10; 
                    $number_am = $number_am * $arItems["QUANTITY"];                                                          
                    $arResult["AMOUNT"] += $number_am;   
                }
            } 
    $i++;
    }
}  
if($arResult["AMOUNT"] > 0.001){
    $arResult["AMOUNT"] = $arResult["AMOUNT"] / 10;
}
?>