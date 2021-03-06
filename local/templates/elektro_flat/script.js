function adjustItemHeight(item) {
	var maxHeight = 0;
	item.css("height", "auto");
	item.each(function() {
		if($(this).height() > maxHeight) {
			maxHeight = $(this).height();
		}
	});
	item.height(maxHeight);
}

$(function(){
	$(".close").live("click", function() {
		CloseModalWindow("#addItemInCart")
	});
	$(document).keyup(function(event){
		if(event.keyCode == 27) {
			CloseModalWindow("#addItemInCart")
		}
	});
});

function CentriredModalWindow(ModalName){
	$(window).resize(function () {
		modalHeight = ($(window).height() - $(ModalName).height()) / 2;
		$(ModalName).css({
			'top': modalHeight + 'px'
		});
	});
	$(window).resize();
}

function OpenModalWindow(ModalName){
	$(ModalName).fadeIn(300);	
	$("#bgmod").fadeIn(300);
}

function CloseModalWindow(ModalName){
	$("#bgmod").fadeOut(300);
	$(ModalName).fadeOut(300);	
}

function refreshCartLine(result, disabled) {
	disabled = disabled || false;	

	var basketCont, sumOld, sumCurr;
	
	basketCont = $(".cart_line");
	
	basketCont.find(".qnt").text($(result).find(".qnt").text());
	
	basketCont.find(".sum").data("decimal", $(result).find(".sum").data("decimal"));
	
	sumOld = basketCont.find(".sum").data("sum");						
	basketCont.find(".sum").data("sum", $(result).find(".sum").data("sum"));
	sumCurr = basketCont.find(".sum").data("sum");
	
	if(sumCurr != sumOld) {
		var options = {
			useEasing: false,
			useGrouping: true,
			separator: basketCont.find(".sum").data("separator"),
			decimal: basketCont.find(".sum").data("dec-point")
		}
		var counter = new countUp("cartCounter", sumOld, sumCurr, basketCont.find(".sum").data("decimal"), 0.5, options);
		counter.start();
	}
	
	if(disabled != true)
		basketCont.find(".oformit_cont").html($(result).find(".oformit_cont").html());	
}

function addToCompare(href, btn, site_dir) {
	$.ajax({
		type: "POST",
		url: href,
		success: function(html){			
			$.post(site_dir + "ajax/compare_line.php", function(data) {
				$(".compare_line").replaceWith(data);
			});
			$("#" + btn).removeClass("catalog-item-compare").addClass("catalog-item-compared").removeAttr("onclick").css({"cursor": "default"});
		}
	});
	return false;
}

function addToDelay(id, qnt_cont, props, select_props, btn, site_dir, dsbl) {
	dsbl = dsbl || false;
	$.ajax({
		type: "POST",
		url: site_dir + "ajax/add2delay.php",
		data: "id=" + id + "&qnt=" + $("#" + qnt_cont).val() + "&props=" + props + "&select_props=" +select_props,
		success: function(html){
			$.post(site_dir + "ajax/delay_line.php", function(data) {
				$(".delay_line").replaceWith(data);
				console.log(data);
			});
			$.post(site_dir + "ajax/basket_line.php", function(data) {
				refreshCartLine(data, dsbl);				
			});			
			$("#" + btn).removeClass("catalog-item-delay").addClass("catalog-item-delayed").removeAttr("onclick").css({"cursor": "default"});
		}
	});
	return false;
}


$('body').on('click','.title-search-result .pop-up-close.search_close', function(){
	$('body').find('.title-search-result').css('display','none');
});