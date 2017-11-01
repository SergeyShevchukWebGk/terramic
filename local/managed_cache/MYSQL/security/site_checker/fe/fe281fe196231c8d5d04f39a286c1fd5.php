<?
if($INCLUDE_FROM_CACHE!='Y')return false;
$datecreate = '001507071891';
$dateexpire = '001509663891';
$ser_content = 'a:2:{s:7:"CONTENT";s:0:"";s:4:"VARS";a:2:{s:7:"results";a:7:{i:0;a:5:{s:5:"title";s:61:"Включен расширенный вывод ошибок";s:8:"critical";s:5:"HIGHT";s:6:"detail";s:126:"Расширенный вывод ошибок может раскрыть важную информацию о ресурсе";s:14:"recommendation";s:63:"Выключить в файле настроек .settings.php";s:15:"additional_info";s:0:"";}i:1;a:5:{s:5:"title";s:77:"Используются устаревшие модули платформы";s:8:"critical";s:5:"HIGHT";s:6:"detail";s:55:"Доступны новые версии модулей";s:14:"recommendation";s:275:"Рекомендуется своевременно обновлять модули платформы, установить рекомендуемые обновления: <a href="/bitrix/admin/update_system.php" target="_blank">Обновление платформы</a>";s:15:"additional_info";s:406:"Модули для которых доступны обновления:<br>main<br />
fileman<br />
iblock<br />
subscribe<br />
translate<br />
workflow<br />
catalog<br />
sale<br />
currency<br />
mail<br />
ldap<br />
blog<br />
socialnetwork<br />
bizproc<br />
seo<br />
bizprocdesigner<br />
lists<br />
socialservices<br />
clouds<br />
report<br />
im<br />
bitrixcloud<br />
sender<br />
abtest";}i:2;a:5:{s:5:"title";s:113:"Разрешено отображение сайта во фрейме с произвольного домена";s:8:"critical";s:6:"MIDDLE";s:6:"detail";s:307:"Запрет отображения фреймов сайта со сторонних доменов способен предотвратить целый класс атак, таких как <a href="https://www.owasp.org/index.php/Clickjacking" target="_blank">Clickjacking</a>, Framesniffing и т.д.";s:14:"recommendation";s:1875:"Скорее всего, вам будет достаточно разрешения на просмотр сайта в фреймах только на страницах текущего сайта.
Сделать это достаточно просто, достаточно добавить заголовок ответа "X-Frame-Options: SAMEORIGIN" в конфигурации вашего frontend-сервера.
</p><p>В случае использования nginx:<br>
1. Найти секцию server, отвечающую за обработку запросов нужного сайта. Зачастую это файлы в /etc/nginx/site-enabled/*.conf<br>
2. Добавить строку:
<pre>
add_header X-Frame-Options SAMEORIGIN;
</pre>
3. Перезапустить nginx<br>
Подробнее об этой директиве можно прочесть в документации к nginx: <a href="http://nginx.org/ru/docs/http/ngx_http_headers_module.html" target="_blank">Модуль ngx_http_headers_module</a>
</p><p>В случае использования Apache:<br>
1. Найти конфигурационный файл для вашего сайта, зачастую это файлы /etc/apache2/httpd.conf, /etc/apache2/vhost.d/*.conf<br>
2. Добавить строки:
<pre>
&lt;IfModule headers_module&gt;
	Header set X-Frame-Options SAMEORIGIN
&lt;/IfModule&gt;
</pre>
3. Перезапустить Apache<br>
4. Убедиться, что он корректно обрабатывается Apache и этот заголовок никто не переопределяет<br>
Подробнее об этой директиве можно прочесть в документации к Apache: <a href="http://httpd.apache.org/docs/2.2/mod/mod_headers.html" target="_blank">Apache Module mod_headers</a>
</p>";s:15:"additional_info";s:1841:"Адрес: <a href="https://terramic.ru/" target="_blank">https://terramic.ru/</a><br>Запрос/Ответ: <pre>GET / HTTP/1.1
host: terramic.ru
accept: */*
user-agent: BitrixCloud BitrixSecurityScanner/Robin-Scooter

