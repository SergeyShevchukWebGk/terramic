<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?
    $location = explode(',', $_REQUEST["region"]);
    $location_new = str_replace("область", "обл", trim($location[1]));
    $location_new_2 = str_replace("область", "обл", trim($location[2]));
    $_SESSION["REGION_LOCATION"] = $location_new;
    $_SESSION["REGION_LOCATION_2"] = $location_new_2;
?>