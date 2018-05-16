<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог товаров");?>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	".default", 
	array(
		"PATH_TO_SHIPPING" => "/delivery/",
		"DISPLAY_IMG_WIDTH" => "178",
		"DISPLAY_IMG_HEIGHT" => "178",
		"DISPLAY_DETAIL_IMG_WIDTH" => "390",
		"DISPLAY_DETAIL_IMG_HEIGHT" => "390",
		"DISPLAY_MORE_PHOTO_WIDTH" => "86",
		"DISPLAY_MORE_PHOTO_HEIGHT" => "86",
		"SHARPEN" => "30",
		"AJAX_MODE" => "N",
		"SEF_MODE" => "Y",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "23",
		"USE_FILTER" => "Y",
		"USE_REVIEW" => "N",
		"USE_COMPARE" => "Y",
		"SHOW_TOP_ELEMENTS" => "N",
		"SECTION_COUNT_ELEMENTS" => "N",
		"SECTION_TOP_DEPTH" => "2",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "1",
		"ELEMENT_SORT_FIELD" => "CATALOG_AVAILABLE",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "",
		"ELEMENT_SORT_ORDER2" => "",
		"LIST_PROPERTY_CODE" => array(
			0 => "",
			1 => "VES_S_AKKUM",
			2 => "CHASTOTA_H_H",
			3 => "MAX_KR_MOM",
			4 => "NAPRAJ_AKKUM",
			5 => "",
		),
		"INCLUDE_SUBSECTIONS" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "PROPUSKNAYA_SPOSOBNOST_M3_CH",
			1 => "FILTRUYUSHCHIY_ELEMENT",
			2 => "SHIRINA_2",
			3 => "SHIRINA_1",
			4 => "SHIRINA",
			5 => "TOLSHCHINA_2",
			6 => "TOLSHCHINA",
			7 => "KOLICHESTVO_SLOEV",
			8 => "KOLICHESTVO_SLOEV_1",
			9 => "SROK_EKSPLUATATSII",
			10 => "SROK_EKSPLUATATSII_1",
			11 => "VYLIV",
			12 => "VID",
			13 => "VID_1",
			14 => "NAZNACHENIE",
			15 => "NAZNACHENIE_1",
			16 => "DIAMETR_POD_STEBEL",
			17 => "DIAMETR_NITI",
			18 => "VID_FORMY",
			19 => "DIAMETR_REZBOVOGO_SOEDINENIYA_1",
			20 => "TIP_PROFILYA",
			21 => "SHIRINA_RULONA",
			22 => "TOLSHCHINA_PROFILYA",
			23 => "EMITERY_CHEREZ",
			24 => "DLINA_PROFILYA",
			25 => "TOLSHCHINA_STENKI",
			26 => "DLINNA_LENTY_V_BUKHTE",
			27 => "TIP_ZAMKA",
			28 => "CML2_ARTICLE",
			29 => "POKRYTIE",
			30 => "CML2_MANUFACTURER",
			31 => "TSVET",
			32 => "TIP_PROFILYA_1",
			33 => "DIAMETR_PODZHIMA_DLYA_TRUBY_MM",
			34 => "TOLSHCHINA_PROFILYA_1",
			35 => "DIAMETR_REZBOVOGO_SOEDINENIYA_2",
			36 => "DLINA",
			37 => "DLINA_RULONA",
			38 => "REZBA",
			39 => "TSVET_1",
			40 => "MATERIAL",
			41 => "REZBA_1",
			42 => "MATERIAL_2",
			43 => "DIAMETR_PODZHIMA_DLYA_TRUBY_MM_1",
			44 => "MATERIAL_3",
			45 => "REZBA_2",
			46 => "DLINA_KRYUCHKA",
			47 => "DIAMETR_KAPELNOY_LENTY",
			48 => "MATERIAL_4",
			49 => "DIAMETR_REZBOVOGO_SOEDINENIYA",
			50 => "MATERIAL_1",
			51 => "DIAMETR_SHLANGOVOGO_SOEDINENIYA",
			52 => "RASPOLOZHENIE",
			53 => "TSVET_2",
			54 => "TOLSHCHINA_1",
			55 => "DLINA_MM",
			56 => "SORT",
			57 => "TSVET_3",
			58 => "METROV_V_RULONE_",
			59 => "DOPOLNITELNYE_DOBAVKI",
			60 => "MANUFACTURER",
			61 => "COUNTRY",
			62 => "VES_S_AKKUM",
			63 => "SROK_SLUZHBY",
			64 => "ARTNUMBER",
			65 => "DLINNA_RULONA",
			66 => "BUKHTA",
			67 => "CHASTOTA_H_H",
			68 => "MAX_KR_MOM",
			69 => "NAPRAJ_AKKUM",
			70 => "METROV_V_BOBINE",
			71 => "",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"BASKET_URL" => "/personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_NOTES" => "",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"PRICE_CODE" => array(
			0 => "Интернет магазин физ.лицо (Вертянкина)",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"USE_PRODUCT_QUANTITY" => "Y",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_IBLOCK_ID" => "",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY" => "N",
		"USE_STORE" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPARE_PROPERTY_CODE" => array(
			0 => "",
			1 => "NEWPRODUCT",
			2 => "SALELEADER",
			3 => "DISCOUNT",
			4 => "MANUFACTURER",
			5 => "COUNTRY",
			6 => "VES_S_AKKUM",
			7 => "SROK_SLUZHBY",
			8 => "ARTNUMBER",
			9 => "DLINNA_RULONA",
			10 => "BUKHTA",
			11 => "CHASTOTA_H_H",
			12 => "MAX_KR_MOM",
			13 => "NAPRAJ_AKKUM",
			14 => "",
		),
		"COMPARE_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPARE_OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3",
			3 => "",
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "shows",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3",
			3 => "",
		),
		"LIST_OFFERS_LIMIT" => "",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3",
			3 => "",
		),
		"FILTER_NAME" => "arrFilter",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PRICE_CODE" => array(
			0 => "Интернет магазин физ.лицо (Вертянкина)",
		),
		"FILTER_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"USE_STORE_PHONE" => "Y",
		"USE_STORE_SCHEDULE" => "Y",
		"USE_MIN_AMOUNT" => "N",
		"STORE_PATH" => "/store/#store_id#",
		"MAIN_TITLE" => "Наличие на складах",
		"HIDE_NOT_AVAILABLE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CURRENCY_ID" => "",
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "asc",
		"IBLOCK_TYPE_REVIEWS" => "catalog",
		"IBLOCK_ID_REVIEWS" => "1",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"SEF_FOLDER" => "/catalog/",
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "bestsell",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"STORES" => array(
			0 => "5",
			1 => "6",
			2 => "",
		),
		"USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"PROPERTY_CODE_MOD" => array(
			0 => "",
			1 => "GUARANTEE",
			2 => "",
		),
		"BUTTON_PAYMENTS_HREF" => "/payments/",
		"BUTTON_CREDIT_HREF" => "",
		"BUTTON_DELIVERY_HREF" => "/delivery/",
		"SHOW_DEACTIVATED" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"SET_LAST_MODIFIED" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "Y",
		"FILE_404" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"MIN_AMOUNT" => "10",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "N",
		"GIFTS_SHOW_OLD_PRICE" => "N",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COMPATIBLE_MODE" => "Y",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE#/",
			"element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
			"compare" => "compare/",
			"smart_filter" => "#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/",
		)
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>