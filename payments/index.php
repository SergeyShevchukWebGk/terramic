<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Способы оплаты");?>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list", 
	"payments", 
	array(
		"IBLOCK_TYPE" => "content",
		"IBLOCK_ID" => "11",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"COUNT_ELEMENTS" => "N",
		"TOP_DEPTH" => "2",
		"SECTION_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"VIEW_MODE" => "",
		"SHOW_PARENT_NAME" => "",
		"SECTION_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"COMPONENT_TEMPLATE" => "payments",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
<?/*
<h2>Дополнительное текстовое описание раздела</h2>
<p>Вы можете отключить ненужные вам способы оплаты или добавить свои, используя удобную для вас структуру. Рекомендуем не использовать вложенность категорий&nbsp;более 2-х уровней.</p>
<p>Данный сайт является демо-версией готового интернет-магазина ЭЛЕКТРОСИЛА для 1С-Битрикс. Вся информация на сайте не является офертой, а служит лишь примером наполнения для ознакомления с возможностями решения.</p>
*/?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>