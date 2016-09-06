// (string)options.id, (string)options.action, (callback)options.onError(e)
	/*
		$.getJSON(url, function(data) {
			$.each(data, function(index, val) {
				listaOrigen.append("<option value='"+val.value+"'>"+val.label+"</option>");
			});
			
		}).error(function(e){
			options.onError(e);
		});
	*/
var EYuiRelation = function(options){
	
	var action = options.action;
	var instance = $('#'+options.id);
	var loading = false;
	
	var list_get = function(){
		return instance.find('select');
	}
	var ul_get = function(){
		return instance.find('ul');
	}
	var button_get = function(){
		return instance.find('input');
	}
	var list_clear = function(){
		instance.find('select').find('option').each(function(){ $(this).remove(); });
	}
	var list_addli = function(value,text){
		var lista = ul_get();
		lista.append("<li value='"+value+"'><span>"+text+"</span><img src='"+options.deleteImgUrl+"' class='accion'></li>");
		lista.find('li').each(function(){
			var li = $(this);
			if(li.val() == value){
				clic_handler(li);
			}
		});
	}
	var showLoader = function(){
		instance.find('img.loading').show();
		loading = true;
		list_get().attr('disabled','disabled');
		button_get().attr('disabled','disabled');
	}
	var hideLoader = function(){
		instance.find('img.loading').hide();
		loading = false;
		list_get().attr('disabled',null);
		button_get().attr('disabled',null);
	}
	var clic_handler = function(li){
		var spanX = li.find('.accion');
		spanX.css("cursor","pointer");
		spanX.click(function(){
			if(confirm(options.queryRemoveMessage))
			on_removeoption(li.val(),function(ok){
				li.remove();
			});
		});
	}

	var on_addnewoption = function(value){
		if(loading == true)
			return;
		var url = options.action+"&action=addnewoption&data="+value+"&id="+options.id;
		showLoader();
		$.getJSON(url, function(obj) {
			list_addli(obj.id,obj.text);
			hideLoader();
		}).error(function(e){
			options.onError(e);
			hideLoader();
		});
	}
	
	var on_removeoption = function(value,callback){
		var url = options.action+"&action=removeoption&data="+value+"&id="+options.id;
		showLoader();
		$.getJSON(url, function(result) {
			hideLoader();
			if(callback)
				callback(true);
		}).error(function(e){
			hideLoader();
			options.onError(e);
			if(callback)
				callback(false);
		});
	}
	
	button_get().click(function(){
		var list = list_get();
		if(list.val() != ''){
			on_addnewoption(list.val());
		}
		else
		alert(options.errorMsgPleaseSelectOption);
	});
	
	ul_get().find('li').each(function(){
		clic_handler($(this));
	});
}