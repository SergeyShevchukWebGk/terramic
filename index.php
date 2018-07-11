<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Производство полиэтиленовой пленки | ООО «Террамик»");
$APPLICATION->SetPageProperty("keywords", "производство полиэтиленовой пленки производители");
$APPLICATION->SetPageProperty("description", "ООО «Террамик» занимается производством полиэтиленовой пленки. Мы обеспечиваем индивидуальный подход к каждому клиенту. Подробности можно узнать по телефонам, указанным на сайте.");
$APPLICATION->SetTitle("Террамик - интернет-магазин товаров для агропромышленного комплекса");
global $arSetting;
if(in_array("CONTENT", $arSetting["HOME_PAGE"]["VALUE"])):?><h1 id="pagetitle">Лучшие товары по лучшим ценам!</h1>
<p>
	Качество этих марок подтверждают многие промышленные гиганты, которые работают с ними уже не один десяток лет.&nbsp;
</p>
 <br><?endif;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>