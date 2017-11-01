
; /* Start:"a:4:{s:4:"full";s:101:"/local/templates/elektro_flat/components/bitrix/sale.basket.basket/.default/script.js?150775987014365";s:6:"source";s:85:"/local/templates/elektro_flat/components/bitrix/sale.basket.basket/.default/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function updateBasketTable(basketItemId, res) {
	//update product params after recalculation
	if(!!res.BASKET_DATA) {
		for(id in res.BASKET_DATA.GRID.ROWS) {
			if(res.BASKET_DATA.GRID.ROWS.hasOwnProperty(id)) {
				var item = res.BASKET_DATA.GRID.ROWS[id];				

				/***ITEM_PRICE***/
				if(BX("price_" + id))
					BX("price_" + id).innerHTML = item.PRICE_FORMATED;

				/***ITEM_REFERENCE_PRICE***/
				if(BX("reference-price_" + id)) {
					var itemReferenceCont = $(BX("reference-price_" + id)),
						itemReferenceCoef = itemReferenceCont.data("reference-coef"),
						dec_point = itemReferenceCont.data("dec-point"),
						thousands_sep = itemReferenceCont.data("separator"),
						decimals = itemReferenceCont.data("reference-decimal");
					if(itemReferenceCont.data("hide-zero") == "Y") {			
						if(Math.abs(parseFloat(item.PRICE * itemReferenceCoef).toFixed(decimals)) == Math.abs(parseFloat(item.PRICE * itemReferenceCoef).toFixed(0))) {						
							decimals = 0;								
						}
					}
					BX("itemReferenceVal_" + id).innerHTML = number_format(item.PRICE * itemReferenceCoef, decimals, dec_point, thousands_sep);
				}
				
				/***ITEM_OLD_PRICE***/
				if(BX("old-price_" + id))
					BX("old-price_" + id).innerHTML = (item.FULL_PRICE_FORMATED != item.PRICE_FORMATED) ? item.FULL_PRICE_FORMATED : '';				

				/***ITEM_UNIT***/
				if(BX("unit_" + id))
					BX("unit_" + id).innerHTML = item.MEASURE_TEXT;
				
				/***ITEM_SUM***/
				if(BX("cart-item-summa_" + id)) {
					var itemSumCont,
						itemSumOld,
						itemSumCurr,
						decimals;

					itemSumCont = $(BX("cart-item-summa_" + id));

					itemSumOld = itemSumCont.data("itemsum");					
					itemSumCont.data("itemsum", (item.PRICE * item.QUANTITY));
					itemSumCurr = itemSumCont.data("itemsum");

					if(itemSumCurr != itemSumOld) {
						decimals = itemSumCont.data("decimal");
						if(itemSumCont.data("hide-zero") == "Y") {			
							if(Math.abs(parseFloat(itemSumCurr).toFixed(decimals)) == Math.abs(parseFloat(itemSumCurr).toFixed(0))) {						
								decimals = 0;								
							}
						}
						var options = {
							useEasing: false,
							useGrouping: true,
							separator: itemSumCont.data("separator"),
							decimal: itemSumCont.data("dec-point")
						}
						var counter = new countUp("itemSumVal_" + id, itemSumOld, itemSumCurr, decimals, 0.5, options);
						counter.start();
					}

					/***ITEM_REFERENCE_SUM***/
					if(BX("itemReferenceSumVal_" + id)) {
						var itemReferenceSumCoef,
							itemReferenceSumOld,
							itemReferenceSumCurr;					
					
						itemReferenceSumCoef = itemSumCont.data("itemreferencesumcoef");
						itemReferenceSumOld = itemSumCont.data("itemreferencesum");					
						itemSumCont.data("itemreferencesum", (item.PRICE * item.QUANTITY * itemReferenceSumCoef));
						itemReferenceSumCurr = itemSumCont.data("itemreferencesum");

						if(itemReferenceSumCurr != itemReferenceSumOld) {
							decimals = itemSumCont.data("reference-decimal");
							if(itemSumCont.data("hide-zero") == "Y") {			
								if(Math.abs(parseFloat(itemReferenceSumCurr).toFixed(decimals)) == Math.abs(parseFloat(itemReferenceSumCurr).toFixed(0))) {						
									decimals = 0;								
								}
							}
							var options = {
								useEasing: false,
								useGrouping: true,
								separator: itemSumCont.data("separator"),
								decimal: itemSumCont.data("dec-point")
							}
							var counter = new countUp("itemReferenceSumVal_" + id, itemReferenceSumOld, itemReferenceSumCurr, decimals, 0.5, options);
							counter.start();
						}
					}					
				}

				//if the quantity was set by user to 0 or was too much, we need to show corrected quantity value from ajax response
				if(BX("QUANTITY_" + id)) {
					BX("QUANTITY_" + id).value = item.QUANTITY;
				}
			}
		}
	}

	//update coupon info
	if(!!res.BASKET_DATA)
		couponListUpdate(res.BASKET_DATA);

	//update total basket values
	if(!!res.BASKET_DATA) {
		/***ALL_SUM***/
		if(BX("cart-allsum")) {
			var allSumCont,
				allSumOld,
				allSumCurr,
				decimals;

			allSumCont = $(BX("cart-allsum"));
			
			allSumOld = allSumCont.data("allsum");		
			allSumCont.data("allsum", res["BASKET_DATA"]["allSum"]);
			allSumCurr = allSumCont.data("allsum");

			if(allSumCurr != allSumOld) {
				decimals = allSumCont.data("decimal");
				if(allSumCont.data("hide-zero") == "Y") {			
					if(Math.abs(parseFloat(allSumCurr).toFixed(decimals)) == Math.abs(parseFloat(allSumCurr).toFixed(0))) {						
						decimals = 0;										
					}
				}
				var options = {
					useEasing: false,
					useGrouping: true,
					separator: allSumCont.data("separator"),
					decimal: allSumCont.data("dec-point")
				}
				var counter = new countUp("allSumVal", allSumOld, allSumCurr, decimals, 0.5, options);
				counter.start();
			}

			/***ALL_REFERENCE_SUM***/
			if(BX("allReferenceSumVal")) {
				var allReferenceSumCoef,
					allReferenceSumOld,
					allReferenceSumCurr;

				allReferenceSumCoef = allSumCont.data("allreferencesumcoef");
				allReferenceSumOld = allSumCont.data("allreferencesum");		
				allSumCont.data("allreferencesum", (res["BASKET_DATA"]["allSum"] * allReferenceSumCoef));
				allReferenceSumCurr = allSumCont.data("allreferencesum");

				if(allReferenceSumCurr != allReferenceSumOld) {
					decimals = allSumCont.data("reference-decimal");
					if(allSumCont.data("hide-zero") == "Y") {			
						if(Math.abs(parseFloat(allReferenceSumCurr).toFixed(decimals)) == Math.abs(parseFloat(allReferenceSumCurr).toFixed(0))) {						
							decimals = 0;										
						}
					}
					var options = {
						useEasing: false,
						useGrouping: true,
						separator: allSumCont.data("separator"),
						decimal: allSumCont.data("dec-point")
					}
					var counter = new countUp("allReferenceSumVal", allReferenceSumOld, allReferenceSumCurr, decimals, 0.5, options);
					counter.start();
				}
			}
			
			BX.onCustomEvent("OnBasketChange");
		}
	}
}

