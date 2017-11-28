<?php
global $MESS;
$MESS["TMG_PK_TITLE"] = "PayKeeper";
$MESS["TMG_PK_DESC"] = "Платёжная платформа PayKeeper обеспечивает взаимодействие с основными банками и платёжными системами";
$MESS["TMG_PK_SERVER_ADDR"] = "Адрес формы оплаты";
$MESS["TMG_PK_SERVER_ADDR_DESCR"] = "Пример: http://<вашсайт>.server.paykeeper.ru/create/";
$MESS["TMG_PK_FORM_ENCODING"] = "Укажите utf8 для кодировки UTF-8 или cp1251 для CP-1251";
$MESS["TMG_PK_SECRET_KEY"] = "Секретное слово";
$MESS["TMG_PK_SECRET_KEY_DESC"] = "";
//Convert to non-utf-8
if (LANG_CHARSET != "UTF-8") {
    foreach ($MESS as $key => $value)
        $MESS[$key] = iconv("UTF-8", LANG_CHARSET, $value);
}
