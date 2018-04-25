<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
$sendComment = $_POST["sendComment"];
$_SESSION["ORDER_DESCRIPTION"] = $sendComment;
echo $sendComment;