HTTP/1.1 200 OK
Server: nginx-reuseport/1.13.2
Date: Mon, 02 Oct 2017 18:45:21 GMT
Content-Type: text/html; charset=UTF-8
Content-Length: 41849
Connection: keep-alive
Keep-Alive: timeout=30
Vary: Accept-Encoding
X-Powered-By: PHP/7.1.5
ETag: da318ffb7c12a86861505ceb765e5523
Expires: Fri, 07 Jun 1974 04:00:00 GMT
Last-Modified: Mon, 02 Oct 2017 12:36:21 GMT
X-Bitrix-Composite: Cache (200)

&lt;!DOCTYPE html&gt;
&lt;html lang=&quot;ru&quot;&gt;
&lt;head&gt;
	&lt;link rel=&quot;shortcut icon&quot; type=&quot;image/x-icon&quot; href=&quot;/bitrix/templates/elektro_flat/favicon.ico?v=2&quot; /&gt;	
			&lt;title&gt;Террамик - интернет-магазин товаров для агропромышленного комплекса&lt;/title&gt;
	&lt;meta http-equiv=&quot;Content-Type&quot; content=&quot;text/html; charset=UTF-8&quot; /&gt;
&lt;meta name=&quot;robots&quot; content=&quot;index, follow&quot; /&gt;
&lt;script type=&quot;text/javascript&quot; data-skip-moving=&quot;true&quot;&gt;(function(w, d) {var v = w.frameCacheVars = {\'CACHE_MODE\':\'HTMLCACHE\',\'storageBlocks\':[],\'dynamicBlocks\':{\'iPbi6c\':{\'hash\':\'d41d8cd98f00b204e9800998ecf8427e\'},\'callback_s1\':{\'hash\':\'d41d8cd98f00b204e9800998ecf8427e\'},\'bigdata\':{\'hash\':\'d41d8cd98f00b204e9800998ecf8427e\'},\'already_seen\':{\'hash\':\'d41d8cd98f00b204e9800998ecf8427e\'},\'kabinet\':{\'hash\':\'d41d8cd98f00b204e9800998ecf8427e\'},\'compare\':{\'hash\':\'13ba779151facae8959150c5b21872fb\'},\'delay\':{\'hash\':\'80b34c1d5ca0f39590a80de88ef9a9cb\'},\'cart_line\':{\'hash\':\'d41d8cd98f00b204e9800998ecf8427e\'}},\'AUTO_UPDATE\':
----------Only 1Kb of body shown----------<pre>";}i:3;a:5:{s:5:"title";s:110:"Установлен не корректный порядок формирования массива _REQUEST";s:8:"critical";s:6:"MIDDLE";s:6:"detail";s:392:"Зачастую в массив _REQUEST нет необходимости добавлять любые переменные, кроме массивов _GET и _POST. В противном случае это может привести к раскрытию информации о пользователе/сайте и иным не предсказуемым последствиям.";s:14:"recommendation";s:88:"Необходимо в настройках php указать:<br>request_order = "GP"";s:15:"additional_info";s:75:"Текущее значение: ""<br>Рекомендованное: "GP"";}i:4;a:5:{s:5:"title";s:119:"Временные файлы хранятся в пределах корневой директории проекта";s:8:"critical";s:6:"MIDDLE";s:6:"detail";s:271:"Хранение временных файлов, создаваемых при использовании CTempFile, в пределах корневой директории проекта не рекомендовано и несет с собой ряд рисков.";s:14:"recommendation";s:883:"Необходимо определить константу "BX_TEMPORARY_FILES_DIRECTORY" в "bitrix/php_interface/dbconn.php" с указанием необходимого пути.<br>
Выполните следующие шаги:<br>
1. Выберите директорию вне корня проекта. Например, это может быть "/home/bitrix/tmp/www"<br>
2. Создайте ее. Для этого выполните следующую комманду:
<pre>
mkdir -p -m 700 /полный/путь/к/директории
</pre>
3. В файле "bitrix/php_interface/dbconn.php" определите соответствующую константу, чтобы система начала использовать эту директорию:
<pre>
define("BX_TEMPORARY_FILES_DIRECTORY", "/полный/путь/к/директории");
</pre>";s:15:"additional_info";s:88:"Текущая директория: /home/t/terramic/terramic.ru/public_html/upload/tmp";}i:5;a:5:{s:5:"title";s:44:"Включен Automatic MIME Type Detection";s:8:"critical";s:3:"LOW";s:6:"detail";s:248:"По умолчанию в Internet Explorer/FlashPlayer включен автоматический mime-сниффинг, что может служить источником XSS нападения или раскрытия информации.";s:14:"recommendation";s:1752:"Скорее всего, вам не нужна эта функция, поэтому её можно безболезненно отключить, добавив заголовок ответа "X-Content-Type-Options: nosniff" в конфигурации вашего веб-сервера.
</p><p>В случае использования nginx:<br>
1. Найти секцию server, отвечающую за обработку запросов нужного сайта. Зачастую это файлы в /etc/nginx/site-enabled/*.conf<br>
2. Добавить строку:
<pre>
add_header X-Content-Type-Options nosniff;
</pre>
3. Перезапустить nginx<br>
Подробнее об этой директиве можно прочесть в документации к nginx: <a href="http://nginx.org/ru/docs/http/ngx_http_headers_module.html" target="_blank">Модуль ngx_http_headers_module</a>
</p><p>В случае использования Apache:<br>
1. Найти конфигурационный файл для вашего сайта, зачастую это файлы /etc/apache2/httpd.conf, /etc/apache2/vhost.d/*.conf<br>
2. Добавить строки:
<pre>
&lt;IfModule headers_module&gt;
	Header set X-Content-Type-Options nosniff
&lt;/IfModule&gt;
</pre>
3. Перезапустить Apache<br>
4. Убедиться, что он корректно обрабатывается Apache и этот заголовок никто не переопределяет<br>
Подробнее об этой директиве можно прочесть в документации к Apache: <a href="http://httpd.apache.org/docs/2.2/mod/mod_headers.html" target="_blank">Apache Module mod_headers</a>
</p>";s:15:"additional_info";s:1866:"Адрес: <a href="https://terramic.ru/bitrix/js/main/core/core.js?rnd=0.471151327407" target="_blank">https://terramic.ru/bitrix/js/main/core/core.js?rnd=0.471151327407</a><br>Запрос/Ответ: <pre>GET /bitrix/js/main/core/core.js?rnd=0.471151327407 HTTP/1.1
host: terramic.ru
accept: */*
user-agent: BitrixCloud BitrixSecurityScanner/Robin-Scooter

HTTP/1.1 200 OK
Server: nginx-reuseport/1.13.2
Date: Mon, 02 Oct 2017 18:45:20 GMT
Content-Type: application/x-javascript
Content-Length: 118107
Last-Modified: Mon, 26 Jun 2017 07:17:09 GMT
Connection: keep-alive
Keep-Alive: timeout=30
Vary: Accept-Encoding
ETag: &quot;5950b4f5-1cd5b&quot;
Expires: Mon, 09 Oct 2017 18:45:20 GMT
Cache-Control: max-age=604800
Accept-Ranges: bytes

/**********************************************************************/
/*********** Bitrix JS Core library ver 0.9.0 beta ********************/
/**********************************************************************/

;(function(window){

if (!!window.BX &amp;&amp; !!window.BX.extend)
	return;

var _bxtmp;
if (!!window.BX)
{
	_bxtmp = window.BX;
}

window.BX = function(node, bCache)
{
	if (BX.type.isNotEmptyString(node))
	{
		var ob;

		if (!!bCache &amp;&amp; null != NODECACHE[node])
			ob = NODECACHE[node];
		ob = ob || document.getElementById(node);
		if (!!bCache)
			NODECACHE[node] = ob;

		return ob;
	}
	else if (BX.type.isDomNode(node))
		return node;
	else if (BX.type.isFunction(node))
		return BX.ready(node);

	return null;
};

BX.debugEnableFlag = true;

// language utility
BX.message = function(mess)
{
	if (BX.type.isString(mess))
	{
		if (typeof BX.message[mess] == &quot;undefined&quot;)
		{
			BX.onCustomEvent(&quot;onBXMessageNotFound&quot;, [mess]);
			if (typeof BX.message[mess] == &quot;undefined&quot;)
			{
				BX.debug(&quot;message undef
----------Only 1Kb of body shown----------<pre>";}i:6;a:5:{s:5:"title";s:38:"Включен вывод ошибок";s:8:"critical";s:3:"LOW";s:6:"detail";s:202:"Вывод ошибок предназначен для разработки и тестовых стендов, он не должен использоваться на конечном ресурсе.";s:14:"recommendation";s:88:"Необходимо в настройках php указать:<br>display_errors = Off";s:15:"additional_info";s:0:"";}}s:9:"test_date";s:10:"02.10.2017";}}';
return true;
?>