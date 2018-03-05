<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!function_exists("showFilePropertyField")) {
    function showFilePropertyField($name, $property_fields, $values, $max_file_size_show=50000) {
        $res = "";

        if(!is_array($values) || empty($values))
            $values = array(
                "n0" => 0,
            );

        if($property_fields["MULTIPLE"] == "N") {
            $res = "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
        } else {
            $res = '
            <script type="text/javascript">
                function addControl(item)
                {
                    var current_name = item.id.split("[")[0],
                        current_id = item.id.split("[")[1].replace("[", "").replace("]", ""),
                        next_id = parseInt(current_id) + 1;

                    var newInput = document.createElement("input");
                    newInput.type = "file";
                    newInput.name = current_name + "[" + next_id + "]";
                    newInput.id = current_name + "[" + next_id + "]";
                    newInput.onchange = function() { addControl(this); };

                    var br = document.createElement("br");
                    var br2 = document.createElement("br");

                    BX(item.id).parentNode.appendChild(br);
                    BX(item.id).parentNode.appendChild(br2);
                    BX(item.id).parentNode.appendChild(newInput);
                }
            </script>
            ';

            $res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
            $res .= "<br/><br/>";
            $res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[1]\" id=\"".$name."[1]\" onChange=\"javascript:addControl(this);\"></label>";
        }
        return $res;
    }
}

