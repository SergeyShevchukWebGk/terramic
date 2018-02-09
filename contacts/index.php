<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");?><h2>
<p>
	 ООО «ТЕРРАМИК»
</p>
 </h2>
<p>
	 Адрес: Самарская область, с. Кинель-Черкассы, ул.Казакова 37а.
</p>
<p>
	 Тел.: +7 (846) 604-71-00
</p>
<p>
	 Часы работы:
</p>
<p>
	 ПН-ПТ 09:00-19:00 Перерыв 13:00-14:00
</p>
<p>
	 СБ 10:00-16:00 Без перерыва
</p>
<p>
	 ВС Выходной
</p>
<h2>Схема проезда</h2>
 <?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	".default",
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CONTROLS" => array(
			0 => "ZOOM",
			1 => "TYPECONTROL",
			2 => "SCALELINE",
		),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:53.44690848043428;s:10:\"yandex_lon\";d:51.508685542328;s:12:\"yandex_scale\";i:16;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:51.508707;s:3:\"LAT\";d:53.446806;s:4:\"TEXT\";s:27:\"ООО «ТЕРРАМИК»\";}}}",
		"MAP_HEIGHT" => "350",
		"MAP_ID" => "1",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(
			0 => "ENABLE_DBLCLICK_ZOOM",
			1 => "ENABLE_DRAGGING",
		),
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?> <br>
<hr>
 <br>
<h2>
<p>
</p>
<p>
	 ИП Вертянкина Татьяна Викторовна
</p>
 </h2>
<p>
	 Адрес: Самарская область, с. Кинель-Черкассы, ул.Казакова 37а.
</p>
<p>
	 Тел.: +7 (846) 604-71-00
</p>
<p>
	 Часы работы:
</p>
<p>
	 ПН-ПТ 09:00-19:00 Перерыв 13:00-14:00
</p>
<p>
	 СБ 10:00-16:00 Без перерыва
</p>
<p>
	 ВС Выходной
</p>
<p>
</p>
<h2>Схема проезда</h2>
 <?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	".default",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"CONTROLS" => array(0=>"ZOOM",1=>"TYPECONTROL",2=>"SCALELINE",),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:53.44690848043717;s:10:\"yandex_lon\";d:51.50868554232785;s:12:\"yandex_scale\";i:16;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:51.508707;s:3:\"LAT\";d:53.446806;s:4:\"TEXT\";s:27:\"ООО «ТЕРРАМИК»\";}}}",
		"MAP_HEIGHT" => "350",
		"MAP_ID" => "1",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(0=>"ENABLE_DBLCLICK_ZOOM",1=>"ENABLE_DRAGGING",)
	)
);?>&nbsp; <br>
<hr>
<h2><br>
 </h2>
<h2>Филиал ООО «ТЕРРАМИК»</h2>
 &nbsp;&nbsp;<br>
 Адрес: Краснодарский край, Усть-Лабинский район, станица Воронежская, ул.Садовая 5а<br>
 <br>
<p>
	 Тел.: +7 (861) 212-30-05, +7(918) 976-08-80
</p>
<p>
	 Часы работы:
</p>
<p>
	 ПН-ВС 09:00-18:00 Без перерыва
</p>
<h2>Схема проезда</h2>
 <?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	".default",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONTROLS" => array(0=>"ZOOM",1=>"TYPECONTROL",2=>"SCALELINE",),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:45.20922036060809;s:10:\"yandex_lon\";d:39.54602833163461;s:12:\"yandex_scale\";i:16;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:39.543357085887;s:3:\"LAT\";d:45.207657361904;s:4:\"TEXT\";s:174:\"ООО «ТЕРРАМИК»###RN###Краснодарский край, Усть-Лабинский район, станица Воронежская, ул.Садовая 5а\";}}}",
		"MAP_HEIGHT" => "305",
		"MAP_ID" => "1",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(0=>"ENABLE_DBLCLICK_ZOOM",1=>"ENABLE_DRAGGING",)
	)
);?> <br>
<hr>
<h2><br>
 </h2>
<h2>Форма обратной связи</h2>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.feedback",
	".default",
	Array(
		"EMAIL_TO" => "info@terramic.ru",
		"EVENT_MESSAGE_ID" => array(),
		"OK_TEXT" => "Спасибо, ваше сообщение принято.",
		"REQUIRED_FIELDS" => array("NAME","EMAIL","MESSAGE"),
		"USE_CAPTCHA" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php")?>