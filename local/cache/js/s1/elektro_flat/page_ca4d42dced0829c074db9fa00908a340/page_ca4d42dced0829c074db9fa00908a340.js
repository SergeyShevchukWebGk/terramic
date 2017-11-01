
; /* Start:"a:4:{s:4:"full";s:103:"/bitrix/components/bitrix/sale.location.selector.search/templates/.default/script.min.js?14932932247747";s:6:"source";s:84:"/bitrix/components/bitrix/sale.location.selector.search/templates/.default/script.js";s:3:"min";s:88:"/bitrix/components/bitrix/sale.location.selector.search/templates/.default/script.min.js";s:3:"map";s:88:"/bitrix/components/bitrix/sale.location.selector.search/templates/.default/script.map.js";}"*/
BX.namespace("BX.Sale.component.location.selector");if(typeof BX.Sale.component.location.selector.search=="undefined"&&typeof BX.ui!="undefined"&&typeof BX.ui.widget!="undefined"){BX.Sale.component.location.selector.search=function(e,t){this.parentConstruct(BX.Sale.component.location.selector.search,e);BX.merge(this,{opts:{usePagingOnScroll:true,pageSize:10,arrowScrollAdditional:2,pageUpWardOffset:3,provideLinkBy:"id",bindEvents:{"after-input-value-modify":function(){this.ctrls.fullRoute.value=""},"after-select-item":function(e){var t=this.opts;var i=this.vars.cache.nodes[e];var s=i.DISPLAY;if(typeof i.PATH=="object"){for(var o=0;o<i.PATH.length;o++){s+=", "+this.vars.cache.path[i.PATH[o]]}}this.ctrls.inputs.fake.setAttribute("title",s);this.ctrls.fullRoute.value=s;if(typeof this.opts.callback=="string"&&this.opts.callback.length>0&&this.opts.callback in window)window[this.opts.callback].apply(this,[e,this])},"after-deselect-item":function(){this.ctrls.fullRoute.value="";this.ctrls.inputs.fake.setAttribute("title","")},"before-render-variant":function(e){if(e.PATH.length>0){var t="";for(var i=0;i<e.PATH.length;i++)t+=", "+this.vars.cache.path[e.PATH[i]];e.PATH=t}else e.PATH="";var s="";if(this.vars&&this.vars.lastQuery&&this.vars.lastQuery.QUERY)s=this.vars.lastQuery.QUERY;if(BX.type.isNotEmptyString(s)){var o=[];if(this.opts.wrapSeparate)o=s.split(/\s+/);else o=[s];e["=display_wrapped"]=BX.util.wrapSubstring(e.DISPLAY+e.PATH,o,this.opts.wrapTagName,true)}else e["=display_wrapped"]=BX.util.htmlspecialchars(e.DISPLAY)}}},vars:{cache:{path:{},nodesByCode:{}}},sys:{code:"sls"}});this.handleInitStack(t,BX.Sale.component.location.selector.search,e)};BX.extend(BX.Sale.component.location.selector.search,BX.ui.autoComplete);BX.merge(BX.Sale.component.location.selector.search.prototype,{init:function(){if(typeof this.opts.pathNames=="object")BX.merge(this.vars.cache.path,this.opts.pathNames);this.pushFuncStack("buildUpDOM",BX.Sale.component.location.selector.search);this.pushFuncStack("bindEvents",BX.Sale.component.location.selector.search)},buildUpDOM:function(){var e=this.ctrls,t=this.opts,i=this.vars,s=this,o=this.sys.code;e.fullRoute=BX.create("input",{props:{className:"bx-ui-"+o+"-route"},attrs:{type:"text",disabled:"disabled",autocomplete:"off"}});BX.style(e.fullRoute,"paddingTop",BX.style(e.inputs.fake,"paddingTop"));BX.style(e.fullRoute,"paddingLeft",BX.style(e.inputs.fake,"paddingLeft"));BX.style(e.fullRoute,"paddingRight","0px");BX.style(e.fullRoute,"paddingBottom","0px");BX.style(e.fullRoute,"marginTop",BX.style(e.inputs.fake,"marginTop"));BX.style(e.fullRoute,"marginLeft",BX.style(e.inputs.fake,"marginLeft"));BX.style(e.fullRoute,"marginRight","0px");BX.style(e.fullRoute,"marginBottom","0px");if(BX.style(e.inputs.fake,"borderTopStyle")!="none"){BX.style(e.fullRoute,"borderTopStyle","solid");BX.style(e.fullRoute,"borderTopColor","transparent");BX.style(e.fullRoute,"borderTopWidth",BX.style(e.inputs.fake,"borderTopWidth"))}if(BX.style(e.inputs.fake,"borderLeftStyle")!="none"){BX.style(e.fullRoute,"borderLeftStyle","solid");BX.style(e.fullRoute,"borderLeftColor","transparent");BX.style(e.fullRoute,"borderLeftWidth",BX.style(e.inputs.fake,"borderLeftWidth"))}BX.prepend(e.fullRoute,e.container);e.inputBlock=this.getControl("input-block");e.loader=this.getControl("loader")},bindEvents:function(){var e=this;BX.bindDelegate(this.getControl("quick-locations",true),"click",{tag:"a"},function(){e.setValueByLocationId(BX.data(this,"id"))});this.vars.outSideClickScope=this.ctrls.inputBlock},setValueByLocationId:function(e,t){BX.Sale.component.location.selector.search.superclass.setValue.apply(this,[e,t])},setValueByLocationIds:function(e){if(e.IDS){this.displayPage({VALUE:e.IDS,order:{TYPE_ID:"ASC","NAME.NAME":"ASC"}})}},setValueByLocationCode:function(e,t){var i=this.vars,s=this.opts,o=this.ctrls,n=this;this.hideError();if(e==null||e==false||typeof e=="undefined"||e.toString().length==0){this.resetVariables();BX.cleanNode(o.vars);if(BX.type.isElementNode(o.nothingFound))BX.hide(o.nothingFound);this.fireEvent("after-deselect-item");this.fireEvent("after-clear-selection");return}if(t!==false)i.forceSelectSingeOnce=true;if(typeof i.cache.nodesByCode[e]=="undefined"){this.resetNavVariables();n.downloadBundle({CODE:e},function(t){n.fillCache(t,false);if(typeof i.cache.nodesByCode[e]=="undefined"){n.showNothingFound()}else{var o=i.cache.nodesByCode[e].VALUE;if(s.autoSelectIfOneVariant||i.forceSelectSingeOnce)n.selectItem(o);else n.displayVariants([o])}},function(){i.forceSelectSingeOnce=false})}else{var a=i.cache.nodesByCode[e].VALUE;if(i.forceSelectSingeOnce)this.selectItem(a);else this.displayVariants([a]);i.forceSelectSingeOnce=false}},getNodeByValue:function(e){if(this.opts.provideLinkBy=="id")return this.vars.cache.nodes[e];else return this.vars.cache.nodesByCode[e]},getNodeByLocationId:function(e){return this.vars.cache.nodes[e]},setValue:function(e){if(this.opts.provideLinkBy=="id")BX.Sale.component.location.selector.search.superclass.setValue.apply(this,[e]);else this.setValueByLocationCode(e)},getValue:function(){if(this.opts.provideLinkBy=="id")return this.vars.value===false?"":this.vars.value;else{return this.vars.value?this.vars.cache.nodes[this.vars.value].CODE:""}},getSelectedPath:function(){var e=this.vars,t=[];if(typeof e.value=="undefined"||e.value==false||e.value=="")return t;if(typeof e.cache.nodes[e.value]!="undefined"){var i=BX.clone(e.cache.nodes[e.value]);if(typeof i.TYPE_ID!="undefined"&&typeof this.opts.types!="undefined")i.TYPE=this.opts.types[i.TYPE_ID].CODE;var s=i.PATH;delete i.PATH;t.push(i);if(typeof s!="undefined"){for(var o in s){var i=BX.clone(e.cache.nodes[s[o]]);if(typeof i.TYPE_ID!="undefined"&&typeof this.opts.types!="undefined")i.TYPE=this.opts.types[i.TYPE_ID].CODE;delete i.PATH;t.push(i)}}}return t},setInitialValue:function(){if(this.opts.selectedItem!==false)this.setValueByLocationId(this.opts.selectedItem);else if(this.ctrls.inputs.origin.value.length>0){if(this.opts.provideLinkBy=="id")this.setValueByLocationId(this.ctrls.inputs.origin.value);else this.setValueByLocationCode(this.ctrls.inputs.origin.value)}},addItem2Cache:function(e){this.vars.cache.nodes[e.VALUE]=e;this.vars.cache.nodesByCode[e.CODE]=e},refineRequest:function(e){var t={};if(typeof e["QUERY"]!="undefined")t["=PHRASE"]=e.QUERY;if(typeof e["VALUE"]!="undefined")t["=ID"]=e.VALUE;if(typeof e["CODE"]!="undefined")t["=CODE"]=e.CODE;if(typeof this.opts.query.BEHAVIOUR.LANGUAGE_ID!="undefined")t["=NAME.LANGUAGE_ID"]=this.opts.query.BEHAVIOUR.LANGUAGE_ID;if(BX.type.isNotEmptyString(this.opts.query.FILTER.SITE_ID))t["=SITE_ID"]=this.opts.query.FILTER.SITE_ID;var i={select:{VALUE:"ID",DISPLAY:"NAME.NAME",1:"CODE",2:"TYPE_ID"},additionals:{1:"PATH"},filter:t,version:"2"};if(typeof e["order"]!="undefined")i["order"]=e.order;return i},refineResponce:function(e,t){if(typeof e.ETC.PATH_ITEMS!="undefined"){for(var i in e.ETC.PATH_ITEMS){if(BX.type.isNotEmptyString(e.ETC.PATH_ITEMS[i].DISPLAY))this.vars.cache.path[i]=e.ETC.PATH_ITEMS[i].DISPLAY}for(var i in e.ITEMS){var s=e.ITEMS[i];if(typeof s.PATH!="undefined"){var o=BX.clone(s.PATH);for(var n in s.PATH){var a=s.PATH[n];o.shift();if(typeof this.vars.cache.nodes[a]=="undefined"&&typeof e.ETC.PATH_ITEMS[a]!="undefined"){var l=BX.clone(e.ETC.PATH_ITEMS[a]);l.PATH=BX.clone(o);this.vars.cache.nodes[a]=l}}}}}return e.ITEMS},refineItems:function(e){return e},refineItemDataForTemplate:function(e){return e},getSelectorValue:function(e){if(this.opts.provideLinkBy=="id")return e;if(typeof this.vars.cache.nodes[e]!="undefined")return this.vars.cache.nodes[e].CODE;else return""},whenLoaderToggle:function(e){BX[e?"show":"hide"](this.ctrls.loader)}})}
/* End */
;
; /* Start:"a:4:{s:4:"full";s:98:"/local/templates/elektro_flat/components/bitrix/sale.order.ajax/.default/script.js?150706317311163";s:6:"source";s:82:"/local/templates/elektro_flat/components/bitrix/sale.order.ajax/.default/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
BX.saleOrderAjax = { // bad solution, actually, a singleton at the page

	BXCallAllowed: false,

	options: {},
	indexCache: {},
	controls: {},

	modes: {},
	properties: {},

	// called once, on component load
	init: function(options)
	{
		var ctx = this;
		this.options = options;

		window.submitFormProxy = BX.proxy(function(){
			ctx.submitFormProxy.apply(ctx, arguments);
		}, this);

		BX(function(){
			ctx.initDeferredControl();
		});
		BX(function(){
			ctx.BXCallAllowed = true; // unlock form refresher
		});

		this.controls.scope = BX('order_form_div');

		// user presses "add location" when he cannot find location in popup mode
		BX.bindDelegate(this.controls.scope, 'click', {className: '-bx-popup-set-mode-add-loc'}, function(){

			var input = BX.create('input', {
				attrs: {
					type: 'hidden',
					name: 'PERMANENT_MODE_STEPS',
					value: '1'
				}
			});

			BX.prepend(input, BX('ORDER_FORM'));

			ctx.BXCallAllowed = false;
			submitForm();
		});
	},

	cleanUp: function(){

		for(var k in this.properties)
		{
			if (this.properties.hasOwnProperty(k))
			{
				if(typeof this.properties[k].input != 'undefined')
				{
					BX.unbindAll(this.properties[k].input);
					this.properties[k].input = null;
				}

				if(typeof this.properties[k].control != 'undefined')
					BX.unbindAll(this.properties[k].control);
			}
		}

		this.properties = {};
	},

	addPropertyDesc: function(desc){
		this.properties[desc.id] = desc.attributes;
		this.properties[desc.id].id = desc.id;
	},

	// called each time form refreshes
	initDeferredControl: function()
	{
		var ctx = this,
			k,
			row,
			input,
			locPropId,
			m,
			control,
			code,
			townInputFlag,
			adapter;

		// first, init all controls
		if(typeof window.BX.locationsDeferred != 'undefined'){

			this.BXCallAllowed = false;

			for(k in window.BX.locationsDeferred){

				window.BX.locationsDeferred[k].call(this);
				window.BX.locationsDeferred[k] = null;
				delete(window.BX.locationsDeferred[k]);

				this.properties[k].control = window.BX.locationSelectors[k];
				delete(window.BX.locationSelectors[k]);
			}
		}

		for(k in this.properties){

			// zip input handling
			if(this.properties[k].isZip){
				row = this.controls.scope.querySelector('[data-property-id-row="'+k+'"]');
				if(BX.type.isElementNode(row)){

					input = row.querySelector('input[type="text"]');
					if(BX.type.isElementNode(input)){
						this.properties[k].input = input;

						// set value for the first "location" property met
						locPropId = false;
						for(m in this.properties){
							if(this.properties[m].type == 'LOCATION'){
								locPropId = m;
								break;
							}
						}

						if(locPropId !== false){
							BX.bindDebouncedChange(input, function(value){

								input = null;
								row = null;

								if(BX.type.isNotEmptyString(value) && /^\s*\d+\s*$/.test(value) && value.length > 3){

									ctx.getLocationByZip(value, function(locationId){
										ctx.properties[locPropId].control.setValueByLocationId(locationId);
									}, function(){
										try{
											ctx.properties[locPropId].control.clearSelected(locationId);
										}catch(e){}
									});
								}
							});
						}
					}
				}
			}

			// location handling, town property, etc...
			if(this.properties[k].type == 'LOCATION')
			{

				if(typeof this.properties[k].control != 'undefined'){

					control = this.properties[k].control; // reference to sale.location.selector.*
					code = control.getSysCode();

					// we have town property (alternative location)
					if(typeof this.properties[k].altLocationPropId != 'undefined')
					{
						if(code == 'sls') // for sale.location.selector.search
						{
							// replace default boring "nothing found" label for popup with "-bx-popup-set-mode-add-loc" inside
							control.replaceTemplate('nothing-found', this.options.messages.notFoundPrompt);
						}

						if(code == 'slst')  // for sale.location.selector.steps
						{
							(function(k, control){

								// control can have "select other location" option
								control.setOption('pseudoValues', ['other']);

								// insert "other location" option to popup
								control.bindEvent('control-before-display-page', function(adapter){

									control = null;

									var parentValue = adapter.getParentValue();

									// you can choose "other" location only if parentNode is not root and is selectable
									if(parentValue == this.getOption('rootNodeValue') || !this.checkCanSelectItem(parentValue))
										return;

									var controlInApater = adapter.getControl();

									if(typeof controlInApater.vars.cache.nodes['other'] == 'undefined')
									{
										controlInApater.fillCache([{
											CODE:		'other', 
											DISPLAY:	ctx.options.messages.otherLocation, 
											IS_PARENT:	false,
											VALUE:		'other'
										}], {
											modifyOrigin:			true,
											modifyOriginPosition:	'prepend'
										});
									}
								});

								townInputFlag = BX('LOCATION_ALT_PROP_DISPLAY_MANUAL['+parseInt(k)+']');

								control.bindEvent('after-select-real-value', function(){

									// some location chosen
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '0';
								});
								control.bindEvent('after-select-pseudo-value', function(){

									// option "other location" chosen
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '1';
								});

								// when user click at default location or call .setValueByLocation*()
								control.bindEvent('before-set-value', function(){
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '0';
								});

								// restore "other location" label on the last control
								if(BX.type.isDomNode(townInputFlag) && townInputFlag.value == '1'){

									// a little hack: set "other location" text display
									adapter = control.getAdapterAtPosition(control.getStackSize() - 1);

									if(typeof adapter != 'undefined' && adapter !== null)
										adapter.setValuePair('other', ctx.options.messages.otherLocation);
								}

							})(k, control);
						}
					}
				}
			}
		}

		this.BXCallAllowed = true;
	},

	checkMode: function(propId, mode){

		//if(typeof this.modes[propId] == 'undefined')
		//	this.modes[propId] = {};

		//if(typeof this.modes[propId] != 'undefined' && this.modes[propId][mode])
		//	return true;

		if(mode == 'altLocationChoosen'){

			if(this.checkAbility(propId, 'canHaveAltLocation')){

				var input = this.getInputByPropId(this.properties[propId].altLocationPropId);
				var altPropId = this.properties[propId].altLocationPropId;

				if(input !== false && input.value.length > 0 && !input.disabled && this.properties[altPropId].valueSource != 'default'){

					//this.modes[propId][mode] = true;
					return true;
				}
			}
		}

		return false;
	},

	checkAbility: function(propId, ability){

		if(typeof this.properties[propId] == 'undefined')
			this.properties[propId] = {};

		if(typeof this.properties[propId].abilities == 'undefined')
			this.properties[propId].abilities = {};

		if(typeof this.properties[propId].abilities != 'undefined' && this.properties[propId].abilities[ability])
			return true;

		if(ability == 'canHaveAltLocation'){

			if(this.properties[propId].type == 'LOCATION'){

				// try to find corresponding alternate location prop
				if(typeof this.properties[propId].altLocationPropId != 'undefined' && typeof this.properties[this.properties[propId].altLocationPropId]){

					var altLocPropId = this.properties[propId].altLocationPropId;

					if(typeof this.properties[propId].control != 'undefined' && this.properties[propId].control.getSysCode() == 'slst'){

						if(this.getInputByPropId(altLocPropId) !== false){
							this.properties[propId].abilities[ability] = true;
							return true;
						}
					}
				}
			}

		}

		return false;
	},

	getInputByPropId: function(propId){
		if(typeof this.properties[propId].input != 'undefined')
			return this.properties[propId].input;

		var row = this.getRowByPropId(propId);
		if(BX.type.isElementNode(row)){
			var input = row.querySelector('input[type="text"]');
			if(BX.type.isElementNode(input)){
				this.properties[propId].input = input;
				return input;
			}
		}

		return false;
	},

	getRowByPropId: function(propId){

		if(typeof this.properties[propId].row != 'undefined')
			return this.properties[propId].row;

		var row = this.controls.scope.querySelector('[data-property-id-row="'+propId+'"]');
		if(BX.type.isElementNode(row)){
			this.properties[propId].row = row;
			return row;
		}

		return false;
	},

	getAltLocPropByRealLocProp: function(propId){
		if(typeof this.properties[propId].altLocationPropId != 'undefined')
			return this.properties[this.properties[propId].altLocationPropId];

		return false;
	},

	toggleProperty: function(propId, way, dontModifyRow){

		var prop = this.properties[propId];

		if(typeof prop.row == 'undefined')
			prop.row = this.getRowByPropId(propId);

		if(typeof prop.input == 'undefined')
			prop.input = this.getInputByPropId(propId);

		if(!way){
			if(!dontModifyRow)
				BX.hide(prop.row);
			prop.input.disabled = true;
		}else{
			if(!dontModifyRow)
				BX.show(prop.row);
			prop.input.disabled = false;
		}
	},

	submitFormProxy: function(item, control)
	{
		var propId = false;
		for(var k in this.properties){
			if(typeof this.properties[k].control != 'undefined' && this.properties[k].control == control){
				propId = k;
				break;
			}
		}

		// turning LOCATION_ALT_PROP_DISPLAY_MANUAL on\off

		if(item != 'other'){

			if(this.BXCallAllowed){

				this.BXCallAllowed = false;
				submitForm();
			}

		}
	},

	getPreviousAdapterSelectedNode: function(control, adapter){

		var index = adapter.getIndex();
		var prevAdapter = control.getAdapterAtPosition(index - 1);

		if(typeof prevAdapter !== 'undefined' && prevAdapter != null){
			var prevValue = prevAdapter.getControl().getValue();

			if(typeof prevValue != 'undefined'){
				var node = control.getNodeByValue(prevValue);

				if(typeof node != 'undefined')
					return node;

				return false;
			}
		}

		return false;
	},
	getLocationByZip: function(value, successCallback, notFoundCallback)
	{
		if(typeof this.indexCache[value] != 'undefined')
		{
			successCallback.apply(this, [this.indexCache[value]]);
			return;
		}

		ShowWaitWindow();

		var ctx = this;

		BX.ajax({

			url: this.options.source,
			method: 'post',
			dataType: 'json',
			async: true,
			processData: true,
			emulateOnload: true,
			start: true,
			data: {'ACT': 'GET_LOC_BY_ZIP', 'ZIP': value},
			//cache: true,
			onsuccess: function(result){

				CloseWaitWindow();
				if(result.result){

					ctx.indexCache[value] = result.data.ID;

					successCallback.apply(ctx, [result.data.ID]);

				}else
					notFoundCallback.call(ctx);

			},
			onfailure: function(type, e){

				CloseWaitWindow();
				// on error do nothing
			}

		});
	}

}

$( document ).ready(function() {
	$('#order_form_div').on('keydown','form',function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});
});
/* End */
;
; /* Start:"a:4:{s:4:"full";s:103:"/bitrix/components/bitrix/sale.ajax.delivery.calculator/templates/.default/proceed.min.js?1489124576797";s:6:"source";s:85:"/bitrix/components/bitrix/sale.ajax.delivery.calculator/templates/.default/proceed.js";s:3:"min";s:89:"/bitrix/components/bitrix/sale.ajax.delivery.calculator/templates/.default/proceed.min.js";s:3:"map";s:89:"/bitrix/components/bitrix/sale.ajax.delivery.calculator/templates/.default/proceed.map.js";}"*/
function deliveryCalcProceed(arParams){var delivery_id=arParams.DELIVERY_ID;var getExtraParamsFunc=arParams.EXTRA_PARAMS_CALLBACK;function __handlerDeliveryCalcProceed(e){var a=document.getElementById("delivery_info_"+delivery_id);if(a){a.innerHTML=e}PCloseWaitMessage("wait_container_"+delivery_id,true)}PShowWaitMessage("wait_container_"+delivery_id,true);var url="/bitrix/components/bitrix/sale.ajax.delivery.calculator/templates/.default/ajax.php";var TID=CPHttpRequest.InitThread();CPHttpRequest.SetAction(TID,__handlerDeliveryCalcProceed);if(!getExtraParamsFunc){CPHttpRequest.Post(TID,url,arParams)}else{eval(getExtraParamsFunc);BX.addCustomEvent("onSaleDeliveryGetExtraParams",function(e){arParams.EXTRA_PARAMS=e;CPHttpRequest.Post(TID,url,arParams)})}}
/* End */
;
; /* Start:"a:4:{s:4:"full";s:102:"/bitrix/components/bitrix/sale.location.selector.steps/templates/.default/script.min.js?14932932247752";s:6:"source";s:83:"/bitrix/components/bitrix/sale.location.selector.steps/templates/.default/script.js";s:3:"min";s:87:"/bitrix/components/bitrix/sale.location.selector.steps/templates/.default/script.min.js";s:3:"map";s:87:"/bitrix/components/bitrix/sale.location.selector.steps/templates/.default/script.map.js";}"*/
BX.namespace("BX.Sale.component.location.selector");if(typeof BX.Sale.component.location.selector.steps=="undefined"&&typeof BX.ui!="undefined"&&typeof BX.ui.widget!="undefined"){BX.Sale.component.location.selector.steps=function(e,t){this.parentConstruct(BX.Sale.component.location.selector.steps,e);BX.merge(this,{opts:{bindEvents:{"after-select-item":function(e){if(typeof this.opts.callback=="string"&&this.opts.callback.length>0&&this.opts.callback in window)window[this.opts.callback].apply(this,[e,this])}},disableKeyboardInput:false,dontShowNextChoice:false,pseudoValues:[],provideLinkBy:"id",requestParamsInject:false},vars:{cache:{nodesByCode:{}}},sys:{code:"slst"},flags:{skipAfterSelectItemEventOnce:false}});this.handleInitStack(t,BX.Sale.component.location.selector.steps,e)};BX.extend(BX.Sale.component.location.selector.steps,BX.ui.chainedSelectors);BX.merge(BX.Sale.component.location.selector.steps.prototype,{init:function(){this.pushFuncStack("buildUpDOM",BX.Sale.component.location.selector.steps);this.pushFuncStack("bindEvents",BX.Sale.component.location.selector.steps)},buildUpDOM:function(){},bindEvents:function(){var e=this,t=this.opts;if(t.disableKeyboardInput){this.bindEvent("after-control-placed",function(e){var t=e.getControl();BX.unbindAll(t.ctrls.toggle);BX.bind(t.ctrls.scope,"click",function(e){t.toggleDropDown()})})}BX.bindDelegate(this.getControl("quick-locations",true),"click",{tag:"a"},function(){e.setValueByLocationId(BX.data(this,"id"))})},setValueByLocationId:function(e){BX.Sale.component.location.selector.steps.superclass.setValue.apply(this,[e])},setValueByLocationIds:function(e){if(!e.PARENT_ID)return;this.flags.skipAfterSelectItemEventOnce=true;this.setValueByLocationId(e.PARENT_ID);this.bindEvent("after-control-placed",function(t){var s=t.getControl();if(s.vars.value!=false)return;if(e.IDS)this.opts.requestParamsInject={filter:{"=ID":e.IDS}};s.tryDisplayPage("toggle")})},setValueByLocationCode:function(e){var t=this.vars;if(e==null||e==false||typeof e=="undefined"||e.toString().length==0){this.displayRoute([]);this.setValueVariable("");this.setTargetValue("");this.fireEvent("after-clear-selection");return}this.fireEvent("before-set-value",[e]);var s=new BX.deferred;var i=this;s.done(BX.proxy(function(s){this.displayRoute(s);var i=t.cache.nodesByCode[e].VALUE;t.value=i;this.setTargetValue(this.checkCanSelectItem(i)?i:this.getLastValidValue())},this));s.fail(function(e){if(e=="notfound"){i.displayRoute([]);i.setValueVariable("");i.setTargetValue("");i.showError({errors:[i.opts.messages.nothingFound],type:"server-logic",options:{}})}});this.hideError();this.getRouteToNodeByCode(e,s)},setValue:function(e){if(this.opts.provideLinkBy=="id")BX.Sale.component.location.selector.steps.superclass.setValue.apply(this,[e]);else this.setValueByLocationCode(e)},setTargetValue:function(e){this.setTargetInputValue(this.opts.provideLinkBy=="code"?e?this.vars.cache.nodes[e].CODE:"":e);if(!this.flags.skipAfterSelectItemEventOnce)this.fireEvent("after-select-item",[e]);else this.flags.skipAfterSelectItemEventOnce=false},getValue:function(){if(this.opts.provideLinkBy=="id")return this.vars.value===false?"":this.vars.value;else{return this.vars.value?this.vars.cache.nodes[this.vars.value].CODE:""}},getNodeByLocationId:function(e){return this.vars.cache.nodes[e]},getSelectedPath:function(){var e=this.vars,t=[];if(typeof e.value=="undefined"||e.value==false||e.value=="")return t;if(typeof e.cache.nodes[e.value]!="undefined"){var s=e.cache.nodes[e.value];while(typeof s!="undefined"){var i=BX.clone(s);var n=i.PARENT_VALUE;delete i.PATH;delete i.PARENT_VALUE;delete i.IS_PARENT;if(typeof i.TYPE_ID!="undefined"&&typeof this.opts.types!="undefined")i.TYPE=this.opts.types[i.TYPE_ID].CODE;t.push(i);if(typeof n=="undefined"||typeof e.cache.nodes[n]=="undefined")break;else s=e.cache.nodes[n]}}return t},setInitialValue:function(){if(this.opts.selectedItem!==false)this.setValueByLocationId(this.opts.selectedItem);else if(this.ctrls.inputs.origin.value.length>0){if(this.opts.provideLinkBy=="id")this.setValueByLocationId(this.ctrls.inputs.origin.value);else this.setValueByLocationCode(this.ctrls.inputs.origin.value)}},getRouteToNodeByCode:function(e,t){var s=this.vars,i=this;if(typeof e!="undefined"&&e!==false&&e.toString().length>0){var n=[];if(typeof s.cache.nodesByCode[e]!="undefined")n=this.getRouteToNodeFromCache(s.cache.nodesByCode[e].VALUE);if(n.length==0){i.downloadBundle({request:{CODE:e},callbacks:{onLoad:function(o){for(var a in o){if(typeof s.cache.links[a]=="undefined")s.cache.incomplete[a]=true}i.fillCache(o,true);n=[];if(typeof s.cache.nodesByCode[e]!="undefined")n=this.getRouteToNodeFromCache(s.cache.nodesByCode[e].VALUE);if(n.length==0)t.reject("notfound");else t.resolve(n)},onError:function(){t.reject("internal")}},options:{}})}else t.resolve(n)}else t.resolve([])},addItem2Cache:function(e){this.vars.cache.nodes[e.VALUE]=e;this.vars.cache.nodesByCode[e.CODE]=e},controlChangeActions:function(e,t){var s=this,i=this.opts,n=this.vars,o=this.ctrls;this.hideError();if(t.length==0){s.truncateStack(e);n.value=s.getLastValidValue();s.setTargetValue(n.value);this.fireEvent("after-select-real-value")}else if(BX.util.in_array(t,i.pseudoValues)){s.truncateStack(e);s.setTargetValue(s.getLastValidValue());this.fireEvent("after-select-item",[t]);this.fireEvent("after-select-pseudo-value")}else{var a=n.cache.nodes[t];if(typeof a=="undefined")throw new Error("Selected node not found in the cache");s.truncateStack(e);if(i.dontShowNextChoice){if(a.IS_UNCHOOSABLE)s.appendControl(t)}else{if(typeof n.cache.links[t]!="undefined"||a.IS_PARENT)s.appendControl(t)}if(s.checkCanSelectItem(t)){n.value=t;s.setTargetValue(t);this.fireEvent("after-select-real-value")}}},refineRequest:function(e){var t={};var s={VALUE:"ID",DISPLAY:"NAME.NAME",1:"TYPE_ID",2:"CODE"};var i={};if(typeof e["PARENT_VALUE"]!="undefined"){t["=PARENT_ID"]=e.PARENT_VALUE;s["10"]="IS_PARENT"}if(typeof e["VALUE"]!="undefined"){t["=ID"]=e.VALUE;i["1"]="PATH"}if(BX.type.isNotEmptyString(e["CODE"])){t["=CODE"]=e.CODE;i["1"]="PATH"}if(BX.type.isNotEmptyString(this.opts.query.BEHAVIOUR.LANGUAGE_ID))t["=NAME.LANGUAGE_ID"]=this.opts.query.BEHAVIOUR.LANGUAGE_ID;if(BX.type.isNotEmptyString(this.opts.query.FILTER.SITE_ID)){if(typeof this.vars.cache.nodes[e.PARENT_VALUE]=="undefined"||this.vars.cache.nodes[e.PARENT_VALUE].IS_UNCHOOSABLE)t["=SITE_ID"]=this.opts.query.FILTER.SITE_ID}var n={select:s,filter:t,additionals:i,version:"2"};if(this.opts.requestParamsInject){for(var o in this.opts.requestParamsInject){if(this.opts.requestParamsInject.hasOwnProperty(o)){if(n[o]==undefined)n[o]={};for(var a in this.opts.requestParamsInject[o]){if(this.opts.requestParamsInject[o].hasOwnProperty(a)){if(n[o][a]!=undefined){var r=n[o][a];n[o][a]=[];n[o][a].push(r)}else{n[o][a]=[]}for(var l in this.opts.requestParamsInject[o][a])if(this.opts.requestParamsInject[o][a].hasOwnProperty(l))n[o][a].push(this.opts.requestParamsInject[o][a][l])}}}}}return n},refineResponce:function(e,t){if(e.length==0)return e;if(typeof t.PARENT_VALUE!="undefined"){var s={};s[t.PARENT_VALUE]=e["ITEMS"];e=s}else if(typeof t.VALUE!="undefined"||typeof t.CODE!="undefined"){var i={};if(typeof e.ITEMS[0]!="undefined"&&typeof e.ETC.PATH_ITEMS!="undefined"){var n=0;for(var o=e.ITEMS[0]["PATH"].length-1;o>=0;o--){var a=e.ITEMS[0]["PATH"][o];var r=e.ETC.PATH_ITEMS[a];r.IS_PARENT=true;i[n]=[r];n=r.VALUE}i[n]=[e.ITEMS[0]]}e=i}return e},showError:function(e){if(e.type!="server-logic")e.errors=[this.opts.messages.error];this.ctrls.errorMessage.innerHTML='<p><font class="errortext">'+BX.util.htmlspecialchars(e.errors.join(", "))+"</font></p>";BX.show(this.ctrls.errorMessage);BX.debug(e)}})}
/* End */
;; /* /bitrix/components/bitrix/sale.location.selector.search/templates/.default/script.min.js?14932932247747*/
; /* /local/templates/elektro_flat/components/bitrix/sale.order.ajax/.default/script.js?150706317311163*/
; /* /bitrix/components/bitrix/sale.ajax.delivery.calculator/templates/.default/proceed.min.js?1489124576797*/
; /* /bitrix/components/bitrix/sale.location.selector.steps/templates/.default/script.min.js?14932932247752*/

//# sourceMappingURL=page_ca4d42dced0829c074db9fa00908a340.map.js