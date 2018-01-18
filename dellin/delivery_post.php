<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?
    $_SESSION["PACKING_HARD"] = $_REQUEST["param"];
  arshow($_SESSION["PACKING_HARD"]);
?>