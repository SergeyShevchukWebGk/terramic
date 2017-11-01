<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");?>

<?$APPLICATION->IncludeComponent("bitrix:sale.personal.order", "list", 
	array(
		"PROP_1" => array(),
		"PROP_2" => array(),
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/personal/order/",
		"ORDERS_PER_PAGE" => "10",
		"PATH_TO_PAYMENT" => "/personal/order/payment/",
		"PATH_TO_BASKET" => "/personal/cart/",
		"SET_TITLE" => "Y",
		"SAVE_IN_SESSION" => "N",
		"NAV_TEMPLATE" => "arrows",
		"COMPONENT_TEMPLATE" => "list",
		"PROP_2" => array(),
		"ACTIVE_DATE_FORMAT" => "FULL",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"CUSTOM_SELECT_PROPS" => array(),
		"HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"SEF_URL_TEMPLATES" => array(
			"list" => "index.php",
			"detail" => "detail/#ID#/",
			"cancel" => "cancel/#ID#/"
		)
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>