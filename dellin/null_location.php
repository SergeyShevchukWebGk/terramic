<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?
    $location = explode(',', $_REQUEST["region"]);
    $location_new = str_replace("область", "обл", trim($location[1]));
    $_SESSION["REGION_LOCATION"] = $location_new;
?>