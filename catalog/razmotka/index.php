?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Размотка");
global $arrFilter;
$arrFilter["PROPERTY_RAZMOTKA_VALUE"] = "Размотка";
?>

<?$APPLICATION->IncludeComponent("bitrix:catalog.section", 'table',
    array(
        "IBLOCK_TYPE" => 'catalog',
        "IBLOCK_ID" => 23,
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
            5 => "RAZMOTKA",
        ),
        "SET_LAST_MODIFIED" => "N",
        "INCLUDE_SUBSECTIONS" => "Y",
        "SHOW_ALL_WO_SECTION" => "Y",
        "BASKET_URL" => "/personal/cart/",
        "ACTION_VARIABLE" => "action",
        "PRODUCT_ID_VARIABLE" => "id",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "FILTER_NAME" => 'arrFilter',
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_NOTES" => "",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "SET_TITLE" => "Y",
        "SHOW_404" => "Y",
        "FILE_404" => "",
        "PAGE_ELEMENT_COUNT" => "12",
        "LINE_ELEMENT_COUNT" => "1",
        "ELEMENT_SORT_FIELD" => "CATALOG_AVAILABLE",
        "ELEMENT_SORT_ORDER" => "asc",
        "ELEMENT_SORT_FIELD2" => "",
        "ELEMENT_SORT_ORDER2" => "",
        "PRICE_CODE" => array(
            0 => "Интернет магазин физ.лицо (Вертянкина)",
        ),
        "USE_PRICE_COUNT" => "N",
        "SHOW_PRICE_COUNT" => "1",
        "PRICE_VAT_INCLUDE" => "Y",
        "USE_PRODUCT_QUANTITY" => "Y",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Товары",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "OFFERS_CART_PROPERTIES" => array(
            0 => "COLOR",
            1 => "PROP2",
            2 => "PROP3",
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
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_FIELD2" => "id",
        "OFFERS_SORT_ORDER2" => "asc",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
        "USE_MAIN_ELEMENT_SECTION" => "Y",
        "CONVERT_CURRENCY" => "N",
        "HIDE_NOT_AVAILABLE" => "Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "BACKGROUND_IMAGE" => "-",
        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
        "DISPLAY_IMG_WIDTH" => "178",
        "DISPLAY_IMG_HEIGHT" => "178",
        "PROPERTY_CODE_MOD" => array(
            0 => "",
            1 => "GUARANTEE",
            2 => "",
        ),
    ),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>