if(!function_exists("PrintPropsForm")) {
    function PrintPropsForm($arSource = array(), $locationTemplate = ".default") {
        $group = '0';
        // echo("<pre>");
        // print_r($arSource);
        // echo("</pre>");
        if(!empty($arSource)) {
            foreach($arSource as $arProperties) {

                if(!($group==$arProperties['PROPS_GROUP_ID'])):
                    if($group==0):?>
                        <div class="group_el group_id_<?=$arProperties['PROPS_GROUP_ID']?> ">
                    <?else:?>
                        </div>
                        <div class="group_el group_id_<?=$arProperties['PROPS_GROUP_ID']?> ">
                    <?endif;
                    $group = $arProperties['PROPS_GROUP_ID'];
                endif;
                ?>

                <div class="property <?=($arProperties["CODE"] == 'delivery_type')? 'delivery_type':''?>" data-property-id-row="<?=intval(intval($arProperties["ID"]))?>" >
                    <?if($arProperties["TYPE"] == "CHECKBOX") {?>

                        <input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="">
                        <div class="label">
                            <?=$arProperties["NAME"]?>
                            <?if($arProperties["REQUIED_FORMATED"]=="Y"):?>
                                <span class="star">*</span>
                            <?endif;?>
                        </div>
                        <div class="block">
                            <input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>
                            <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                                <div class="description">
                                    <?=$arProperties["DESCRIPTION"]?>
                                </div>
                            <?endif;?>
                        </div>
                        <div class="clr"></div>

                    <?} elseif($arProperties["TYPE"] == "TEXT") {
                        if (in_array($arProperties["CODE"], array("PHONE", "MOBILE_PHONE", "PASSPORT_SERIES", "PASSPORT_NUMBER", "INN", "KPP", "FAX"))) {
                            $is_numeric_field = "Y";
                        } else {
                            $is_numeric_field = "N";
                        }
                        if ($arProperties["CODE"] == "INN" && $arProperties["PERSON_TYPE_ID"] == 2) {
                            $field_size = 10;
                        } else if ($arProperties["CODE"] == "KPP") {
                            $field_size = 9;
                        } else if ($arProperties["CODE"] == "PASSPORT_SERIES") {
                            $field_size = 4;
                        } else if ($arProperties["CODE"] == "PASSPORT_NUMBER") {
                            $field_size = 6;
                        } else if ($arProperties["CODE"] == "INN" && $arProperties["PERSON_TYPE_ID"] == 3) {
                            $field_size = 12;
                        } else {
                            $field_size = 250;
                        }
                        ?>
                        <?if($arProperties["CODE"] != 'TERMINAL_DL' && $arProperties["CODE"] != 'TERMINALS'){?>
                            <div class="label <?=($arProperties["CODE"] == 'TERMINAL_DL')? 'terminals':''?>">
                                <?=$arProperties["NAME"]?>
                                <?if($arProperties["REQUIED_FORMATED"]=="Y"):?>
                                    <span class="star">*</span>
                                <?endif;?>
                            </div>
                            <div class="block <?=($arProperties["CODE"] == 'TERMINAL_DL')? 'terminals':''?>" >
                                <input type="text"
                                    maxlength="<?= $field_size ?>"
                                    size="<?=$arProperties["SIZE1"]?>"
                                    value="<?=$arProperties["VALUE"]//=($arProperties["VALUE"]) ? htmlspecialchars($arProperties["VALUE"]) : $_POST[$arProperties["FIELD_NAME"]]?>"
                                    name="<?=$arProperties["FIELD_NAME"]?>"
                                    placeholder="<?=$arProperties["DESCRIPTION"]?>"
                                    id="<?=$arProperties["FIELD_NAME"]?>"
                                    <?if ($is_numeric_field == "Y") {?>
                                        onkeydown = "if (((event.which < 48 || event.which > 57) || event.shiftKey === true) && event.which != 8 && event.which != 9) {event.preventDefault();return false;}"
                                    <?}?>/>
                                <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                                    <div class="description">
                                        <?//=$arProperties["DESCRIPTION"]?>
                                    </div>
                                <?endif;?>
                            </div>
                            <div class="clr"></div>
                        <?}?>
                    <?} elseif($arProperties["TYPE"] == "SELECT") {?>
                        <?if($arProperties["ID"] == 42){
                            $_prop_nds = "N";
                        }?>
                        <div class="label  
                            <?=($arProperties["CODE"] == 'agreement')? 'agreement':''?> 
                            <?=($arProperties["CODE"] == 'stock')?'stock':''?>
                            <?=($arProperties["CODE"] == 'organization')?'organization':''?>
                        ">
                            <?=$arProperties["NAME"]?>
                            <?if($arProperties["REQUIED_FORMATED"]=="Y"):?>
                                <span class="star">*</span>
                            <?endif;?>
                        </div>
                        <?if($arProperties["CODE"] == 'stock' && $_POST["BUYER_STORE"] == 3 ){
                             $select = 'stock_1';
                        } else if($arProperties["CODE"] == 'stock' && $_POST["BUYER_STORE"] == 8){
                             $select = 'stock_2';
                        } else {
                             $select = '';
                        }?>
                        <div class="block 
                            <?=($arProperties["CODE"] == 'stock')?'stock':''?> 
                            <?=($arProperties["CODE"] == 'agreement')? 'agreement':''?> 
                            <?=($arProperties["CODE"] == 'organization')? 'organization':''?> 
                        ">
                            <select name="<?=$arProperties["FIELD_NAME"]?>" <?=($arProperties["CODE"] == 'agreement')? 'onclick="return false"':''?> id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
                                <?foreach($arProperties["VARIANTS"] as $arVariants):?>
                                    <option <?=($arVariants["VALUE"] == $select)? 'selected':''?> value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
                                <?endforeach;?>
                            </select>
                            <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                                <div class="description">
                                    <?//=$arProperties["DESCRIPTION"]?>
                                </div>
                            <?endif;?>
                        </div>
                        <div class="clr"></div>

                    <?} elseif($arProperties["TYPE"] == "MULTISELECT") {?>
                        <div class="label">
                            <?=$arProperties["NAME"]?>
                            <?if($arProperties["REQUIED_FORMATED"]=="Y"):?>
                                <span class="star">*</span>
                            <?endif;?>
                        </div>
                        <div class="block">
                            <select multiple name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
                                <?foreach($arProperties["VARIANTS"] as $arVariants):?>
                                    <option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
                                <?endforeach;?>
                            </select>
                            <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                                <div class="description">
                                    <?//=$arProperties["DESCRIPTION"]?>
                                </div>
                            <?endif;?>
                        </div>
                        <div class="clr"></div>

                    <?} elseif($arProperties["TYPE"] == "TEXTAREA") {?>
                            <?if($arProperties["CODE"] != 'ADDRESS'){?>
                            <div class="label">
                                <?=$arProperties["NAME"]?>
                                <?if($arProperties["REQUIED_FORMATED"]=="Y"):?>
                                    <span class="star">*</span>
                                <?endif;?>
                            </div>
                            <div class="block">  
                                <textarea rows="<?=$arProperties["SIZE2"]?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" placeholder="<?=$arProperties["DESCRIPTION"]?>"><?=$arProperties["VALUE"]?></textarea>
                                <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                                    <div class="description">
                                        <?//=$arProperties["DESCRIPTION"]?>
                                    </div>
                                <?endif;?>
                            </div>
                            <div class="clr"></div>
                           <?}?>
                    <?} elseif($arProperties["TYPE"] == "LOCATION") {?>
                       <?/*?>
                        <div class="label location_hide" >
                            <?=$arProperties["NAME"]?>
                            <?if($arProperties["REQUIED_FORMATED"]=="Y"):?>
                                <span class="star">*</span>
                            <?endif;?>
                        </div>
                        <div class="block location_hide" >
                            <?$value = 0;
                            if(is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0) {
                                foreach($arProperties["VARIANTS"] as $arVariant) {
                                    if($arVariant["SELECTED"] == "Y") {
                                        $value = $arVariant["ID"];
                                        break;
                                    }
                                }
                            }

                            if(CSaleLocation::isLocationProMigrated()) {
                                $locationTemplateP = $locationTemplate == 'popup' ? 'search' : 'steps';
                                $locationTemplateP = $_REQUEST['PERMANENT_MODE_STEPS'] == 1 ? 'steps' : $locationTemplateP;
                            }

                            if($locationTemplateP == 'steps'):?>
                                <input type="hidden" id="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" name="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" value="<?=($_REQUEST['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($arProperties["ID"])] ? '1' : '0')?>" />
                            <?endif?>

                            <?CSaleLocation::proxySaleAjaxLocationsComponent(
                                array(
                                    "AJAX_CALL" => "N",
                                    "COUNTRY_INPUT_NAME" => "COUNTRY",
                                    "REGION_INPUT_NAME" => "REGION",
                                    "CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
                                    "CITY_OUT_LOCATION" => "Y",
                                    "LOCATION_VALUE" => $value,
                                    "ORDER_PROPS_ID" => $arProperties["ID"],
                                    "ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                                    "SIZE1" => $arProperties["SIZE1"],
                                ),
                                array(
                                    "ID" => $value,
                                    "CODE" => "",
                                    "SHOW_DEFAULT_LOCATIONS" => "Y",
                                    "JS_CALLBACK" => "submitFormProxy",
                                    "JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),
                                    "JS_CONTROL_GLOBAL_ID" => intval($arProperties["ID"]),
                                    "DISABLE_KEYBOARD_INPUT" => 'Y',
                                    "PRECACHE_LAST_LEVEL" => "Y",
                                    "PRESELECT_TREE_TRUNK" => "Y",
                                    "SUPPRESS_ERRORS" => "Y"
                                ),
                                $locationTemplateP,
                                true,
                                'location-block-wrapper'
                            )?>
                            <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                                <div class="description">
                                    <?=$arProperties["DESCRIPTION"]?>
                                </div>
                            <?endif;?>
                        </div>
                        <div class="clr"></div>
                      <?*/?>
                    <?} elseif($arProperties["TYPE"] == "RADIO") {?>

                        <div class="label">
                            <?=$arProperties["NAME"]?>
                            <?if($arProperties["REQUIED_FORMATED"]=="Y"):?>
                                <span class="star">*</span>
                            <?endif;?>
                        </div>
                        <div class="block">
                            <?if(is_array($arProperties["VARIANTS"])) {
                                foreach($arProperties["VARIANTS"] as $arVariants):?>
                                    <input type="radio" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>" value="<?=$arVariants["VALUE"]?>" <?if($arVariants["CHECKED"] == "Y") echo " checked";?> />
                                    <label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"><?=$arVariants["NAME"]?></label>
                                    </br>
                                <?endforeach;
                            }?>
                            <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                                <div class="description">
                                    <?//=$arProperties["DESCRIPTION"]?>
                                </div>
                            <?endif;?>
                        </div>
                        <div class="clr"></div>

                    <?} elseif($arProperties["TYPE"] == "FILE") {?>

                        <div class="label">
                            <?=$arProperties["NAME"]?>
                            <?if($arProperties["REQUIED_FORMATED"]=="Y"):?>
                                <span class="star">*</span>
                            <?endif;?>
                        </div>
                        <div class="block">
                            <?=showFilePropertyField("ORDER_PROP_".$arProperties["ID"], $arProperties, $arProperties["VALUE"], $arProperties["SIZE1"])?>
                            <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                                <div class="description">
                                    <?//=$arProperties["DESCRIPTION"]?>
                                </div>
                            <?endif;?>
                        </div>
                        <div class="clr"></div>

                    <?}?>
                </div>
                <?if(CSaleLocation::isLocationProEnabled()):
                    $propertyAttributes = array(
                        'type' => $arProperties["TYPE"],
                        'valueSource' => $arProperties['SOURCE'] == 'DEFAULT' ? 'default' : 'form'
                    );
                    if(intval($arProperties['IS_ALTERNATE_LOCATION_FOR']))
                        $propertyAttributes['isAltLocationFor'] = intval($arProperties['IS_ALTERNATE_LOCATION_FOR']);

                    if(intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']))
                        $propertyAttributes['altLocationPropId'] = intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']);

                    if($arProperties['IS_ZIP'] == 'Y')
                        $propertyAttributes['isZip'] = true;?>

                    <script>
                        (window.top.BX || BX).saleOrderAjax.addPropertyDesc(<?=CUtil::PhpToJSObject(array(
                            'id' => intval($arProperties["ID"]),
                            'attributes' => $propertyAttributes
                        ))?>);
                    </script>
                <?endif;
            }
            ?>

            </div><?
        }

    }
function PrintPropsFormLocation($arSource = array(), $locationTemplate = ".default", $adress) {
    foreach($arSource as $arProperties) {    ?>
        <?/*if($arProperties["TYPE"] == "LOCATION") {?>

        <div class="block" >
            <label><input type="radio" checked /><?=GetMessage('DELIVERY_PICKUP')?></label> <br>
            <?$value = 0;
            if(is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0) {
                foreach($arProperties["VARIANTS"] as $arVariant) {
                    if($arVariant["SELECTED"] == "Y") {
                        $value = $arVariant["ID"];
                        break;
                    }
                }
            }

            if(CSaleLocation::isLocationProMigrated()) {
                $locationTemplateP = $locationTemplate == 'popup' ? 'search' : 'steps';
                $locationTemplateP = $_REQUEST['PERMANENT_MODE_STEPS'] == 1 ? 'steps' : $locationTemplateP;
            }

            if($locationTemplateP == 'steps'):?>
                <input placeholder="<?=$arProperties["DESCRIPTION"]?>" type="hidden" id="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" name="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" value="<?=($_REQUEST['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($arProperties["ID"])] ? '1' : '0')?>" />
            <?endif?>
            <?CSaleLocation::proxySaleAjaxLocationsComponent(
                array(
                    "AJAX_CALL" => "N",
                    "COUNTRY_INPUT_NAME" => "COUNTRY",
                    "REGION_INPUT_NAME" => "REGION",
                    "CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
                    "CITY_OUT_LOCATION" => "Y",
                    "LOCATION_VALUE" => $value,
                    "ORDER_PROPS_ID" => $arProperties["ID"],
                    "ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                    "SIZE1" => $arProperties["SIZE1"],
                ),
                array(
                    "ID" => $value,
                    "CODE" => "",
                    "SHOW_DEFAULT_LOCATIONS" => "Y",
                    "JS_CALLBACK" => "submitFormProxy",
                    "JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),
                    "JS_CONTROL_GLOBAL_ID" => intval($arProperties["ID"]),
                    "DISABLE_KEYBOARD_INPUT" => 'Y',
                    "PRECACHE_LAST_LEVEL" => "Y",
                    "PRESELECT_TREE_TRUNK" => "Y",
                    "SUPPRESS_ERRORS" => "Y"
                ),
                $locationTemplateP,
                true,
                'location-block-wrapper'
            )?>
            <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                <div class="description">
                    <?//=$arProperties["DESCRIPTION"]?>
                </div>
            <?endif;?>
            <span class="star">*</span>
            <p><?=GetMessage('DELIVERY_TEXT')?></p>

        </div>
        <div class="clr"></div>

        <?}*/?>
        <?if($arProperties["CODE"] == "ADDRESS"){?>
            <div class="label">
                <?=$arProperties["NAME"]?>
                <?if($arProperties["REQUIED_FORMATED"]=="Y"):?>
                    <span class="star">*</span>
                <?endif;?>
            </div>
            <div class="block">
                <textarea rows="<?=$arProperties["SIZE2"]?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
                <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                    <div class="description">
                        <?//=$arProperties["DESCRIPTION"]?>
                    </div>
                <?endif;?>
            </div>
            <div class="clr"></div>
        <?} else if($arProperties["CODE"] == 'TERMINAL_DL'){ ?>
            <div class="block terminals" >
                <input type="text" maxlength="250" style=" margin-top: 10px; " placeholder="<?=$arProperties["DESCRIPTION"]?>" size="<?=$arProperties["SIZE1"]?>" value="<?=$adress?>" name="<?=$arProperties["FIELD_NAME"]?>" />
                <span class="star">*</span>
                <p><?=GetMessage('DELIVERY_TEXT')?></p>
            </div>
        <?} 
        if($arProperties["CODE"] == 'TERMINALS'){ ?>
            <div class="block terminal_vs" >
                <input type="text" maxlength="250" style=" margin-top: 10px; " placeholder="<?=$arProperties["DESCRIPTION"]?>" size="<?=$arProperties["SIZE1"]?>" value="" name="<?=$arProperties["FIELD_NAME"]?>" />
                <span class="star">*</span>
                <p><?=GetMessage('DELIVERY_TEXT')?></p>
            </div>
        <?}
        ?>

        <?
    }
}


function PrintLocation($arSource = array(), $locationTemplate = ".default") {
    foreach($arSource as $arProperties) {    ?>
        <?if($arProperties["TYPE"] == "LOCATION") {?>
        <div class="label location_hide" >
            <?=$arProperties["NAME"]?>
            <?if($arProperties["REQUIED_FORMATED"]=="Y"):?>
                <span class="star">*</span>
            <?endif;?>
        </div>
        <div class="block" >
            <?$value = 0;
            if(is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0) {
                foreach($arProperties["VARIANTS"] as $arVariant) {
                    if($arVariant["SELECTED"] == "Y") {
                        $value = $arVariant["ID"];
                        break;
                    }
                }
            }

            if(CSaleLocation::isLocationProMigrated()) {
                $locationTemplateP = $locationTemplate == 'popup' ? 'search' : 'steps';
                $locationTemplateP = $_REQUEST['PERMANENT_MODE_STEPS'] == 1 ? 'steps' : $locationTemplateP;
            }

            if($locationTemplateP == 'steps'):?>
                <input placeholder="<?=$arProperties["DESCRIPTION"]?>" type="hidden" id="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" name="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" value="<?=($_REQUEST['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($arProperties["ID"])] ? '1' : '0')?>" />
            <?endif?>
            <?CSaleLocation::proxySaleAjaxLocationsComponent(
                array(
                    "AJAX_CALL" => "N",
                    "COUNTRY_INPUT_NAME" => "COUNTRY",
                    "REGION_INPUT_NAME" => "REGION",
                    "CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
                    "CITY_OUT_LOCATION" => "Y",
                    "LOCATION_VALUE" => $value,
                    "ORDER_PROPS_ID" => $arProperties["ID"],
                    "ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                    "SIZE1" => $arProperties["SIZE1"],
                ),
                array(
                    "ID" => $value,
                    "CODE" => "",
                    "SHOW_DEFAULT_LOCATIONS" => "Y",
                    "JS_CALLBACK" => "submitFormProxy",
                    "JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),
                    "JS_CONTROL_GLOBAL_ID" => intval($arProperties["ID"]),
                    "DISABLE_KEYBOARD_INPUT" => 'Y',
                    "PRECACHE_LAST_LEVEL" => "Y",
                    "PRESELECT_TREE_TRUNK" => "Y",
                    "SUPPRESS_ERRORS" => "Y"
                ),
                $locationTemplateP,
                true,
                'location-block-wrapper'
            )?>
            <?if(strlen(trim($arProperties["DESCRIPTION"])) > 0):?>
                <div class="description">
                    <?//=$arProperties["DESCRIPTION"]?>
                </div>
            <?endif;?>
            <p><?=GetMessage('TEXT_LOCATION')?></p>
        </div>
        <div class="clr"></div>

        <?}?>

        <?
    }
}
}?>