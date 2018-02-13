<?$APPLICATION->IncludeComponent("altop:search.title", "search_top", Array(
	"SHOW_INPUT" => "Y",	// Показывать форму ввода поискового запроса
		"INPUT_ID" => "title-search-input",	// ID строки ввода поискового запроса
		"CONTAINER_ID" => "altop_search",	// ID контейнера, по ширине которого будут выводиться результаты
		"IBLOCK_TYPE" => "catalog",	// Тип инфоблока
		"IBLOCK_ID" => "23",	// Инфоблок
		"PAGE" => "/catalog/",	// Страница выдачи результатов поиска (доступен макрос #SITE_DIR#)
		"NUM_CATEGORIES" => "1",	// Количество категорий поиска
		"TOP_COUNT" => "7",	// Количество результатов в каждой категории
		"ORDER" => "rank",	// Сортировка результатов
		"USE_LANGUAGE_GUESS" => "Y",	// Включить автоопределение раскладки клавиатуры
		"CHECK_DATES" => "N",	// Искать только в активных по дате документах
		"PROPERTY_CODE_MOD" => array(	// Свойства для выбора (значения которых сможет выбрать покупатель)
			0 => "",
			1 => "GUARANTEE",
			2 => "ARTNUMBER",
			3 => "",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3",
			3 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "asc",
		"OFFERS_LIMIT" => "",	// Максимальное количество предложений для показа (0 - все)
		"SHOW_PRICE" => "Y",	// Отображать цену
		"PRICE_CODE" => array(	// Тип цены
			0 => "BASE",
			1 => "Интернет магазин Юр.лицо (Террамик)",
			2 => "Интернет магазин физ.лицо (Вертянкина)",
		),
		"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
		"SHOW_ADD_TO_CART" => "Y",	// Отображать кнопку 'В корзину'
		"SHOW_ALL_RESULTS" => "Y",	// Отображать ссылку 'Все результаты'
		"CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),	// Название категории
		"CATEGORY_0" => array(	// Ограничение области поиска
			0 => "iblock_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(	// Искать в информационных блоках типа "iblock_catalog"
			0 => "all",
		),
		"CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
		"CURRENCY_ID" => "",
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR",
			1 => "PROP2",
			2 => "PROP3",
		),
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
		"COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
	),
	false
);?>