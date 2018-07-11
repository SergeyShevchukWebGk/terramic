<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($_POST["save"] == "Y" &&  $_POST["USER_SECOND_NAME"]) {
    $arResult["USER_SECOND_NAME"] = $_POST["USER_SECOND_NAME"];
}