function number_format(number, decimals, dec_point, thousands_sep) {
	number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === "undefined") ? "," : thousands_sep,
		dec = (typeof dec_point === "undefined") ? "." : dec_point,
		s = "",
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return "" + Math.round(n * k) / k;
		};
	s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
	if(s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if((s[1] || "").length < prec) {
		s[1] = s[1] || "";
		s[1] += new Array(prec - s[1].length + 1).join("0");
	}
	return s.join(dec);
}

function couponCreate(couponBlock, oneCoupon) {
	var couponClass = 'disabled';

	if(!BX.type.isElementNode(couponBlock))
		return;
	if(oneCoupon.JS_STATUS === 'BAD')
		couponClass = 'bad';
	else if(oneCoupon.JS_STATUS === 'APPLYED')
		couponClass = 'good';

	couponBlock.appendChild(BX.create(
		'div',
		{
			props: {
				className: 'bx_ordercart_coupon'
			},
			children: [				
				BX.create(
					'input',
					{
						props: {							
							type: 'hidden',
							value: oneCoupon.COUPON,
							name: 'OLD_COUPON[]'
						}						
					}
				),
				BX.create(
					'div',
					{
						props: {
							className: 'old_coupon ' + couponClass
						},
						html: oneCoupon.COUPON + ' ' + oneCoupon.JS_CHECK_CODE
					}
				),
				BX.create(
					'span',
					{
						props: {
							className: 'close ' + couponClass
						},
						attrs: {
							'data-coupon': oneCoupon.COUPON
						},
						children: [
							BX.create(
								'i',
								{
									props: {
										className: 'fa fa-times'
									}
								}
							)
						]
					}
				),
				BX.create(
					'div',
					{
						props: {
							className: 'clr'
						}						
					}
				)
			]
		}
	));
}

function couponListUpdate(res) {
	var couponBlock,		
		fieldCoupon,
		couponsCollection,
		couponFound,
		i,
		j,
		key;

	if(!!res && typeof res !== 'object') {
		return;
	}

	couponBlock = BX('cart-coupon');
	if(!!couponBlock) {
		if(!!res.COUPON_LIST && BX.type.isArray(res.COUPON_LIST)) {
			fieldCoupon = BX('coupon');
			if(!!fieldCoupon) {
				fieldCoupon.value = '';
			}
			couponsCollection = BX.findChildren(couponBlock, { tagName: 'input', property: { name: 'OLD_COUPON[]' } }, true);			

			if(!!couponsCollection) {
				if(BX.type.isElementNode(couponsCollection)) {
					couponsCollection = [couponsCollection];
				}
				for(i = 0; i < res.COUPON_LIST.length; i++) {
					couponFound = false;
					key = -1;
					for(j = 0; j < couponsCollection.length; j++) {
						if(couponsCollection[j].value === res.COUPON_LIST[i].COUPON) {
							couponFound = true;
							key = j;
							couponsCollection[j].couponUpdate = true;
							break;
						}
					}					
					if(!couponFound)
						couponCreate(couponBlock, res.COUPON_LIST[i]);
				}
				for(j = 0; j < couponsCollection.length; j++) {
					if(typeof (couponsCollection[j].couponUpdate) === 'undefined' || !couponsCollection[j].couponUpdate) {
						BX.remove(couponsCollection[j].parentNode);
						couponsCollection[j] = null;
					} else {
						couponsCollection[j].couponUpdate = null;
					}
				}
			} else {
				for(i = 0; i < res.COUPON_LIST.length; i++) {
					couponCreate(couponBlock, res.COUPON_LIST[i]);
				}
			}
		}
	}
	couponBlock = null;
}

function checkOut() {
	if(!!BX("coupon"))
		BX("coupon").disabled = true;	
	BX("basket_form").submit();	
	return true;
}

function enterCoupon() {
	var newCoupon = BX("coupon");
	if(!!newCoupon && !!newCoupon.value)
		recalcBasketAjax({"coupon" : newCoupon.value});
}

function updateQuantity(controlId, basketId, ratio, bUseFloatQuantity) {
	var oldVal = BX(controlId).defaultValue,
		newVal = parseFloat(BX(controlId).value) || 0,
		bIsCorrectQuantityForRatio = false;

	if(ratio === 0 || ratio == 1) {
		bIsCorrectQuantityForRatio = true;
	} else {
		var newValInt = newVal * 10000,
			ratioInt = ratio * 10000,
			reminder = newValInt % ratioInt,
			newValRound = parseInt(newVal);

		if(reminder === 0) {
			bIsCorrectQuantityForRatio = true;
		}
	}

	var bIsQuantityFloat = false;

	if(parseInt(newVal) != parseFloat(newVal)) {
		bIsQuantityFloat = true;
	}

	newVal = (bUseFloatQuantity === false && bIsQuantityFloat === false) ? parseInt(newVal) : parseFloat(newVal).toFixed(2);

	if(bIsCorrectQuantityForRatio) {
		BX(controlId).defaultValue = newVal;		
		
		BX("QUANTITY_" + basketId).value = newVal;
		
		recalcBasketAjax({});
	} else {
		newVal = getCorrectRatioQuantity(newVal, ratio, bUseFloatQuantity);
		
		if(newVal != oldVal) {			
			BX("QUANTITY_" + basketId).value = newVal;			
			
			recalcBasketAjax({});
		} else {
			BX(controlId).value = oldVal;
		}
	}
}

