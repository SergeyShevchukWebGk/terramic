<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("iblock"))
    return;

// Изменим количество товара в записи $ID корзины на 2 штуки и отложим товар
$arFields = array(
   "QUANTITY" => $_REQUEST{"newVal"},
   "DELAY" => "Y"
);
CSaleBasket::Update($_REQUEST["id"], $arFields);