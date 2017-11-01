
; /* Start:"a:4:{s:4:"full";s:126:"/local/templates/elektro_flat/components/bitrix/catalog/.default/bitrix/catalog.smart.filter/elektro/script.js?150706317320451";s:6:"source";s:110:"/local/templates/elektro_flat/components/bitrix/catalog/.default/bitrix/catalog.smart.filter/elektro/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
function JCSmartFilter(ajaxURL, params) {
	this.ajaxURL = ajaxURL;
	this.form = null;
	this.timer = null;
	if(params && params.SEF_SET_FILTER_URL) {
		this.bindUrlToButton('set_filter', params.SEF_SET_FILTER_URL);
		this.sef = true;
	}
	if(params && params.SEF_DEL_FILTER_URL) {
		this.bindUrlToButton('del_filter', params.SEF_DEL_FILTER_URL);
	}	
}

JCSmartFilter.prototype.keyup = function(input) {
	if(!!this.timer) {
		clearTimeout(this.timer);
	}
	this.timer = setTimeout(BX.delegate(function(){
		this.reload(input);
	}, this), 500);
};

JCSmartFilter.prototype.click = function(checkbox) {	
	if(!!this.timer) {
		clearTimeout(this.timer);
	}
	this.timer = setTimeout(BX.delegate(function(){
		this.reload(checkbox);
	}, this), 500);	
};

JCSmartFilter.prototype.reload = function(input) {
	var values = [];

	this.position = BX.pos(input, true);
	this.form = BX.findParent(input, {'tag':'form'});
	if(this.form) {
		values[0] = {name: 'ajax', value: 'y'};
		this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': new RegExp('^(input|select)$', 'i')}, true));
		
		this.curFilterinput = input;
		BX.ajax.loadJSON(
			this.ajaxURL,
			this.values2post(values),
			BX.delegate(this.postHandler, this)
		);
	}
};

JCSmartFilter.prototype.updateItem = function (PID, arItem) {
	if(arItem.PROPERTY_TYPE === 'N' || arItem.PRICE) {
		var trackBar = window['trackBar' + PID];
		if(!trackBar && arItem.ENCODED_ID)
			trackBar = window['trackBar' + arItem.ENCODED_ID];

		if(trackBar && arItem.VALUES) {
			if(arItem.VALUES.MIN) {
				if(arItem.VALUES.MIN.FILTERED_VALUE)
					trackBar.setMinFilteredValue(arItem.VALUES.MIN.FILTERED_VALUE);
				else
					trackBar.setMinFilteredValue(arItem.VALUES.MIN.VALUE);
			}

			if(arItem.VALUES.MAX) {
				if(arItem.VALUES.MAX.FILTERED_VALUE)
					trackBar.setMaxFilteredValue(arItem.VALUES.MAX.FILTERED_VALUE);
				else
					trackBar.setMaxFilteredValue(arItem.VALUES.MAX.VALUE);
			}
		}
	} else if(arItem.VALUES) {
		for(var i in arItem.VALUES) {
			if(arItem.VALUES.hasOwnProperty(i)) {
				var value = arItem.VALUES[i];
				var control = BX(value.CONTROL_ID);

				if(!!control) {
					var label = document.querySelector('[data-role="label_'+value.CONTROL_ID+'"]');
					if(value.DISABLED) {						
						BX.adjust(control, {props: {disabled: true}});
						if(label)
							BX.addClass(label, 'disabled');
						else
							BX.addClass(control.parentNode, 'disabled');						
					} else {
						BX.adjust(control, {props: {disabled: false}});
						if(label)
							BX.removeClass(label, 'disabled');
						else
							BX.removeClass(control.parentNode, 'disabled');
					}
					if(value.hasOwnProperty('ELEMENT_COUNT')) {
						label = document.querySelector('[data-role="count_'+value.CONTROL_ID+'"]');
						if(label)
							label.innerHTML = value.ELEMENT_COUNT;
					}
				}
			}
		}
	}
};

JCSmartFilter.prototype.postHandler = function (result) {
	var hrefFILTER, url, curProp;
	var modef = BX('modef');
	var modef_popup = BX('modef_popup');
	var modef_num = BX('modef_num');
	var modef_popup_num = BX('modef_popup_num');

	if(!!result && !!result.ITEMS) {
		for(var PID in result.ITEMS) {
			if(result.ITEMS.hasOwnProperty(PID)) {
				this.updateItem(PID, result.ITEMS[PID]);
			}
		}				

		if(!!modef && !!modef_num) {
			modef_num.innerHTML = result.ELEMENT_COUNT;
			if(modef.style.display === 'none') {
				modef.style.display = 'inline-block';
			}
		}
		
		if(!!modef_popup && !!modef_popup_num) {
			modef_popup_num.innerHTML = result.ELEMENT_COUNT;
			hrefFILTER = BX.findChildren(modef_popup, {tag: 'A'}, true);

			if(result.FILTER_URL && hrefFILTER) {
				hrefFILTER[0].href = BX.util.htmlspecialcharsback(result.FILTER_URL);
			}

			if(result.FILTER_AJAX_URL && result.COMPONENT_CONTAINER_ID) {
				BX.bind(hrefFILTER[0], 'click', function(e) {
					var url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
					BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
					return BX.PreventDefault(e);
				});
			}

			if(result.INSTANT_RELOAD && result.COMPONENT_CONTAINER_ID) {
				url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
				BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
			} else {
				if(modef_popup.style.display === 'none') {
					modef_popup.style.display = 'inline-block';
				}
				curProp = BX.findChild(BX.findParent(this.curFilterinput, {'class':'bx_filter_box'}), {'class':'bx_filter_container_modef_popup'}, true, false);
				curProp.appendChild(modef_popup);				
				if(result.SEF_SET_FILTER_URL) {
					this.bindUrlToButton('set_filter', result.SEF_SET_FILTER_URL);
				}
			}
		}
	}
};

JCSmartFilter.prototype.bindUrlToButton = function (buttonId, url) {
	var button = BX(buttonId);
	if(button) {
		var proxy = function(j, func) {
			return function() {
				return func(j);
			}
		};

		if(button.type == 'submit')
			button.type = 'button';

		BX.bind(button, 'click', proxy(url, function(url) {
			window.location.href = url;
			return false;
		}));
	}
};

JCSmartFilter.prototype.gatherInputsValues = function (values, elements) {
	if(elements) {
		for(var i = 0; i < elements.length; i++) {
			var el = elements[i];
			if(el.disabled || !el.type)
				continue;

			switch(el.type.toLowerCase()) {
				case 'text':
				case 'textarea':
				case 'password':
				case 'hidden':
				case 'select-one':
					if(el.value.length)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'radio':
				case 'checkbox':
					if(el.checked)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'select-multiple':
					for(var j = 0; j < el.options.length; j++) {
						if (el.options[j].selected)
							values[values.length] = {name : el.name, value : el.options[j].value};
					}
					break;
				default:
					break;
			}
		}
	}
};

JCSmartFilter.prototype.values2post = function (values) {
	var post = new Array;
	var current = post;
	var i = 0;
	while(i < values.length) {
		var p = values[i].name.indexOf('[');
		if(p == -1) {
			current[values[i].name] = values[i].value;
			current = post;
			i++;
		} else {
			var name = values[i].name.substring(0, p);
			var rest = values[i].name.substring(p+1);
			if(!current[name])
				current[name] = new Array;

			var pp = rest.indexOf(']');
			if(pp == -1) {
				//Error - not balanced brackets
				current = post;
				i++;
			} else if(pp == 0) {
				//No index specified - so take the next integer
				current = current[name];
				values[i].name = '' + current.length;
			} else {
				//Now index name becomes and name and we go deeper into the array
				current = current[name];
				values[i].name = rest.substring(0, pp) + rest.substring(pp+1);
			}
		}
	}
	return post;
};

JCSmartFilter.prototype.hideFilterProps = function(element) {
	var easing,
		obj = element.parentNode.parentNode.parentNode,
		filterBlock = BX.findChild(obj, {className:"bx_filter_block"}, true, false),
		faWrap = BX.findChild(obj, {className:"sect__arrow"}, true, false),
		fa = BX.findChildren(faWrap, {className: "fa"}, true);
	
	if(BX.hasClass(obj, "active")) {
		easing = new BX.easing({
			duration : 300,
			start : { opacity: 1 },
			finish : { opacity: 0 },
			transition : BX.easing.transitions.quart,
			step : function(state){
				filterBlock.style.opacity = state.opacity;
			},
			complete : function() {
				filterBlock.setAttribute("style", "");
				BX.removeClass(obj, "active");
				if(!!fa && 0 < fa.length) {
					for(i = 0; i < fa.length; i++) {
						if(BX.hasClass(fa[i], "fa-angle-left"))
							BX.toggleClass(fa[i], ["fa-angle-left", "fa-angle-right"]);
						else if(BX.hasClass(fa[i], "fa-angle-up"))
							BX.toggleClass(fa[i], ["fa-angle-up", "fa-angle-down"]);
					}
				}
			}
		});
		easing.animate();
	} else {
		filterBlock.style.opacity = 0;
		
		easing = new BX.easing({
			duration : 300,
			start : { opacity: 0 },
			finish : { opacity: 1 },
			transition : BX.easing.transitions.quart,
			step : function(state){
				filterBlock.style.opacity = state.opacity;
			},
			complete : function() {}
		});
		easing.animate();
		BX.addClass(obj, "active");
		if(!!fa && 0 < fa.length) {
			for(i = 0; i < fa.length; i++) {
				if(BX.hasClass(fa[i], "fa-angle-right"))
					BX.toggleClass(fa[i], ["fa-angle-left", "fa-angle-right"]);
				else if(BX.hasClass(fa[i], "fa-angle-down"))
					BX.toggleClass(fa[i], ["fa-angle-up", "fa-angle-down"]);
			}
		}
	}
};

JCSmartFilter.prototype.showDropDownPopup = function(element, popupId) {
	var contentNode = element.querySelector('[data-role="dropdownContent"]');
	BX.PopupWindowManager.create("smartFilterDropDown"+popupId, element, {
		autoHide: true,
		offsetLeft: 0,
		offsetTop: 3,
		overlay : false,
		draggable: {restrict:true},
		closeByEsc: true,
		content: contentNode
	}).show();
};

JCSmartFilter.prototype.selectDropDownItem = function(element, controlId) {
	this.keyup(BX(controlId));

	var wrapContainer = BX.findParent(BX(controlId), {className:"bx_filter_select_container"}, false);

	var currentOption = wrapContainer.querySelector('[data-role="currentOption"]');
	currentOption.innerHTML = element.innerHTML;
	BX.PopupWindowManager.getCurrentPopup().close();
};

BX.namespace("BX.Iblock.SmartFilter");
BX.Iblock.SmartFilter = (function() {
	var SmartFilter = function(arParams) {
		if(typeof arParams === 'object') {
			this.leftSlider = BX(arParams.leftSlider);
			this.rightSlider = BX(arParams.rightSlider);
			this.tracker = BX(arParams.tracker);
			this.trackerWrap = BX(arParams.trackerWrap);

			this.minInput = BX(arParams.minInputId);
			this.maxInput = BX(arParams.maxInputId);

			this.minPrice = parseFloat(arParams.minPrice);
			this.maxPrice = parseFloat(arParams.maxPrice);

			this.curMinPrice = parseFloat(arParams.curMinPrice);
			this.curMaxPrice = parseFloat(arParams.curMaxPrice);

			this.fltMinPrice = arParams.fltMinPrice ? parseFloat(arParams.fltMinPrice) : parseFloat(arParams.curMinPrice);
			this.fltMaxPrice = arParams.fltMaxPrice ? parseFloat(arParams.fltMaxPrice) : parseFloat(arParams.curMaxPrice);

			this.precision = arParams.precision || 0;

			this.priceDiff = this.maxPrice - this.minPrice;

			this.leftPercent = 0;
			this.rightPercent = 0;

			this.fltMinPercent = 0;
			this.fltMaxPercent = 0;

			this.colorUnavailableActive = BX(arParams.colorUnavailableActive);//gray
			this.colorAvailableActive = BX(arParams.colorAvailableActive);//blue
			this.colorAvailableInactive = BX(arParams.colorAvailableInactive);//light blue

			this.isTouch = false;

			this.init();

			if('ontouchstart' in document.documentElement) {
				this.isTouch = true;

				BX.bind(this.leftSlider, "touchstart", BX.proxy(function(event){
					this.onMoveLeftSlider(event)
				}, this));

				BX.bind(this.rightSlider, "touchstart", BX.proxy(function(event){
					this.onMoveRightSlider(event)
				}, this));
			} else {
				BX.bind(this.leftSlider, "mousedown", BX.proxy(function(event){
					this.onMoveLeftSlider(event)
				}, this));

				BX.bind(this.rightSlider, "mousedown", BX.proxy(function(event){
					this.onMoveRightSlider(event)
				}, this));
			}

			BX.bind(this.minInput, "keyup", BX.proxy(function(event){
				this.onInputChange();
			}, this));

			BX.bind(this.maxInput, "keyup", BX.proxy(function(event){
				this.onInputChange();
			}, this));
		}
	};

	SmartFilter.prototype.init = function() {
		var priceDiff;

		if(this.curMinPrice > this.minPrice) {
			priceDiff = this.curMinPrice - this.minPrice;
			this.leftPercent = (priceDiff*100)/this.priceDiff;

			this.leftSlider.style.left = this.leftPercent + "%";
			this.colorUnavailableActive.style.left = this.leftPercent + "%";
		}

		this.setMinFilteredValue(this.fltMinPrice);

		if(this.curMaxPrice < this.maxPrice) {
			priceDiff = this.maxPrice - this.curMaxPrice;
			this.rightPercent = (priceDiff*100)/this.priceDiff;

			this.rightSlider.style.right = this.rightPercent + "%";
			this.colorUnavailableActive.style.right = this.rightPercent + "%";
		}

		this.setMaxFilteredValue(this.fltMaxPrice);
	};

	SmartFilter.prototype.setMinFilteredValue = function (fltMinPrice) {
		this.fltMinPrice = parseFloat(fltMinPrice);
		if(this.fltMinPrice >= this.minPrice) {
			var priceDiff = this.fltMinPrice - this.minPrice;
			this.fltMinPercent = (priceDiff*100)/this.priceDiff;

			if(this.leftPercent > this.fltMinPercent)
				this.colorAvailableActive.style.left = this.leftPercent + "%";
			else
				this.colorAvailableActive.style.left = this.fltMinPercent + "%";

			this.colorAvailableInactive.style.left = this.fltMinPercent + "%";
		} else {
			this.colorAvailableActive.style.left = "0%";
			this.colorAvailableInactive.style.left = "0%";
		}
	};

	SmartFilter.prototype.setMaxFilteredValue = function (fltMaxPrice) {
		this.fltMaxPrice = parseFloat(fltMaxPrice);
		if(this.fltMaxPrice <= this.maxPrice) {
			var priceDiff = this.maxPrice - this.fltMaxPrice;
			this.fltMaxPercent = (priceDiff*100)/this.priceDiff;

			if(this.rightPercent > this.fltMaxPercent)
				this.colorAvailableActive.style.right = this.rightPercent + "%";
			else
				this.colorAvailableActive.style.right = this.fltMaxPercent + "%";

			this.colorAvailableInactive.style.right = this.fltMaxPercent + "%";
		} else {
			this.colorAvailableActive.style.right = "0%";
			this.colorAvailableInactive.style.right = "0%";
		}
	};

	SmartFilter.prototype.getXCoord = function(elem) {
		var box = elem.getBoundingClientRect();
		var body = document.body;
		var docElem = document.documentElement;

		var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft;
		var clientLeft = docElem.clientLeft || body.clientLeft || 0;
		var left = box.left + scrollLeft - clientLeft;

		return Math.round(left);
	};

	SmartFilter.prototype.getPageX = function(e) {
		e = e || window.event;
		var pageX = null;

		if(this.isTouch && event.targetTouches[0] != null) {
			pageX = e.targetTouches[0].pageX;
		} else if (e.pageX != null) {
			pageX = e.pageX;
		} else if (e.clientX != null) {
			var html = document.documentElement;
			var body = document.body;

			pageX = e.clientX + (html.scrollLeft || body && body.scrollLeft || 0);
			pageX -= html.clientLeft || 0;
		}

		return pageX;
	};

	SmartFilter.prototype.recountMinPrice = function() {
		var newMinPrice = (this.priceDiff*this.leftPercent)/100;
		newMinPrice = (this.minPrice + newMinPrice).toFixed(this.precision);

		if(newMinPrice != this.minPrice)
			this.minInput.value = newMinPrice;
		else
			this.minInput.value = "";
		smartFilter.keyup(this.minInput);
	};

	SmartFilter.prototype.recountMaxPrice = function() {
		var newMaxPrice = (this.priceDiff*this.rightPercent)/100;
		newMaxPrice = (this.maxPrice - newMaxPrice).toFixed(this.precision);

		if(newMaxPrice != this.maxPrice)
			this.maxInput.value = newMaxPrice;
		else
			this.maxInput.value = "";
		smartFilter.keyup(this.maxInput);
	};

	SmartFilter.prototype.onInputChange = function () {
		var priceDiff;
		if(this.minInput.value) {
			var leftInputValue = this.minInput.value;
			if (leftInputValue < this.minPrice)
				leftInputValue = this.minPrice;

			if (leftInputValue > this.maxPrice)
				leftInputValue = this.maxPrice;

			priceDiff = leftInputValue - this.minPrice;
			this.leftPercent = (priceDiff*100)/this.priceDiff;

			this.makeLeftSliderMove(false);
		}

		if(this.maxInput.value) {
			var rightInputValue = this.maxInput.value;
			if (rightInputValue < this.minPrice)
				rightInputValue = this.minPrice;

			if (rightInputValue > this.maxPrice)
				rightInputValue = this.maxPrice;

			priceDiff = this.maxPrice - rightInputValue;
			this.rightPercent = (priceDiff*100)/this.priceDiff;

			this.makeRightSliderMove(false);
		}
	};

	SmartFilter.prototype.makeLeftSliderMove = function(recountPrice) {
		recountPrice = (recountPrice === false) ? false : true;

		this.leftSlider.style.left = this.leftPercent + "%";
		this.colorUnavailableActive.style.left = this.leftPercent + "%";

		var areBothSlidersMoving = false;
		if(this.leftPercent + this.rightPercent >= 100) {
			areBothSlidersMoving = true;
			this.rightPercent = 100 - this.leftPercent;
			this.rightSlider.style.right = this.rightPercent + "%";
			this.colorUnavailableActive.style.right = this.rightPercent + "%";
		}

		if(this.leftPercent >= this.fltMinPercent && this.leftPercent <= (100-this.fltMaxPercent)) {
			this.colorAvailableActive.style.left = this.leftPercent + "%";
			if(areBothSlidersMoving) {
				this.colorAvailableActive.style.right = 100 - this.leftPercent + "%";
			}
		} else if(this.leftPercent <= this.fltMinPercent) {
			this.colorAvailableActive.style.left = this.fltMinPercent + "%";
			if(areBothSlidersMoving) {
				this.colorAvailableActive.style.right = 100 - this.fltMinPercent + "%";
			}
		} else if(this.leftPercent >= this.fltMaxPercent) {
			this.colorAvailableActive.style.left = 100-this.fltMaxPercent + "%";
			if(areBothSlidersMoving) {
				this.colorAvailableActive.style.right = this.fltMaxPercent + "%";
			}
		}

		if(recountPrice) {
			this.recountMinPrice();
			if(areBothSlidersMoving)
				this.recountMaxPrice();
		}
	};

	SmartFilter.prototype.countNewLeft = function(event) {
		pageX = this.getPageX(event);

		var trackerXCoord = this.getXCoord(this.trackerWrap);
		var rightEdge = this.trackerWrap.offsetWidth;

		var newLeft = pageX - trackerXCoord;

		if (newLeft < 0)
			newLeft = 0;
		else if (newLeft > rightEdge)
			newLeft = rightEdge;

		return newLeft;
	};

	SmartFilter.prototype.onMoveLeftSlider = function(e) {
		if(!this.isTouch) {
			this.leftSlider.ondragstart = function() {
				return false;
			};
		}

		if(!this.isTouch) {
			document.onmousemove = BX.proxy(function(event) {
				this.leftPercent = ((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
				this.makeLeftSliderMove();
			}, this);

			document.onmouseup = function() {
				document.onmousemove = document.onmouseup = null;
			};
		} else {
			document.ontouchmove = BX.proxy(function(event) {
				this.leftPercent = ((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
				this.makeLeftSliderMove();
			}, this);

			document.ontouchend = function() {
				document.ontouchmove = document.touchend = null;
			};
		}

		return false;
	};

	SmartFilter.prototype.makeRightSliderMove = function(recountPrice) {
		recountPrice = (recountPrice === false) ? false : true;

		this.rightSlider.style.right = this.rightPercent + "%";
		this.colorUnavailableActive.style.right = this.rightPercent + "%";

		var areBothSlidersMoving = false;
		if(this.leftPercent + this.rightPercent >= 100) {
			areBothSlidersMoving = true;
			this.leftPercent = 100 - this.rightPercent;
			this.leftSlider.style.left = this.leftPercent + "%";
			this.colorUnavailableActive.style.left = this.leftPercent + "%";
		}

		if((100-this.rightPercent) >= this.fltMinPercent && this.rightPercent >= this.fltMaxPercent) {
			this.colorAvailableActive.style.right = this.rightPercent + "%";
			if(areBothSlidersMoving) {
				this.colorAvailableActive.style.left = 100 - this.rightPercent + "%";
			}
		} else if(this.rightPercent <= this.fltMaxPercent) {
			this.colorAvailableActive.style.right = this.fltMaxPercent + "%";
			if(areBothSlidersMoving) {
				this.colorAvailableActive.style.left = 100 - this.fltMaxPercent + "%";
			}
		} else if((100-this.rightPercent) <= this.fltMinPercent) {
			this.colorAvailableActive.style.right = 100-this.fltMinPercent + "%";
			if(areBothSlidersMoving) {
				this.colorAvailableActive.style.left = this.fltMinPercent + "%";
			}
		}

		if(recountPrice) {
			this.recountMaxPrice();
			if(areBothSlidersMoving)
				this.recountMinPrice();
		}
	};

	SmartFilter.prototype.onMoveRightSlider = function(e) {
		if(!this.isTouch) {
			this.rightSlider.ondragstart = function() {
				return false;
			};
		}

		if(!this.isTouch) {
			document.onmousemove = BX.proxy(function(event) {
				this.rightPercent = 100-(((this.countNewLeft(event))*100)/(this.trackerWrap.offsetWidth));
				this.makeRightSliderMove();
			}, this);

			document.onmouseup = function() {
				document.onmousemove = document.onmouseup = null;
			};
		} else {
			document.ontouchmove = BX.proxy(function(event) {
				this.rightPercent = 100-(((this.countNewLeft(event))*100)/(this.trackerWrap.offsetWidth));
				this.makeRightSliderMove();
			}, this);

			document.ontouchend = function() {
				document.ontouchmove = document.ontouchend = null;
			};
		}

		return false;
	};

	return SmartFilter;
})();
/* End */
;
; /* Start:"a:4:{s:4:"full";s:95:"/local/templates/elektro_flat/components/bitrix/catalog.section/table/script.js?150706317323438";s:6:"source";s:79:"/local/templates/elektro_flat/components/bitrix/catalog.section/table/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
(function (window) {

	if(!!window.JCCatalogSection) {
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

	window.JCCatalogSection = function (arParams) {
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

	window.JCCatalogSection.prototype.Init = function() {
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

	window.JCCatalogSection.prototype.SelectProp = function() {
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

	window.JCCatalogSection.prototype.SelectOfferProp = function() {
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
	
	window.JCCatalogSection.prototype.SearchOfferPropIndex = function(strPropID, strPropValue) {
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
	
	window.JCCatalogSection.prototype.UpdateRow = function(intNumber, activeID, showID, canBuyID) {
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

	window.JCCatalogSection.prototype.GetRowValues = function(arFilter, index) {
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

	window.JCCatalogSection.prototype.GetCanBuy = function(arFilter) {
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

	window.JCCatalogSection.prototype.SetSelectCurrent = function() {
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

	window.JCCatalogSection.prototype.SetCurrent = function() {
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

	window.JCCatalogSection.prototype.ChangeInfo = function() {
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

	window.JCCatalogSection.prototype.setPict = function(visual_id, offer_id) {
		PictItems = BX.findChildren(this.obPict, {className: 'img'}, true);
		if(!!PictItems && 0 < PictItems.length) {
			for(i = 0; i < PictItems.length; i++) {
				BX.addClass(PictItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('img_'+visual_id+'_'+offer_id), 'hidden');
	};

	window.JCCatalogSection.prototype.setPrice = function(visual_id, offer_id) {
		PriceItems = BX.findChildren(this.obPrice, {className: 'price'}, true);
		if(!!PriceItems && 0 < PriceItems.length) {
			for(i = 0; i < PriceItems.length; i++) {
				BX.addClass(PriceItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('price_'+visual_id+'_'+offer_id), 'hidden');
	};

	window.JCCatalogSection.prototype.setBuy = function(visual_id, offer_id) {
		BuyItems = BX.findChildren(this.obBuy, {className: 'buy_more'}, true);
		if(!!BuyItems && 0 < BuyItems.length) {
			for(i = 0; i < BuyItems.length; i++) {
				BX.addClass(BuyItems[i], 'hidden');
			}
		}
		BX.removeClass(BX('buy_more_'+visual_id+'_'+offer_id), 'hidden');
	};

	window.JCCatalogSection.prototype.Add2Basket = function() {
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
				BX.ajax.post(
					BX.message("TABLE_SITE_DIR") + "ajax/basket_line.php",
					"",
					BX.delegate(function(data) {
						refreshCartLine(data);
					}, this)
				);
				BX.ajax.post(
					BX.message("TABLE_SITE_DIR") + "ajax/delay_line.php",
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
				BX.adjust(target, {
					props: {disabled: true},
					html: "<i class='fa fa-check'></i><span>" + BX.message("TABLE_ADDITEMINCART_ADDED") + "</span>"
				});
				this.BasketResult();
			}, this)			
		);		
	};

	window.JCCatalogSection.prototype.BasketResult = function() {
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
			titleBar: {content: BX.create("span", {html: BX.message("TABLE_POPUP_WINDOW_TITLE")})}			
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
				text: BX.message("TABLE_POPUP_WINDOW_BTN_CLOSE"),
				name: "close",
				className: "btn_buy ppp close",
				events: {
					click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
				}
			}),
			new BasketButton({				
				text: BX.message("TABLE_POPUP_WINDOW_BTN_ORDER"),
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

	window.JCCatalogSection.prototype.BasketRedirect = function() {
		location.href = BX.message("TABLE_SITE_DIR") + "personal/cart/";
	};
})(window);
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
;; /* /local/templates/elektro_flat/components/bitrix/catalog/.default/bitrix/catalog.smart.filter/elektro/script.js?150706317320451*/
; /* /local/templates/elektro_flat/components/bitrix/catalog.section/table/script.js?150706317323438*/
; /* /local/templates/elektro_flat/components/bitrix/catalog.bigdata.products/.default/script.js?150706317327313*/