//used when quantity is changed by clicking on arrows
function setQuantity(basketId, ratio, sign, bUseFloatQuantity) {
	var curVal = parseFloat(BX("QUANTITY_" + basketId).value),
		newVal;

	newVal = (sign == 'up') ? curVal + ratio : curVal - ratio;

	if(newVal < 0)
		newVal = 0;

	if(bUseFloatQuantity) {
		newVal = newVal.toFixed(2);
	}

	if(ratio > 0 && newVal < ratio) {
		newVal = ratio;
	}

	if(!bUseFloatQuantity && newVal != newVal.toFixed(2)) {
		newVal = newVal.toFixed(2);
	}

	newVal = getCorrectRatioQuantity(newVal, ratio, bUseFloatQuantity);

	BX("QUANTITY_" + basketId).value = newVal;
	BX("QUANTITY_" + basketId).defaultValue = newVal;

	updateQuantity('QUANTITY_' + basketId, basketId, ratio, bUseFloatQuantity);
}

function getCorrectRatioQuantity(quantity, ratio, bUseFloatQuantity) {
	var newValInt = quantity * 10000,
		ratioInt = ratio * 10000,
		reminder = newValInt % ratioInt,
		result = quantity,
		bIsQuantityFloat = false,
		i;
	ratio = parseFloat(ratio);

	if(reminder === 0) {
		return result;
	}

	if(ratio !== 0 && ratio != 1) {
		for(i = ratio, max = parseFloat(quantity) + parseFloat(ratio); i <= max; i = parseFloat(parseFloat(i) + parseFloat(ratio)).toFixed(2)) {
			result = i;
		}
	} else if(ratio === 1) {
		result = quantity | 0;
	}

	if(parseInt(result, 10) != parseFloat(result)) {
		bIsQuantityFloat = true;
	}

	result = (bUseFloatQuantity === false && bIsQuantityFloat === false) ? parseInt(result, 10) : parseFloat(result).toFixed(2);

	return result;
}

function recalcBasketAjax(params) {
	BX.showWait();

	var property_values = {},
		action_var = BX('action_var').value,		
		items = BX.findChildren(BX('cart_equipment'), {className: 'tr'}, true),
		shelveItems = BX.findChildren(BX('shelve_equipment'), {className: 'tr'}, true),
		postData,
		i;	

	postData = {
		'sessid': BX.bitrix_sessid(),
		'site_id': BX.message('SITE_ID'),
		'props': property_values,
		'action_var': action_var,
		'select_props': BX('column_headers').value,
		'offers_props': BX('offers_props').value,
		'quantity_float': BX('quantity_float').value,
		'count_discount_4_all_quantity': BX('count_discount_4_all_quantity').value,
		'price_vat_show_value': BX('price_vat_show_value').value,
		'hide_coupon': BX('hide_coupon').value,
		'use_prepayment': BX('use_prepayment').value
	};
	postData[action_var] = 'recalculate';
	if(!!params && typeof params === 'object') {
		for(i in params) {
			if(params.hasOwnProperty(i))
				postData[i] = params[i];
		}
	}

	if(!!items && items.length > 0) {
		for(i = 0; items.length > i; i++)
			postData['QUANTITY_' + items[i].id] = BX('QUANTITY_' + items[i].id).value;
	}

	if(!!shelveItems && shelveItems.length > 0) {
		for(i = 0; shelveItems.length > i; i++) {
			postData['DELAY_' + shelveItems[i].id] = 'N';
postData['QUANTITY_' + shelveItems[i].id] = BX('QUANTITY_' + shelveItems[i].id).value + 1;
		}
	}

	BX.ajax({
		url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
		method: 'POST',
		data: postData,
		dataType: 'json',
		onsuccess: function(result) {
			BX.closeWait();
			updateBasketTable(null, result);
		}
	});
}

function deleteCoupon(e) {
	var target = BX.proxy_context,
		value;

	if(!!target && target.hasAttribute('data-coupon')) {
		value = target.getAttribute('data-coupon');
		if(!!value && value.length > 0) {
			recalcBasketAjax({'delete_coupon' : value});
		}
	}
}

