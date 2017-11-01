<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Террамик - интернет-магазин товаров для агропромышленного комплекса");
global $arSetting;
if(in_array("CONTENT", $arSetting["HOME_PAGE"]["VALUE"])):?><h1 id="pagetitle">Лучшие товары по лучшим ценам!</h1>
<p>
	Качество этих марок подтверждают многие промышленные гиганты, которые работают с ними уже не один десяток лет.&nbsp;
</p>
 <br><?endif;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>