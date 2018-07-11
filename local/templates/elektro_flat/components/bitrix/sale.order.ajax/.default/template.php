<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $arSetting;

if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y") {
    if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y") {
        if(strlen($arResult["REDIRECT_URL"]) > 0) {
            $APPLICATION->RestartBuffer();?>
            <script type="text/javascript">
                window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
            </script>
            <?die();
        }
    }
}

CJSCore::Init(array('fx', 'popup', 'window', 'ajax'));?>

<a name="order_form"></a>

<div id="order_form_div" class="order-checkout">
    <NOSCRIPT>
        <div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
    </NOSCRIPT>

    <?if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N") {

        if(!empty($arResult["ERROR"])) {
            foreach($arResult["ERROR"] as $v)
                echo ShowError($v);
        } elseif(!empty($arResult["OK_MESSAGE"])) {
            foreach($arResult["OK_MESSAGE"] as $v)
                echo ShowNote($v);
        }

        include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");

    } else {

        if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y") {
            if(strlen($arResult["REDIRECT_URL"]) == 0) {
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
            }
        } else {?>
            <script type="text/javascript">
                function setCookie(name, value, options) {
                  options = options || {};

                  var expires = options.expires;

                  if (typeof expires == "number" && expires) {
                    var d = new Date();
                    d.setTime(d.getTime() + expires * 1000);
                    expires = options.expires = d;
                  }
                  if (expires && expires.toUTCString) {
                    options.expires = expires.toUTCString();
                  }

                  value = encodeURIComponent(value);

                  var updatedCookie = name + "=" + value;

                  for (var propName in options) {
                    updatedCookie += "; " + propName;
                    var propValue = options[propName];
                    if (propValue !== true) {
                      updatedCookie += "=" + propValue;
                    }
                  }

                  document.cookie = updatedCookie;
                }
                function getCookie(name) {
                  var matches = document.cookie.match(new RegExp(
                    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
                  ));
                  return matches ? decodeURIComponent(matches[1]) : undefined;
                }
                function deleteCookie(name) {
                  setCookie(name, "", {
                    expires: -1
                  })
                }
                deleteCookie('data-check');

                <?if(CSaleLocation::isLocationProEnabled()):
                    $city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();?>

                    BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
                        'source' => $this->__component->getPath().'/get.php',
                        'cityTypeId' => intval($city['ID']),
                        'messages' => array(
                            'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
                            'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'),
                            'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
                                '#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
                                '#ANCHOR_END#' => '</a>'
                            )).'</div>'
                        )
                    ))?>);
                <?endif?>

                var BXFormPosting = false;
                function submitForm(val) {
                    if(BXFormPosting === true)
                        return true;

                    BXFormPosting = true;
                    if(val != 'Y')
                        BX('confirmorder').value = 'N';

                    var orderForm = BX('ORDER_FORM');
                    BX.showWait();

                    <?if(CSaleLocation::isLocationProEnabled()):?>
                        BX.saleOrderAjax.cleanUp();
                    <?endif?>

                    BX.ajax.submit(orderForm, ajaxResult);

                    return true;
                }

                function ajaxResult(res) {
                    var orderForm = BX('ORDER_FORM');
                    try {
                        var json = JSON.parse(res);
                        BX.closeWait();

                        if(json.error) {
                            BXFormPosting = false;
                            return;
                        } else if(json.redirect) {
                            window.top.location.href = json.redirect;
                        }
                    } catch(e) {
                        BXFormPosting = false;
                        BX('order_form_content').innerHTML = res;

                        <?if(CSaleLocation::isLocationProEnabled()):?>
                            BX.saleOrderAjax.initDeferredControl();
                        <?endif?>
                    }

                    BX.closeWait();
                    BX.onCustomEvent(orderForm, 'onAjaxSuccess');

                    // добавление свйоство доставки в 1С
                    if($('#ID_DELIVERY_ID_3:checked').prop('checked')) {
                        var val_tope = $('.delivery_type select option').val();
                        $('.delivery_type select option').removeAttr('selected')
                        if(val_tope == '2222'){
                            $('.delivery_type select option').attr('selected', 'selected');
                        }
                    } else {
                        var val_tope = $('.delivery_type select option').val();
                        $('.delivery_type select option').removeAttr('selected')
                        if(val_tope == '1111'){
                            $('.delivery_type select option').attr('selected', 'selected');
                        }
                    }
                        /*
                        if($('.stock_delivery input:checked').val() == 31)
                        {
                            $('.stock_delivery input:checked').val(28);  
                        } 
                        */
                        var delivery_id = $('.stock_delivery input:checked').attr('data-delivery');
                        var stock_1 = <?=DELIVERY_KRASNODAR?>;
                        var stock_2 = <?=DELIVERY_SAMARA?>;

                        $('.agreement select option').removeAttr('selected');
                        if(delivery_id == stock_1){
                            $('.stock select option:nth-child(1)').attr("selected", "selected");
                        } else if(delivery_id == stock_2){
                            $('.stock select option:nth-child(2)').attr("selected", "selected");
                        } else {
                            $('.stock_delivery input:checked').addClass('active');
                        }

                     // изменение соглашения при выборе способа оплаты
                    var pay_sistem_nds = $('#ID_PAY_SYSTEM_ID_<?=PAY_SISTEM_NDS?>:checked').prop('checked');
                    if(pay_sistem_nds == true) {
                        $('.agreement select option').removeAttr('selected');
                        $('.agreement select option:nth-child(2)').attr("selected", "selected");
                        $('.organization select option').removeAttr("selected", "selected");
                        $('.organization select option:nth-child(2)').attr("selected", "selected");
                    } else {
                        $('.agreement select option').removeAttr('selected');
                        $('.agreement select option:nth-child(1)').attr("selected", "selected");
                        $('.organization select option').removeAttr("selected", "selected");
                        $('.organization select option:nth-child(1)').attr("selected", "selected");
                    }

                    $('body .selivery_select select').change(function(){
                            var worktables = $('.selivery_select select option:selected').attr('data-worktables');
                            var longitude = $('.selivery_select select option:selected').attr('data-longitude');
                            var latitude = $('.selivery_select select option:selected').attr('data-latitude');
                            var adress = $('.selivery_select select option:selected').text();
                            $('.selivery_select > span').html(worktables);
                            $('.selivery_select a.map_check').attr('onclick', "ynadex_map("+longitude+", "+latitude+", '"+adress.replace(/\s{2,}/g, ' ')+"')");
                         //   $('.selivery_select .terminals input').val(adress.replace(/\s{2,}/g, ' '));
                    })

                        
                    if($('#order_form_content .stock_delivery').length < 3){
                       //  $('.location_hide').show();
                    } else {
                       //  $('.location_hide').hide();
                    }
                    
                    // если не вывелись службы ДЛ    
                  /*  if($('#order_form_content .stock_delivery').length < 3 || getCookie('data-check') != 'yes'){
                        setTimeout(function() {
                       //     $('#order_form_content .payment_check input:checked').click();
                            document.cookie = "data-check=yes";
                            submitForm();
                         //   $('#order_form_content .payment_check input:checked').attr('data-id', 'yes');
                            
                        }, 1000);
                    }      */

                    $("#ORDER_PROP_14, #ORDER_PROP_20, #ORDER_PROP_15, #ORDER_PROP_3, #ORDER_PROP_46, #ORDER_PROP_33, #ORDER_PROP_28").mask("+7 (999) 999-99-99");
                    $("#ORDER_PROP_35").mask("99 99");
                    
                    var check_delivery = $('table .stock_delivery input:checked').attr('data-for');
                     $('.'+check_delivery+' .table_d').prop('checked', true);
                     $('.'+check_delivery+' > table').show();
                     
                     
                    var check_pickap =  $('.stock_delivery.pickup input').is(':checked');
                    if(check_pickap){
                        $('.stock_delivery > table').hide();
                        $('.stock_delivery .terminal input').prop('checked', false);
                    } else {
                        $('.stock_delivery.pickup input').prop('checked', false);
                    };
                    
                    var check_val = $('table .stock_delivery input:checked').attr('data-sort');
                    // проставление транспортной компании
                     if(check_val == 1){
                        $('.transport_service select option').removeAttr('selected');
                        $('.transport_service select option:nth-child(3)').attr("selected", "selected");
                     } else if(check_val == 2){
                        $('.transport_service select option').removeAttr('selected');
                        $('.transport_service select option:nth-child(1)').attr("selected", "selected");
                     } else if(check_val == 3){
                        $('.transport_service select option').removeAttr('selected');
                        $('.transport_service select option:nth-child(4)').attr("selected", "selected");
                     } else if(check_val == 4){
                        $('.transport_service select option').removeAttr('selected');
                        $('.transport_service select option:nth-child(2)').attr("selected", "selected");
                     }

                }
                

                // если не вывелись службы ДЛ
              /*  if($('#order_form_content .stock_delivery').length < 3 || getCookie('data-check') != 'yes'){
                    setTimeout(function() {
                        submitForm();
                        document.cookie = "data-check=yes";
                    }, 1000);
                }    */
                
                var pay_sistem_nds = $('#ID_PAY_SYSTEM_ID_<?=PAY_SISTEM_NDS?>:checked').prop('checked');
                if(pay_sistem_nds == true) {
                    $('.agreement select option').removeAttr('selected');
                    $('.agreement select option:nth-child(2)').attr("selected", "selected");
                    $('.organization select option').removeAttr("selected", "selected");
                    $('.organization select option:nth-child(2)').attr("selected", "selected");
                } else {
                    $('.agreement select option').removeAttr('selected');
                    $('.agreement select option:nth-child(1)').attr("selected", "selected");
                    $('.organization select option').removeAttr("selected", "selected");
                    $('.organization select option:nth-child(1)').attr("selected", "selected");
                }
   
                // добавление свйоство доставки в 1С
                $(function(){
                    if($('#ID_DELIVERY_ID_3:checked').prop('checked')) {
                        var val_tope = $('.delivery_type select option').val();
                        $('.delivery_type select option').removeAttr('selected')
                        if(val_tope == '2222'){
                            $('.delivery_type select option').attr('selected', 'selected');
                        }
                    } else {
                        var val_tope = $('.delivery_type select option').val();
                        $('.delivery_type select option').removeAttr('selected');
                        if(val_tope == '1111'){
                            $('.delivery_type select option').attr('selected', 'selected');
                        }
                    }

                    $('.wrap_terminal ').on('click', 'select option:selected', function(){
                        var value_adress = $(this).html();
                        $('#DELIVERY_PROP_45').val(value_adress);
                    })

                    $('body').on('click','.close', function(){
                         $('.popap_map').hide();
                    });
                    
                    $('body').on('click', 'div.stock_delivery', function(){
                       // $('div.stock_delivery .terminal input').prop('checked', false);
                        $(this).children('.table_d').prop('checked', true);
                        $('div.stock_delivery table').hide();
                        $(this).children('table').show();
                        if($(this).children('.table_d').context.spellcheck){
                            $('.stock_delivery.pickup input').prop('checked', false);
                        }
                    })
                     // подстановка пункта выдаче при выбооре самовывоза 
                    $('body').on('click', '.store_row', function(){
                       // $('div.stock_delivery .terminal input').prop('checked', false);
                        var name = $('.store_row.checked .name').text();      

                        $('body #select_store .ora-store').text(name);
                    })
                    
                    var check_val = $('table .stock_delivery input:checked').attr('data-sort');
                    // проставление транспортной компании
                     if(check_val == 1){
                        $('.transport_service select option').removeAttr('selected');
                        $('.transport_service select option:nth-child(3)').attr("selected", "selected");
                     } else if(check_val == 2){
                        $('.transport_service select option').removeAttr('selected');
                        $('.transport_service select option:nth-child(1)').attr("selected", "selected");
                     } else if(check_val == 3){
                        $('.transport_service select option').removeAttr('selected');
                        $('.transport_service select option:nth-child(4)').attr("selected", "selected");
                     } else if(check_val == 4){
                        $('.transport_service select option').removeAttr('selected');
                        $('.transport_service select option:nth-child(2)').attr("selected", "selected");
                     }
                     // переключение складов при выборе
                     $('body').on('click', '.store_row', function(){
                         GetBuyerStore();
                         submitForm('N'); 
                     })
                                          
                    //  
                })
                
                function sendThisComment() {
                var sendComment =  document.getElementById('ORDER_DESCRIPTION').value;
                console.log("sendComment "+sendComment); 
                $.ajax({
                    type: "POST",
                    datatype: "text",
                    url: 'https://terramic.ru/ajax/saveOrderComment.php',
                    data: {sendComment:sendComment},
                    success: function(respone){
                        console.log("respone "+respone);
                    }
               }); 
               }
                
                BX.ready(function () {

                    var submitBtn = BX('fire_event');
                    BX.bind(submitBtn, 'click', function(){
                        BX.onCustomEvent('my-event-name', []);
                    });

                    if (!BX.UserConsent)
                    {
                        return;
                    }
                    var control = BX.UserConsent.load(BX('ORDER_FORM'));
                    if (!control)
                    {
                        return;
                    }
                    
                    BX.addCustomEvent(
                        control,
                        BX.UserConsent.events.save,
                        function (data) {
                            submitForm('Y');
                        }
                    );
                    
                });

                function SetContact(profileId) {
                    BX("profile_change").value = "Y";
                    submitForm();
                }
            </script>

            <?if($_POST["is_ajax_post"] != "Y") {?>
                <form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
                    <?=bitrix_sessid_post()?>
                    <div id="order_form_content" class="myorders">
            <?} else {
                $APPLICATION->RestartBuffer();
            }

            if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y") {
                foreach($arResult["ERROR"] as $v)
                    echo ShowError($v);?>
                <script type="text/javascript">
                    top.BX.scrollToNode(top.BX('ORDER_FORM'));
                </script>
            <?}

            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");
            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");

            if($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d") {
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
            } else {
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
                include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
            }
            ?>
            <h2><?=GetMessage("SOA_TEMPL_SUM_ADIT_INFO")?></h2>
            <div class="order-info">
                <div class="order-info_in">
                
                    <label><?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?></label>
                    <br />
                    <textarea onchange="sendThisComment();" rows="5" cols="100" name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION"><?/*=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]*/?><?if($arResult["USER_VALS"]["ORDER_DESCRIPTION"] == 0)
                    {                      
                            echo trim($_SESSION["ORDER_DESCRIPTION"], " ");                           
                    }
                    else
                    {
                        $_SESSION["ORDER_DESCRIPTION"] = $arResult["USER_VALS"]["ORDER_DESCRIPTION"];
                        #echo $arResult["USER_VALS"]["ORDER_DESCRIPTION"];
                        echo trim($_SESSION["ORDER_DESCRIPTION"], " ");
                    }?>
                    <??></textarea> 
                    <div style="display: none;"><?arshow($_SESSION["ORDER_DESCRIPTION"])?></div>
                    <div style="display: none;"><?arshow($_POST)?></div>
                    <input type="hidden" name="" value="" />
                </div>
            </div>
            <?if ($arParams['USER_CONSENT'] == 'Y'):?>
                <span class="star_userconsent">*</span>
                 <?$APPLICATION->IncludeComponent(
                  "bitrix:main.userconsent.request",
                  "",
                  array(
                      "ID" => $arParams["USER_CONSENT_ID"],
                      "IS_CHECKED" => $arParams["USER_CONSENT_IS_CHECKED"],
                      "AUTO_SAVE" => "N",          
                      "IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
                      "SUBMIT_EVENT_NAME" => "my-event-name",
                      "REPLACE" => array(
                       'button_caption' => 'Оформить заказ',
                       'fields' => array('ФИО','Номер паспорта','Серия паспорта','Email', 'Телефон')
                      ),
                  )
                 );?>
             <br>
            <?endif;?>
            <?
            if($_POST["is_ajax_post"] != "Y") {?>
                    </div>
                    <input type="hidden" name="confirmorder" id="confirmorder" value="Y">
                    <input type="hidden" name="profile_change" id="profile_change" value="N">
                    <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
                    <input type="hidden" name="json" value="Y">
                    <div align="left">
                        <button name="submitbutton" id="fire_event" class="btn_buy popdef bt3" onclick=" return false;" value="<?=GetMessage('SOA_TEMPL_BUTTON')?>"><?=GetMessage("SOA_TEMPL_BUTTON")?></button>
                    </div>
                </form>
                <?if($arParams["DELIVERY_NO_AJAX"] == "N") {?>
                    <div style="display:none;">
                        <?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "",
                            array(),
                            null,
                            array('HIDE_ICONS' => 'Y')
                        );?>
                    </div>
                <?}
            } else {?>
                <script type="text/javascript">
                    top.BX('confirmorder').value = 'Y';
                    top.BX('profile_change').value = 'N';
                </script>
                <?die();
            }
        }
    }?>
</div>

<?if(CSaleLocation::isLocationProEnabled()):?>
    <div style="display: none">
        <?$APPLICATION->IncludeComponent("bitrix:sale.location.selector.steps", ".default",
            array(),
            false
        );?>
        <?$APPLICATION->IncludeComponent(
	"bitrix:sale.location.selector.search", 
	"geolocation", 
	array(
		"COMPONENT_TEMPLATE" => "geolocation",
		"ID" => "",
		"CODE" => "",
		"INPUT_NAME" => "LOCATION",
		"PROVIDE_LINK_BY" => "id",
		"FILTER_BY_SITE" => "N",
		"SHOW_DEFAULT_LOCATIONS" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"JS_CONTROL_GLOBAL_ID" => "",
		"JS_CALLBACK" => "",
		"SUPPRESS_ERRORS" => "N",
		"INITIALIZE_BY_GLOBAL_EVENT" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
    </div>
<?endif?>