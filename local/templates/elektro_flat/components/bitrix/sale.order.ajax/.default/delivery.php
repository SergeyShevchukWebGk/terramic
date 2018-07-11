<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult["DELIVERY"])):?>
    <script type="text/javascript">
        function fShowStore(id, showImages, formWidth, siteId) {
            var strUrl = '<?=$templateFolder?>' + '/map.php';
            var strUrlPost = 'delivery=' + id + '&showImages=' + showImages + '&siteId=' + siteId;

            var storeForm = new BX.CDialog({
                'title': '<?=GetMessage('SOA_ORDER_GIVE')?>',
                head: '',
                'content_url': strUrl,
                'content_post': strUrlPost,
                'width': formWidth,
                'height':400,
                'resizable':false,
                'draggable':false
            });
            BX.addClass(BX('bx-admin-prefix'), 'popup-store');

            close = BX.findChildren(BX('bx-admin-prefix'), {className: 'bx-core-adm-icon-close'}, true);
            if(!!close && 0 < close.length) {
                for(i = 0; i < close.length; i++) {
                    close[i].innerHTML = "<i class='fa fa-times'></i>";
                }
            }

            var button = ['<button id="crmOk" class="btn_buy ppp" name="crmOk" onclick="GetBuyerStore();BX.WindowManager.Get().Close();"><?=GetMessage("SOA_POPUP_SAVE")?></button>', '<button id="cancel" class="btn_buy popdef" name="cancel" onclick="BX.WindowManager.Get().Close();"><?=GetMessage("SOA_POPUP_CANCEL")?></button>'];

            storeForm.ClearButtons();
            storeForm.SetButtons(button);
            storeForm.Show();
        }

        function GetBuyerStore() {
            $(function(){
                BX('BUYER_STORE').value = BX('POPUP_STORE_ID').value;
                var active_cheed = $('.stock_delivery input:checked').attr('class');
                  if(active_cheed == 'active'){
                        var buyer = BX('BUYER_STORE').value;
                        var buyer_1 = <?=BUYER_STORE_KRASNODAR?>;
                        var buyer_2 = <?=BUYER_STORE_SAMARA?>;
                        if(buyer == buyer_1){
                            $('.stock select option:nth-child(1)').attr("selected", "selected");
                        } else if(buyer == buyer_2){
                            $('.stock select option:nth-child(2)').attr("selected", "selected");
                        }

                  }
                  $('.stock_delivery input:checked').removeClass('active');
             //   BX('store_desc').innerHTML = BX('POPUP_STORE_NAME').value;
              //  BX.show(BX('select_store'));
            })
        }
    </script>

    <input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?=$arResult["BUYER_STORE"]?>" />
    <h2><?=GetMessage("SOA_TEMPL_DELIVERY")?></h2>   
    <div class="order-info">
        <div class="order-info_in">
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include/delivery_descr.php"
                ),
                $components,
                array("HIDE_ICONS"=>"Y")
            );?>
             <?PrintLocation($arProps, $arParams["TEMPLATE_LOCATION"]);?>
             <div class="stock_delivery sm">
                <div valign="top">
                    <label class="terminal image_1">
                        <input type="radio" name="type" class="table_d" value="<?=DELIVERY_SAMARA?>"/>
                        <img src="/local/templates/elektro_flat/images/logotip.jpg" >
                        <span class="FontWeight">Отправка со склада "Самара"</span>
                        <b>Вы можете заказать доставку вашего заказа транспортной компанией</b>
                    </label>
                </div>
                <table>
                <?$width = ($arParams["SHOW_STORES_IMAGES"] == "Y") ? 800 : 750;
                $i = 1;
                foreach($arResult["DELIVERY"] as $delivery_id => $arDelivery){?>
                 <?#if($arDelivery["SORT"] == 28){?>
                 <?if($arDelivery["SORT"] == 24){?>
                    <tr class="stock_delivery">
                    <td valign="top">
                        <?if(count($arDelivery["STORE"]) > 0):
                            $clickHandler = "onClick = \"fShowStore('".$arDelivery["ID"]."','".$arParams["SHOW_STORES_IMAGES"]."','".$width."','".SITE_ID."');submitForm();\"";
                        else:
                            $clickHandler = "onClick = \"submitForm();\"";
                        endif;?>
                        <input type="radio" data-sort="<?=$i++?>" data-delivery="<?=DELIVERY_SAMARA?>" data-for="sm" id="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" name="<?=htmlspecialcharsbx($arDelivery["FIELD_NAME"])?>" value="<?=$arDelivery["ID"]?>"<?if($arDelivery["CHECKED"]=="Y") echo " checked";?> <?=$clickHandler?>/>
                    </td>
                    <td valign="top">
                    <?
                    ?>
                        <label class="terminal" for="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" onclick="BX('ID_DELIVERY_ID_<?=$arDelivery["ID"]?>').checked=true;submitForm();">
                            <div class="iamage">
                            <?if(!empty($arDelivery["LOGOTIP"]["SRC"])):?>
                                <img src="<?=$arDelivery["LOGOTIP"]["SRC"]?>"  />
                            <?endif;?>
                            </div>
                            <div class="name">
                                <?$name = explode('(', $arDelivery["NAME"]);?>
                                Доставка транспортной компанией <?=htmlspecialcharsbx($name[0])?>
                            </div>

                         <?if($arDelivery["CHECKED"] == "Y"){?>
                        <p>
                            <?if(strlen($arDelivery["PERIOD_TEXT"])>0):
                            //    echo $arDelivery["PERIOD_TEXT"]."<br />";
                            endif;
                            if(DoubleVal($arDelivery["PRICE"]) > 0):
                            //    echo GetMessage("SALE_DELIV_PRICE")." ".$arDelivery["PRICE_FORMATED"].($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"]) ? " (".CCurrencyLang::CurrencyFormat($arDelivery["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arDelivery["CURRENCY"], true).")" : "")."<br />";
                            endif;
                            if(strlen($arDelivery["DESCRIPTION"])>0):
                                echo $arDelivery["DESCRIPTION"]."<br />"; 
                            endif;
                            if(count($arDelivery["STORE"]) > 0):?> 
                                <span id="select_store"<?if(strlen($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"]) <= 0) echo " style=\"display:none;\"";?>>
                                    <span class="select_store"><?=GetMessage('SOA_ORDER_GIVE_TITLE');?>: </span>
                                    <span class="ora-store" id="store_desc"><?=htmlspecialcharsbx($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"])?></span>
                                </span>
                            <?endif;?>
                        </p>
                        <br>
                        </label>
                        <?
                            PrintPropsFormLocation($arProps, $arParams["TEMPLATE_LOCATION"], "Самара");
                        }?>                         
                        <?$ar_delivery = object_to_array(json_decode($arDelivery["CALCULATE_DESCRIPTION"]))?>
                        <?if($ar_delivery["TREMINAL"]){?>
                            <div class="selivery_select">

                                <p>Если в Вашем населенном пункте отсутствуют терминал ТК "Деловые линии",
                                система автоматически выберет населенный пункт с терминалом.<br>
                                В случае если терминалов несколько, Вам необходимо выбрать один из представленных,
                                либо система сделает это автоматически</p>
                                    <select>
                                        <option selected
                                                data-worktables="<?=$ar_delivery["TREMINAL"]["worktables"]["worktable"][0]["timetable"]?>"
                                                data-longitude="<?=$ar_delivery["TREMINAL"]["longitude"]?>"
                                                data-latitude="<?=$ar_delivery["TREMINAL"]["latitude"]?>">
                                                <?=$ar_delivery["TREMINAL"]["fullAddress"]?></option>

                                        <?foreach($ar_delivery["TREMINAL"]["AR_TERMINAL"] as $terminal){ ?>
                                            <option
                                                data-worktables="<?=$terminal["worktables"]["worktable"][0]["timetable"]?>"
                                                data-longitude="<?=$terminal["longitude"]?>"
                                                data-latitude="<?=$terminal["latitude"]?>">
                                            <?=$terminal["fullAddress"]?></option>
                                        <?}?>
                                    </select> <br>
                                <span><?=$ar_delivery["TREMINAL"]["worktables"]["worktable"][0]["timetable"]?></span><br>
                                <a href="javascript:void(0)" onclick="ynadex_map(<?=$ar_delivery["TREMINAL"]["longitude"]?>, <?=$ar_delivery["TREMINAL"]["latitude"]?>, '<?=$ar_delivery["TREMINAL"]["fullAddress"]?>')" class="map_check">Показать на карте</a>
                            </div>
                            <br>
                           <div class="pack_wrap">
                               <p>Для сохранности товара мы рекомендуем заказать в транспортной компании дополнительную упаковку.
                               Наш магазин не несет ответственность за порчу товара при перевозке траспортной компанией.
                               Стоимость дополнительной упаковки (обрешетка) - 1300 руб/м3 (минимальная стоимость 700 руб.)</p>
                               <label for="pack_1"><input <?=($_SESSION["PACKING_HARD"] == "")? 'checked':''?> data-id="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" class="pack_param" id="pack_1" name="pack" type="radio" value="">Без дополнительной упаковки</label><br>
                               <label for="pack_2"><input <?=($_SESSION["PACKING_HARD"] == "Y")? 'checked':''?> data-id="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" class="pack_param" id="pack_2" name="pack" type="radio" value="Y">Заказать дополнительную упаковку</label>
                           </div>
                        <?}?>
                        </td>
                    </tr> 
                     <?}?>  
                <?}?>
                </table>
            </div>
            <div class="stock_delivery kr">
                <div valign="top">
                    <label class="terminal image_1">
                        <input type="radio" name="type" class="table_d" value="<?=DELIVERY_KRASNODAR?>"/>
                        <img src="/local/templates/elektro_flat/images/logotip.jpg" >
                            <span class="FontWeight">Отправка со склада "Краснодар"</span>
                        <b>Вы можете заказать доставку вашего заказа транспортной компанией</b>
                    </label>
                </div>
                <table>
                <?$width = ($arParams["SHOW_STORES_IMAGES"] == "Y") ? 800 : 750;
                $i = 1;
                foreach($arResult["DELIVERY"] as $delivery_id => $arDelivery){
                    ?>
                    <?if($arDelivery["SORT"] == 28){?>
                    <?#if($arDelivery["SORT"] == 24){?>
                        <tr class="stock_delivery">
                            <td valign="top">
                                <?if(count($arDelivery["STORE"]) > 0):
                                    $clickHandler = "onClick = \"fShowStore('".$arDelivery["ID"]."','".$arParams["SHOW_STORES_IMAGES"]."','".$width."','".SITE_ID."');submitForm();\"";
                                else:
                                    $clickHandler = "onClick = \"submitForm();\"";
                                endif;?>
                                <input type="radio" data-sort="<?=$i++?>" data-delivery="<?=DELIVERY_KRASNODAR?>" data-for="kr" id="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" name="<?=htmlspecialcharsbx($arDelivery["FIELD_NAME"])?>" value="<?=$arDelivery["ID"]?>"<?if($arDelivery["CHECKED"]=="Y") echo " checked";?> <?=$clickHandler?>/>
                            </td>
                            <td valign="top">
                            <?
                            ?>
                                <label class="terminal" for="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" onclick="BX('ID_DELIVERY_ID_<?=$arDelivery["ID"]?>').checked=true;submitForm();">
                                    <div class="iamage">
                                    <?if(!empty($arDelivery["LOGOTIP"]["SRC"])):?>
                                        <img src="<?=$arDelivery["LOGOTIP"]["SRC"]?>" width="<?=$arDelivery["LOGOTIP"]["WIDTH"]?>" height="<?=$arDelivery["LOGOTIP"]["HEIGHT"]?>" />
                                    <?endif;?>
                                    </div>
                                    <div class="name">
                                        <?$name = explode('(', $arDelivery["NAME"]);?>
                                        Доставка транспортной компанией <?=htmlspecialcharsbx($name[0])?>
                                    </div>

                                <?if($arDelivery["CHECKED"] == "Y"){?>
                                <p>
                                    <?if(strlen($arDelivery["PERIOD_TEXT"])>0):
                                     //   echo $arDelivery["PERIOD_TEXT"]."<br />";
                                    endif;
                                    if(DoubleVal($arDelivery["PRICE"]) > 0):
                                  //      echo GetMessage("SALE_DELIV_PRICE")." ".$arDelivery["PRICE_FORMATED"].($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"]) ? " (".CCurrencyLang::CurrencyFormat($arDelivery["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arDelivery["CURRENCY"], true).")" : "")."<br />";
                                    endif;
                                    if(strlen($arDelivery["DESCRIPTION"])>0):
                                        echo $arDelivery["DESCRIPTION"]."<br />";   
                                    endif;
                                    if(count($arDelivery["STORE"]) > 0):?>
                                        <span id="select_store"<?if(strlen($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"]) <= 0) echo " style=\"display:none;\"";?>>
                                            <span class="select_store"><?=GetMessage('SOA_ORDER_GIVE_TITLE');?>: </span>
                                            <span class="ora-store" id="store_desc"><?=htmlspecialcharsbx($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"])?></span>
                                        </span>
                                    <?endif;?>
                                </p>
                                <br>
                                </label>
                                <?
                                    PrintPropsFormLocation($arProps, $arParams["TEMPLATE_LOCATION"], "Краснодар");
                                }?> 
                                <?$ar_delivery = object_to_array(json_decode($arDelivery["CALCULATE_DESCRIPTION"]))?>
                                <?//arshow($ar_delivery)?>
                                <?if($ar_delivery["TREMINAL"]){?>
                                    <div class="selivery_select">

                                        <p>Если в Вашем населенном пункте отсутствуют терминал ТК "Деловые линии",
                                        система автоматически выберет населенный пункт с терминалом.<br>
                                        В случае если терминалов несколько, Вам необходимо выбрать один из представленных,
                                        либо система сделает это автоматически</p>
                                            <select>
                                                <option selected
                                                        data-worktables="<?=$ar_delivery["TREMINAL"]["worktables"]["worktable"][0]["timetable"]?>"
                                                        data-longitude="<?=$ar_delivery["TREMINAL"]["longitude"]?>"
                                                        data-latitude="<?=$ar_delivery["TREMINAL"]["latitude"]?>">
                                                        <?=$ar_delivery["TREMINAL"]["fullAddress"]?></option>

                                                <?foreach($ar_delivery["TREMINAL"]["AR_TERMINAL"] as $terminal){ ?>
                                                    <option
                                                        data-worktables="<?=$terminal["worktables"]["worktable"][0]["timetable"]?>"
                                                        data-longitude="<?=$terminal["longitude"]?>"
                                                        data-latitude="<?=$terminal["latitude"]?>">
                                                    <?=$terminal["fullAddress"]?></option>
                                                <?}?>
                                            </select> <br>
                                        <span><?=$ar_delivery["TREMINAL"]["worktables"]["worktable"][0]["timetable"]?></span><br>
                                        <a href="javascript:void(0)" onclick="ynadex_map(<?=$ar_delivery["TREMINAL"]["longitude"]?>, <?=$ar_delivery["TREMINAL"]["latitude"]?>, '<?=$ar_delivery["TREMINAL"]["fullAddress"]?>')" class="map_check">Показать на карте</a>
                                    </div>
                                    <br>
                                   <div class="pack_wrap">
                                       <p>Для сохранности товара мы рекомендуем заказать в транспортной компании дополнительную упаковку.
                                       Наш магазин не несет ответственность за порчу товара при перевозке траспортной компанией.
                                       Стоимость дополнительной упаковки (обрешетка) - 1300 руб/м3 (минимальная стоимость 700 руб.)</p>
                                       <label for="pack_1"><input <?=($_SESSION["PACKING_HARD"] == "")? 'checked':''?> data-id="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" class="pack_param" id="pack_1" name="pack" type="radio" value="">Без дополнительной упаковки</label><br>
                                       <label for="pack_2"><input <?=($_SESSION["PACKING_HARD"] == "Y")? 'checked':''?> data-id="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" class="pack_param" id="pack_2" name="pack" type="radio" value="Y">Заказать дополнительную упаковку</label>
                                   </div>
                                <?}?>
                            </td>
                        </tr>
                         <?}?>
                    <?};?>
                    </table>
                </div>
                <table>
                <?$width = ($arParams["SHOW_STORES_IMAGES"] == "Y") ? 800 : 750;
                foreach($arResult["DELIVERY"] as $delivery_id => $arDelivery){?>
                
                 <?if($arDelivery["SORT"] != 28 && $arDelivery["SORT"] != 24){?>
                    <tr class="stock_delivery pickup">
                    <td valign="top">
                        <?if(count($arDelivery["STORE"]) > 0):
                            $clickHandler = "onClick = \"fShowStore('".$arDelivery["ID"]."','".$arParams["SHOW_STORES_IMAGES"]."','".$width."','".SITE_ID."');submitForm();\"";
                        else:
                            $clickHandler = "onClick = \"submitForm();\"";
                        endif;?>
                        <input type="radio" data-for="sm" id="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" name="<?=htmlspecialcharsbx($arDelivery["FIELD_NAME"])?>" value="<?=$arDelivery["ID"]?>"<?if($arDelivery["CHECKED"]=="Y") echo " checked";?> <?=$clickHandler?>/>
                    </td>
                    <td valign="top">
                    <?
                    ?>
                        <label class="terminal" for="ID_DELIVERY_ID_<?=$arDelivery["ID"]?>" onclick="BX('ID_DELIVERY_ID_<?=$arDelivery["ID"]?>').checked=true;submitForm();">
                            <div class="">
                            <?if(!empty($arDelivery["LOGOTIP"]["SRC"])):?>
                                <img src="<?=$arDelivery["LOGOTIP"]["SRC"]?>" width="<?=$arDelivery["LOGOTIP"]["WIDTH"]?>" height="<?=$arDelivery["LOGOTIP"]["HEIGHT"]?>" />
                            <?endif;?>
                            </div>
                            <div class="name">
                                <?=htmlspecialcharsbx($arDelivery["NAME"])?>
                            </div>

                        <b>
                            <?if(strlen($arDelivery["PERIOD_TEXT"])>0):
                                echo $arDelivery["PERIOD_TEXT"]."<br />";
                            endif;
                            if(DoubleVal($arDelivery["PRICE"]) > 0):
                          //      echo GetMessage("SALE_DELIV_PRICE")." ".$arDelivery["PRICE_FORMATED"].($arSetting["REFERENCE_PRICE"]["VALUE"] == "Y" && !empty($arSetting["REFERENCE_PRICE_COEF"]["VALUE"]) ? " (".CCurrencyLang::CurrencyFormat($arDelivery["PRICE"] * $arSetting["REFERENCE_PRICE_COEF"]["VALUE"], $arDelivery["CURRENCY"], true).")" : "")."<br />";
                            endif;
                            if(strlen($arDelivery["DESCRIPTION"])>0):
                                echo $arDelivery["DESCRIPTION"]."<br />";
                            endif;
                            if(count($arDelivery["STORE"]) > 0):?>
                                <span id="select_store"<?if(strlen($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"]) <= 0) echo " style=\"display:none;\"";?>>
                                    <span class="select_store"><?=GetMessage('SOA_ORDER_GIVE_TITLE');?>: </span>
                                    <span class="ora-store" id="store_desc"><?=htmlspecialcharsbx($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"])?></span>
                                </span>
                            <?endif;?>
                        </b>
                        <br>
                        </label>
                        </td>
                    </tr> 
                     <?}?>  
                <?}?>
                </table>
            </div>
    <div class="popap_map">
        <span class="close">x</span>
        <div id='map'></div>
    </div>
</div>
<?endif;?>