BX.ready(function() {
	var couponBlock = BX('cart-coupon');
	if(!!couponBlock)
		BX.bindDelegate(couponBlock, 'click', { 'attribute': 'data-coupon' }, BX.delegate(function(e){deleteCoupon(e); }, this));
});
/* End */
;
; /* Start:"a:4:{s:4:"full";s:107:"/local/templates/elektro_flat/components/bitrix/catalog.bigdata.products/.default/script.js?150706317327313";s:6:"source";s:91:"/local/templates/elektro_flat/components/bitrix/catalog.bigdata.products/.default/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
(function (window) {

	if(!!window.JCCatalogBigdataProducts) {
		return;
	}	

	var BasketButton = function(params) {
		BasketButton.superclass.constructor.apply(this, arguments);		
		this.buttonNode = BX.create("button", {
			text: params.text,
			attrs: { 
				name: params.name,
				className: params.className
			},
			events : this.contextEvents
		});
	};
	BX.extend(BasketButton, BX.PopupWindowButton);

	window.JCCatalogBigdataProducts = function (arParams) {
		this.productType = 0;
		this.visual = {
			ID: '',
			PICT_ID: '',
			PRICE_ID: '',
			BUY_ID: '',
		};
		this.product = {
			name: '',
			id: 0,
			pict: {},
		};		

		this.offersView = null;
		this.offers = [];
		this.offerNum = 0;
		this.treeProps = [];
		this.obTreeRows = [];
		this.selectedValues = {};
		this.selectProps = [];
		this.obSelectRows = [];

		this.obProduct = null;
		this.obPict = null;
		this.obPrice = null;
		this.obBuy = null;
		this.obTree = null;
		this.obSelect = null;
		this.obBuyBtn = null;

		this.obPopupWin = null;
		this.basketParams = {};
			
		this.errorCode = 0;

		if('object' === typeof arParams) {
			this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
			this.visual = arParams.VISUAL;

			switch (this.productType) {
				case 1://product
				case 2://set
					if(!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT)) {
						this.product.name = arParams.PRODUCT.NAME;
						this.product.pict = arParams.PRODUCT.PICT;
						this.product.id = arParams.PRODUCT.ID;
						if(!!arParams.SELECT_PROPS) {
							this.selectProps = arParams.SELECT_PROPS;
						}
					} else {
						this.errorCode = -1;
					}
					break;
				case 3://sku
					if(!!arParams.OFFERS && BX.type.isArray(arParams.OFFERS)) {
						if(!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT)) {
							this.product.name = arParams.PRODUCT.NAME;
							this.product.pict = arParams.PRODUCT.PICT;
							this.product.id = arParams.PRODUCT.ID;
							if(!!arParams.SELECT_PROPS) {
								this.selectProps = arParams.SELECT_PROPS;
							}
						}
						if(!!arParams.OFFERS_VIEW) {
							this.offersView = arParams.OFFERS_VIEW;
						}
						this.offers = arParams.OFFERS;
						this.offerNum = 0;
						if(!!arParams.OFFER_SELECTED) {
							this.offerNum = parseInt(arParams.OFFER_SELECTED, 10);
						}
						if(isNaN(this.offerNum)) {
							this.offerNum = 0;
						}
						if(!!arParams.TREE_PROPS) {
							this.treeProps = arParams.TREE_PROPS;
						}						
					} else {
						this.errorCode = -1;
					}
					break;
				default:
					this.errorCode = -1;
			}
		}
		if(0 === this.errorCode) {
			BX.ready(BX.delegate(this.Init,this));
		}
	};

	window.JCCatalogBigdataProducts.prototype.Init = function() {
		var i = 0,
			strPrefix = '',
			selPrefix = '',
			TreeItems = null,
			SelectItems = null,
			buyBtnItems = null;

		this.obProduct = BX(this.visual.ID);
		if(!this.obProduct) {
			this.errorCode = -1;
		}		
		
		if(3 === this.productType) {
			if("LIST" !== this.offersView) {
				this.obPict = BX(this.visual.PICT_ID);
				if(!this.obPict) {
					this.errorCode = -16;
				}

				this.obPrice = BX(this.visual.PRICE_ID);
				if(!this.obPrice) {
					this.errorCode = -16;
				}

				this.obBuy = BX(this.visual.BUY_ID);
				if(!this.obBuy) {
					this.errorCode = -16;
				}

				if(!!this.visual.TREE_ID) {
					this.obTree = BX(this.visual.TREE_ID);
					if(!this.obTree) {
						this.errorCode = -256;
					}
					strPrefix = this.visual.TREE_ITEM_ID;
					for(i = 0; i < this.treeProps.length; i++) {
						this.obTreeRows[i] = {
							LIST: BX(strPrefix+this.treeProps[i].ID+'_list'),
							CONT: BX(strPrefix+this.treeProps[i].ID+'_cont')
						};
						if(!this.obTreeRows[i].LIST || !this.obTreeRows[i].CONT) {
							this.errorCode = -512;
							break;
						}
					}
				}
			}
		}

		if(!!this.visual.SELECT_PROP_ID) {
			this.obSelect = BX(this.visual.SELECT_PROP_ID);
			if(!this.obSelect) {
				this.errorCode = -256;
			}
			selPrefix = this.visual.SELECT_PROP_ITEM_ID;
			for(i = 0; i < this.selectProps.length; i++) {
				this.obSelectRows[i] = BX(selPrefix+this.selectProps[i].ID);
				if(!this.obSelectRows[i]) {
					this.errorCode = -512;
					break;
				}
			}
		}
		
		if(!!this.visual.BTN_BUY_ID) {			
			this.obBuyBtn = BX(this.visual.BTN_BUY_ID);			
		}
		
		if(0 === this.errorCode) {
			switch (this.productType) {
				case 1://product
				case 2://set
					if(!!this.obSelect) {
						SelectItems = BX.findChildren(this.obSelect, {tagName: 'li'}, true);
						if(!!SelectItems && 0 < SelectItems.length) {
							for(i = 0; i < SelectItems.length; i++) {
								BX.bind(SelectItems[i], 'click', BX.delegate(this.SelectProp, this));
							}
							this.SetSelectCurrent();
						}
					}
					break;
				case 3://sku
					if("LIST" !== this.offersView) {
						TreeItems = BX.findChildren(this.obTree, {tagName: 'li'}, true);
						if(!!TreeItems && 0 < TreeItems.length) {
							for(i = 0; i < TreeItems.length; i++) {
								BX.bind(TreeItems[i], 'click', BX.delegate(this.SelectOfferProp, this));
							}
						}
						this.SetCurrent();
					}

					if(!!this.obSelect) {
						SelectItems = BX.findChildren(this.obSelect, {tagName: 'li'}, true);
						if(!!SelectItems && 0 < SelectItems.length) {
							for(i = 0; i < SelectItems.length; i++) {
								BX.bind(SelectItems[i], 'click', BX.delegate(this.SelectProp, this));
							}
							this.SetSelectCurrent();
						}					
					}
					break;
			}
		}
		
		switch(this.productType) {
			case 1://product
			case 2://set
				if(!!this.obBuyBtn)
					BX.bind(this.obBuyBtn, "click", BX.delegate(this.Add2Basket, this));
				break;
			case 3://sku
				if("LIST" !== this.offersView) {
					if(!!this.obBuy) {
						buyBtnItems = BX.findChildren(this.obBuy, {tagName: "button"}, true);
						if(!!buyBtnItems && 0 < buyBtnItems.length) {
							for(i = 0; i < buyBtnItems.length; i++) {
								BX.bind(buyBtnItems[i], "click", BX.delegate(this.Add2Basket, this));
							}
						}					
					}
				} else {
					if(!!this.obProduct) {
						buyBtnItems = BX.findChildren(this.obProduct, {tagName: "button", attribute: {name: "add2basket"}}, true);
						if(!!buyBtnItems && 0 < buyBtnItems.length) {
							for(i = 0; i < buyBtnItems.length; i++) {
								BX.bind(buyBtnItems[i], "click", BX.delegate(this.Add2Basket, this));
							}
						}
					}
				}
				break;
		}		
	};

	window.JCCatalogBigdataProducts.prototype.SelectProp = function() {
		var i = 0,
		RowItems = null,
		ActiveItems = null,
		selPropValueArr = [],		
		selPropValue = null,		
		MinselDelayOnclick = null,
		MinselDelayOnclickArr = [],
		MinselDelayOnclickNew = null,
		selDelayOnclick = null,
		selDelayOnclickArr = [],
		selDelayOnclickNew = null,
		target = BX.proxy_context;

		if(!!target && target.hasAttribute('data-select-onevalue')) {
			RowItems = BX.findChildren(target.parentNode, {tagName: 'li'}, false);
			if(!!RowItems && 0 < RowItems.length) {
				for(i = 0; i < RowItems.length; i++) {
					BX.removeClass(RowItems[i], 'active');
				}
			}
			BX.addClass(target, 'active');
		}

		ActiveItems = BX.findChildren(this.obSelect, {tagName: 'li', className: 'active'}, true);
		if(!!ActiveItems && 0 < ActiveItems.length) {
			for(i = 0; i < ActiveItems.length; i++) {
				selPropValueArr[i] = ActiveItems[i].getAttribute('data-select-onevalue');
			}
		}
		selPropValue = selPropValueArr.join('||');
		
		if(!!this.offers && 0 < this.offers.length) {
			for(i = 0; i < this.offers.length; i++) {
				/*CART*/
				if(!!BX('select_props_'+this.visual.ID+'_'+this.offers[i].ID))
					BX('select_props_'+this.visual.ID+'_'+this.offers[i].ID).value = selPropValue;				
				/*MIN_DELAY*/
				if(!!BX('catalog-item-delay-min-'+this.visual.ID+'-'+this.offers[i].ID)) {
					MinselDelayOnclick = BX('catalog-item-delay-min-'+this.visual.ID+'-'+this.offers[i].ID).getAttribute('onclick');
					MinselDelayOnclickArr = MinselDelayOnclick.split("',");
					MinselDelayOnclickArr[3] = " '"+selPropValue;
					MinselDelayOnclickNew = MinselDelayOnclickArr.join("',");
					BX('catalog-item-delay-min-'+this.visual.ID+'-'+this.offers[i].ID).setAttribute('onclick', MinselDelayOnclickNew);					
				}
				/*DELAY*/
				if(!!BX('catalog-item-delay-'+this.visual.ID+'-'+this.offers[i].ID)) {
					selDelayOnclick = BX('catalog-item-delay-'+this.visual.ID+'-'+this.offers[i].ID).getAttribute('onclick');
					selDelayOnclickArr = selDelayOnclick.split("',");
					selDelayOnclickArr[3] = " '"+selPropValue;
					selDelayOnclickNew = selDelayOnclickArr.join("',");
					BX('catalog-item-delay-'+this.visual.ID+'-'+this.offers[i].ID).setAttribute('onclick', selDelayOnclickNew);
				}
			}
		} else {
			/*CART*/
			if(!!BX('select_props_'+this.visual.ID))
				BX('select_props_'+this.visual.ID).value = selPropValue;
			/*DELAY*/
			if(!!BX('catalog-item-delay-'+this.visual.ID)) {
				selDelayOnclick = BX('catalog-item-delay-'+this.visual.ID).getAttribute('onclick');
				selDelayOnclickArr = selDelayOnclick.split("',");
				selDelayOnclickArr[3] = " '"+selPropValue;
				selDelayOnclickNew = selDelayOnclickArr.join("',");
				BX('catalog-item-delay-'+this.visual.ID).setAttribute('onclick', selDelayOnclickNew);
			}
		}
	}

	window.JCCatalogBigdataProducts.prototype.SelectOfferProp = function() {
		var i = 0,
		value = '',
		strTreeValue = '',
		arTreeItem = [],
		RowItems = null,
		target = BX.proxy_context;

		if(!!target && target.hasAttribute('data-treevalue')) {
			strTreeValue = target.getAttribute('data-treevalue');
			arTreeItem = strTreeValue.split('_');
			if(this.SearchOfferPropIndex(arTreeItem[0], arTreeItem[1])) {
				RowItems = BX.findChildren(target.parentNode, {tagName: 'li'}, false);
				if(!!RowItems && 0 < RowItems.length) {
					for(i = 0; i < RowItems.length; i++) {
						value = RowItems[i].getAttribute('data-onevalue');
						if(value === arTreeItem[1]) {
							BX.addClass(RowItems[i], 'active');
						} else {
							BX.removeClass(RowItems[i], 'active');
						}
					}
				}
			}
		}
	};
	
	window.JCCatalogBigdataProducts.prototype.SearchOfferPropIndex = function(strPropID, strPropValue) {
		var strName = '',
		arShowValues = false,
		i, j,
		arCanBuyValues = [],
		allValues = [],
		index = -1,
		arFilter = {},
		tmpFilter = [];
		
		for(i = 0; i < this.treeProps.length; i++) {
			if(this.treeProps[i].ID === strPropID) {
				index = i;
				break;
			}
		}

		if(-1 < index) {
			for(i = 0; i < index; i++) {
				strName = 'PROP_'+this.treeProps[i].ID;
				arFilter[strName] = this.selectedValues[strName];
			}
			strName = 'PROP_'+this.treeProps[index].ID;
			arShowValues = this.GetRowValues(arFilter, strName);
			if(!arShowValues) {
				return false;
			}
			if(!BX.util.in_array(strPropValue, arShowValues)) {
				return false;
			}
			arFilter[strName] = strPropValue;
			for(i = index+1; i < this.treeProps.length; i++) {
				strName = 'PROP_'+this.treeProps[i].ID;
				arShowValues = this.GetRowValues(arFilter, strName);
				if(!arShowValues) {
					return false;
				}
				allValues = [];
				arCanBuyValues = [];
				tmpFilter = [];
				tmpFilter = BX.clone(arFilter, true);
				for(j = 0; j < arShowValues.length; j++) {
					tmpFilter[strName] = arShowValues[j];
					allValues[allValues.length] = arShowValues[j];
					if(this.GetCanBuy(tmpFilter))
						arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
				}
				if(!!this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues)) {
					arFilter[strName] = this.selectedValues[strName];
				} else {
					arFilter[strName] = (arCanBuyValues.length > 0 ? arCanBuyValues[0] : allValues[0]);
				}
				this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
			}
			this.selectedValues = arFilter;
			this.ChangeInfo();
		}
		return true;
	};
	
	window.JCCatalogBigdataProducts.prototype.UpdateRow = function(intNumber, activeID, showID, canBuyID) {
		var i = 0,
		showI = 0,
		value = '',
		obData = {},
		isCurrent = false,
		selectIndex = 0,
		RowItems = null;
		
		if(-1 < intNumber && intNumber < this.obTreeRows.length) {
			RowItems = BX.findChildren(this.obTreeRows[intNumber].LIST, {tagName: 'li'}, false);
			if(!!RowItems && 0 < RowItems.length) {
				obData = {
					props: { className: '' },
					style: {}
				};
				for(i = 0; i < RowItems.length; i++) {
					value = RowItems[i].getAttribute('data-onevalue');
					isCurrent = (value === activeID);
					if(BX.util.in_array(value, canBuyID)) {
						obData.props.className = (isCurrent ? 'active' : '');
					} else {
						obData.props.className = (isCurrent ? 'active disabled' : 'disabled');
					}
					obData.style.display = 'none';
					if(BX.util.in_array(value, showID)) {
						obData.style.display = '';
						if(isCurrent) {
							selectIndex = showI;
						}
						showI++;
					}
					BX.adjust(RowItems[i], obData);
				}

				obData = {
					style: {}
				};
				
				BX.adjust(this.obTreeRows[intNumber].LIST, obData);
			}
		}
	};

	window.JCCatalogBigdataProducts.prototype.GetRowValues = function(arFilter, index) {
		var i = 0,
		j,
		arValues = [],
		boolSearch = false,
		boolOneSearch = true;

		if(0 === arFilter.length) {
			for(i = 0; i < this.offers.length; i++) {
				if(!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
					arValues[arValues.length] = this.offers[i].TREE[index];
				}
			}
			boolSearch = true;
		} else {
			for(i = 0; i < this.offers.length; i++) {
				boolOneSearch = true;
				for(j in arFilter) {
					if(arFilter[j] !== this.offers[i].TREE[j]) {
						boolOneSearch = false;
						break;
					}
				}
				if(boolOneSearch) {
					if(!BX.util.in_array(this.offers[i].TREE[index], arValues)) {
						arValues[arValues.length] = this.offers[i].TREE[index];
					}
					boolSearch = true;
				}
			}
		}
		return (boolSearch ? arValues : false);
	};

	window.JCCatalogBigdataProducts.prototype.GetCanBuy = function(arFilter) {
		var i = 0,
			j,
			boolSearch = false,
			boolOneSearch = true;
		
		for(i = 0; i < this.offers.length; i++) {
			boolOneSearch = true;
			for(j in arFilter) {
				if(arFilter[j] !== this.offers[i].TREE[j]) {
					boolOneSearch = false;
					break;
				}
			}
			if(boolOneSearch) {
				if(this.offers[i].CAN_BUY) {
					boolSearch = true;
					break;
				}
			}
		}
		return boolSearch;
	};

	window.JCCatalogBigdataProducts.prototype.SetSelectCurrent = function() {
		var i = 0,
		SelectItems = null,
		selPropValueArr = [],		
		selPropValue = null,		
		MinselDelayOnclick = null,
		MinselDelayOnclickArr = [],
		MinselDelayOnclickNew = null,
		selDelayOnclick = null,
		selDelayOnclickArr = [],
		selDelayOnclickNew = null;		
		
		for(i = 0; i < this.obSelectRows.length; i++) {
			SelectItems = BX.findChildren(this.obSelectRows[i], {tagName: 'li'}, true);
			if(!!SelectItems && 0 < SelectItems.length) {
				BX.addClass(SelectItems[0], 'active');
				selPropValueArr[i] = SelectItems[0].getAttribute('data-select-onevalue');
			}
		}
		selPropValue = selPropValueArr.join('||');
		
		if(!!this.offers && 0 < this.offers.length) {
			for(i = 0; i < this.offers.length; i++) {
				/*CART*/
				if(!!BX('select_props_'+this.visual.ID+'_'+this.offers[i].ID))
					BX('select_props_'+this.visual.ID+'_'+this.offers[i].ID).value = selPropValue;
				/*MIN_DELAY*/
				if(!!BX('catalog-item-delay-min-'+this.visual.ID+'-'+this.offers[i].ID)) {
					MinselDelayOnclick = BX('catalog-item-delay-min-'+this.visual.ID+'-'+this.offers[i].ID).getAttribute('onclick');
					MinselDelayOnclickArr = MinselDelayOnclick.split("',");
					MinselDelayOnclickArr[3] = " '"+selPropValue;
					MinselDelayOnclickNew = MinselDelayOnclickArr.join("',");
					BX('catalog-item-delay-min-'+this.visual.ID+'-'+this.offers[i].ID).setAttribute('onclick', MinselDelayOnclickNew);					
				}
				/*DELAY*/
				if(!!BX('catalog-item-delay-'+this.visual.ID+'-'+this.offers[i].ID)) {
					selDelayOnclick = BX('catalog-item-delay-'+this.visual.ID+'-'+this.offers[i].ID).getAttribute('onclick');
					selDelayOnclickArr = selDelayOnclick.split("',");
					selDelayOnclickArr[3] = " '"+selPropValue;
					selDelayOnclickNew = selDelayOnclickArr.join("',");
					BX('catalog-item-delay-'+this.visual.ID+'-'+this.offers[i].ID).setAttribute('onclick', selDelayOnclickNew);
				}
			}
		} else {
			/*CART*/
			if(!!BX('select_props_'+this.visual.ID))
				BX('select_props_'+this.visual.ID).value = selPropValue;
			/*DELAY*/
			if(!!BX('catalog-item-delay-'+this.visual.ID)) {
				selDelayOnclick = BX('catalog-item-delay-'+this.visual.ID).getAttribute('onclick');
				selDelayOnclickArr = selDelayOnclick.split("',");
				selDelayOnclickArr[3] = " '"+selPropValue;
				selDelayOnclickNew = selDelayOnclickArr.join("',");
				BX('catalog-item-delay-'+this.visual.ID).setAttribute('onclick', selDelayOnclickNew);
			}
		}
	}

	window.JCCatalogBigdataProducts.prototype.SetCurrent = function() {
		var i = 0,
		j = 0,
		arCanBuyValues = [],
		strName = '',
		arShowValues = false,
		arFilter = {},
		tmpFilter = [],
		current = this.offers[this.offerNum].TREE;

		for(i = 0; i < this.treeProps.length; i++) {
			strName = 'PROP_'+this.treeProps[i].ID;
			arShowValues = this.GetRowValues(arFilter, strName);
			if(!arShowValues) {
				break;
			}
			if(BX.util.in_array(current[strName], arShowValues)) {
				arFilter[strName] = current[strName];
			} else {
				arFilter[strName] = arShowValues[0];
				this.offerNum = 0;
			}
			arCanBuyValues = [];
			tmpFilter = [];
			tmpFilter = BX.clone(arFilter, true);
			for(j = 0; j < arShowValues.length; j++) {
				tmpFilter[strName] = arShowValues[j];
				if(this.GetCanBuy(tmpFilter)) {
					arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
				}
			}
			this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
		}
		this.selectedValues = arFilter;
		this.ChangeInfo();
	};

	window.JCCatalogBigdataProducts.prototype.ChangeInfo = function() {
		var i = 0,
		j,
		index = -1,
		boolOneSearch = true;

		for(i = 0; i < this.offers.length; i++) {
			boolOneSearch = true;
			for(j in this.selectedValues) {
				if(this.selectedValues[j] !== this.offers[i].TREE[j]) {
					boolOneSearch = false;
					break;
				}
			}
			if(boolOneSearch) {
				index = i;
				break;
			}
		}
		if(-1 < index) {
			this.setPict(this.visual.ID, this.offers[index].ID);
			this.setPrice(this.visual.ID, this.offers[index].ID);
			this.setBuy(this.visual.ID, this.offers[index].ID);
			this.offerNum = index;
		}
	};

	window.JCCatalogBigdataProducts.prototype.setPict = function(visual_id, offer_id) {
		PictItems = BX.findChildren(this.obPict, {className: 'img'}, true);
		if(!!PictItems && 0 < PictItems.length) {
			for(i = 0; i < PictItems.length; i++) {
				BX.addClass(PictItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('img_'+visual_id+'_'+offer_id), 'hidden');
	};

	window.JCCatalogBigdataProducts.prototype.setPrice = function(visual_id, offer_id) {
		PriceItems = BX.findChildren(this.obPrice, {className: 'price'}, true);
		if(!!PriceItems && 0 < PriceItems.length) {
			for(i = 0; i < PriceItems.length; i++) {
				BX.addClass(PriceItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('price_'+visual_id+'_'+offer_id), 'hidden');
	};

	window.JCCatalogBigdataProducts.prototype.setBuy = function(visual_id, offer_id) {
		BuyItems = BX.findChildren(this.obBuy, {className: 'buy_more'}, true);
		if(!!BuyItems && 0 < BuyItems.length) {
			for(i = 0; i < BuyItems.length; i++) {
				BX.addClass(BuyItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('buy_more_'+visual_id+'_'+offer_id), 'hidden');
	};

	window.JCCatalogBigdataProducts.prototype.Add2Basket = function() {
		var target = BX.proxy_context,
			form = BX.findParent(target, {"tag" : "form"}),
			formInputs = BX.findChildren(form, {"tag" : "input"}, true);
		
		if(!!formInputs && 0 < formInputs.length) {
			for(i = 0; i < formInputs.length; i++) {
				this.basketParams[formInputs[i].getAttribute("name")] = formInputs[i].value;
			}
		}
		
		if("LIST" == this.offersView) {
			var offerItem = BX.findParent(target, {className: "catalog-item"});
			if(!!offerItem)
				this.offerNum = offerItem.getAttribute("data-offer-num");
		}

		BX.ajax.post(
			form.getAttribute("action"),			
			this.basketParams,			
			BX.delegate(function(result) {
				if(location.pathname != BX.message("BIGDATA_SITE_DIR") + "personal/cart/") {
					BX.ajax.post(
						BX.message("BIGDATA_SITE_DIR") + "ajax/basket_line.php",
						"",
						BX.delegate(function(data) {
							refreshCartLine(data);
						}, this)
					);
					BX.ajax.post(
						BX.message("BIGDATA_SITE_DIR") + "ajax/delay_line.php",
						"",
						BX.delegate(function(data) {
							var delayLine = BX.findChildren(document.body, {className: "delay_line"}, true);
							if(!!delayLine && 0 < delayLine.length) {
								for(i = 0; i < delayLine.length; i++) {
									delayLine[i].innerHTML = data;
								}
							}						
						}, this)
					);
				}
				BX.adjust(target, {
					props: {disabled: true},
					html: "<i class='fa fa-check'></i><span>" + BX.message("BIGDATA_ADDITEMINCART_ADDED") + "</span>"
				});
				if(location.pathname != BX.message("BIGDATA_SITE_DIR") + "personal/cart/") {
					this.BasketResult();
				} else {
					this.BasketRedirect();
				}
			}, this)			
		);		
	};

	window.JCCatalogBigdataProducts.prototype.BasketResult = function() {
		var close,
			strContent,
			strPictSrc,
			strPictWidth,
			strPictHeight,
			buttons = [];

		if(!!this.obPopupWin) {
			this.obPopupWin.close();
		}
		
		this.obPopupWin = BX.PopupWindowManager.create("addItemInCart", null, {
			autoHide: true,
			offsetLeft: 0,
			offsetTop: 0,
			overlay: {
				opacity: 100
			},
			draggable: false,
			closeByEsc: false,			
			closeIcon: {top: "-10px", right: "-10px"},
			titleBar: {content: BX.create("span", {html: BX.message("BIGDATA_POPUP_WINDOW_TITLE")})}			
		});
		
		BX.addClass(BX("addItemInCart"), "pop-up modal");
		close = BX.findChildren(BX("addItemInCart"), {className: "popup-window-close-icon"}, true);
		if(!!close && 0 < close.length) {
			for(i = 0; i < close.length; i++) {					
				close[i].innerHTML = "<i class='fa fa-times'></i>";
			}
		}

		switch(this.productType) {
			case 1://product
			case 2://set
				strPictSrc = this.product.pict.SRC;
				strPictWidth = this.product.pict.WIDTH;
				strPictHeight = this.product.pict.HEIGHT;
				break;
			case 3://sku
				strPictSrc = (!!this.offers[this.offerNum].PREVIEW_PICTURE ? this.offers[this.offerNum].PREVIEW_PICTURE.SRC : this.product.pict.SRC);
				strPictWidth = (!!this.offers[this.offerNum].PREVIEW_PICTURE ? this.offers[this.offerNum].PREVIEW_PICTURE.WIDTH : this.product.pict.WIDTH);
				strPictHeight = (!!this.offers[this.offerNum].PREVIEW_PICTURE ? this.offers[this.offerNum].PREVIEW_PICTURE.HEIGHT : this.product.pict.HEIGHT);
				break;
		}
		
		strContent = "<div class='cont'><div class='item_image_cont'><div class='item_image_full'><img src='" + strPictSrc + "' width='" + strPictWidth + "' height='" + strPictHeight + "' alt='"+ this.product.name +"' /></div></div><div class='item_title'>" + this.product.name + "</div></div>";

		buttons = [			
			new BasketButton({				
				text: BX.message("BIGDATA_POPUP_WINDOW_BTN_CLOSE"),
				name: "close",
				className: "btn_buy ppp close",
				events: {
					click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
				}
			}),
			new BasketButton({				
				text: BX.message("BIGDATA_POPUP_WINDOW_BTN_ORDER"),
				name: "order",
				className: "btn_buy popdef order",
				events: {
					click: BX.delegate(this.BasketRedirect, this)
				}
			})
		];
		
		this.obPopupWin.setContent(strContent);
		this.obPopupWin.setButtons(buttons);
		this.obPopupWin.show();	
	};

	window.JCCatalogBigdataProducts.prototype.BasketRedirect = function() {
		location.href = BX.message("BIGDATA_SITE_DIR") + "personal/cart/";
	};

	window.JCCatalogBigdataProducts.prototype.RememberRecommendation = function(obj, productId) {
		var rcmContainer = BX.findParent(obj, {'className':'bigdata_recommended_products_items'});
		var rcmId = BX.findChild(rcmContainer, {'attr':{'name':'bigdata_recommendation_id'}}, true).value;

		this.RememberProductRecommendation(rcmId, productId);
	};

	window.JCCatalogBigdataProducts.prototype.RememberProductRecommendation = function(recommendationId, productId) {
		//save to RCM_PRODUCT_LOG
		var plCookieName = BX.cookie_prefix+'_RCM_PRODUCT_LOG';
		var plCookie = getCookie(plCookieName);
		var itemFound = false;

		var cItems = [],
			cItem;

		if(plCookie) {
			cItems = plCookie.split('.');
		}

		var i = cItems.length;

		while(i--) {
			cItem = cItems[i].split('-');

			if(cItem[0] == productId) {
				//it's already in recommendations, update the date
				cItem = cItems[i].split('-');

				//update rcmId and date
				cItem[1] = rcmId;
				cItem[2] = BX.current_server_time;

				cItems[i] = cItem.join('-');
				itemFound = true;
			} else {
				if((BX.current_server_time - cItem[2]) > 3600*24*30) {
					cItems.splice(i, 1);
				}
			}
		}

		if(!itemFound) {
			//add recommendation
			cItems.push([productId, rcmId, BX.current_server_time].join('-'));
		}

		//serialize
		var plNewCookie = cItems.join('.');

		var cookieDate = new Date(new Date().getTime() + 1000*3600*24*365*10);
		document.cookie=plCookieName+"="+plNewCookie+"; path=/; expires="+cookieDate.toUTCString()+"; domain="+BX.cookie_domain;
	};
})(window);

function getCookie(name) {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function bx_rcm_recommendation_event_attaching(rcm_items_cont) {
	return null;
}

function bx_rcm_adaptive_recommendation_event_attaching(items, uniqId) {
	//onclick handler
	var callback = function(e) {
		var link = BX(this), j;
		for(j in items) {
			if(items[j].productUrl == link.getAttribute("href")) {
				window.JCCatalogBigdataProducts.prototype.RememberProductRecommendation(
					items[j].recommendationId, items[j].productId
				);
				break;
			}
		}
	};

	//check if a container was defined is the template
	var itemsContainer = BX(uniqId);

	if(!itemsContainer) {
		// then get all the links
		itemsContainer = document.body;
	}

	var links = BX.findChildren(itemsContainer, {tag:"a"}, true);

	//bind
	if(links) {
		var i;
		for(i in links) {
			BX.bind(links[i], "click", callback);
		}
	}
}

function bx_rcm_get_from_cloud(injectId, rcmParameters, localAjaxData) {
	var url = "https://analytics.bitrix.info/crecoms/v1_0/recoms.php";
	var data = BX.ajax.prepareData(rcmParameters);

	if(data) {
		url += (url.indexOf("?") !== -1 ? "&" : "?") + data;
	}

	var onready = function(response) {
		if(!response.items) {
			response.items = [];
		}
		BX.ajax({
			url: "/bitrix/components/bitrix/catalog.bigdata.products/ajax.php?"+BX.ajax.prepareData({"AJAX_ITEMS": response.items, "RID": response.id}),
			method: "POST",
			data: localAjaxData,
			dataType: "html",
			processData: false,
			start: true,
			onsuccess: function(html) {
				var ob = BX.processHTML(html);

				// inject
				BX(injectId).innerHTML = ob.HTML;
				BX.ajax.processScripts(ob.SCRIPT);
			}
		});
	};

	BX.ajax({
		"method": "GET",
		"dataType": "json",
		"url": url,
		"timeout": 3,
		"onsuccess": onready,
		"onfailure": onready
	});
}
/* End */
;; /* /local/templates/elektro_flat/components/bitrix/sale.basket.basket/.default/script.js?150775987014365*/
; /* /local/templates/elektro_flat/components/bitrix/catalog.bigdata.products/.default/script.js?150706317327313*/
