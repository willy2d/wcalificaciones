/**
 * EYuiFormEditor class file. 
 * 	depends on: eyuiverticalitem.js
 * 
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
var EYuiFormEditor = function(options){
	
	var div = $('#'+options.id);
	var logger = $('#'+options.loggerId);
	
	var action = options.action;
	var tabP;
	var tabG;
	var tabF;
	
	var curPage;
	var curGroup;

	var refresh = function(tab, parent_id){
		
		var item_type = '';
		if(tab == tabP)
			item_type = 'page';
		if(tab == tabG)
			item_type = 'group';
		if(tab == tabF)
			item_type = 'field';
		tab.clearItems();
		logger.html("");
		var url = action+'&action=listitems&data='+options.id+'&id='+parent_id+'&item_type='+item_type;
		$.getJSON(url, function(data) {
			tab.hideLoading();
			$.each(data, function(key, obj) {
				//alert(key+"="+JSON.stringify(obj));
				tab.addItem(obj);
			});
		}).error(function(e){ 
			tab.hideLoading();
			logger.html(e.responseText); 
		});		
	}
	
	var newItem = function(tab,parent_id,callback){
		var item_type = '';
		if(tab == tabP)
			item_type = 'page';
		if(tab == tabG)
			item_type = 'group';
		if(tab == tabF)
			item_type = 'field';
		logger.html("");
		
		var url = action+'&action=newitem&data='+options.id+"&item_type="+item_type;
		if(parent_id != null)
			url += '&id='+parent_id;
			
		tab.showLoading();
		$.getJSON(url, function(obj) {
			tab.hideLoading();
			refresh(tab,parent_id);
			if(callback)
				callback(true);
		}).error(function(e){ 	
			tab.hideLoading();
			logger.html(e.responseText); 
			if(callback)
				callback(false);
		});		
	}
	
	var updateItem = function(obj,callback){
		var url = action+'&action=updateitem&data='+options.id;
		var result = false;
		jQuery.ajax({url: url,async: true, type: 'post',contentType: "application/json",
			data: JSON.stringify(obj),
			success: function(data, textStatus, jqXHR){
				if(data == 'OK'){
					result = true;
				}else	
				logger.append(data); 
				if(callback)
					callback(true);
			},
			error: function(e, textStatus, errorThrown){
				logger.append(e.responseText); 
				if(callback)
					callback(false);
			}
		});	
		return result;
	}
	
	var updatePosition = function(obj1,obj2) {
		updateItem(obj1);
		updateItem(obj2);
		return true;
	}

	var deleteItem = function(tab,obj,callback){
		if(confirm(options.pleaseConfirmMessage)){
			logger.html("");
			var url = action+'&action=deleteitem&data='+options.id+'&id='+obj.id;
			tab.showLoading();
			var result = false;
			jQuery.ajax({url: url,async: false, type: 'get',
				success: function(data, textStatus, jqXHR){
					if(data=='OK'){
						result=true;
						if(callback)
							callback(true);
					}else{
						logger.html(data); 
						if(callback)
							callback(false);
					}
				},
				error: function(e, textStatus, errorThrown){
					logger.html(e.responseText); 
					if(callback)
						callback(false);
				}
			});			
			tab.hideLoading();
			return result;
		}
	}
	
	var launchDialogEdit = function(tab, obj){
		var dialogbox = $("#"+options.editPageAndGroupDialogId);
		var form = $("#"+options.editPageAndGroupDialogId+"_form");
		form.data('obj',obj);
		form.setField = function(name,value){
			$(this).find("[name|='"+name+"']").val(value);
		}
		form.getField = function(name){
			return $(this).find("[name|='"+name+"']").val();
		}
		
		/*
			required on jquery-validate.js:
			
			alphastrict: function(value, element) {
				return this.optional(element) || /^[A-Za-z0-9]+$/.test(value);
			},
		*/
		form.validate({
			   rules: {
			     page: { required: true , alphastrict: true },
				 label: { required: true },
				 position: { digits: true, required: true}
			   },
			   messages: {
			     page: options.PageRequiredMessage,
				 label: options.LabelRequiredMessage,
				 position: options.PositionRequiredMessage
			   },
			   submitHandler: function(){
					var obj = form.data('obj');
					var object = {
						id: obj.id, 
						item_id: form.getField('page'), 
						label: form.getField('label'), 
						descr: form.getField('descr'), 
						position: form.getField('position'), 
						data: ''
					};
					tab.showLoading();
					updateItem(object,function(ok){
						if(ok==true){
							form.data('obj',object);
							tab.updateItem(form.data('obj'));
							dialogbox.dialog("close");
							tab.hideLoading();
						}
					});
			   }
		});		
		
		var dlgOpt = {
			autoOpen: false,
			resizable: false,
			open:function(){
				form.setField('position',obj.position);
				form.setField('page',obj.item_id);
				form.setField('label',obj.label);
				form.setField('descr',obj.descr);
			},
			close:function(){},
			buttons: {
				"Ok": {
					text: options.OkButtonText,
					click: function() {
						form.submit();
				}},
				"Cancel": {
					text: options.CancelButtonText,
					click: function() { 
						$(this).dialog("close"); 
					}
				}
			
			}
		};
		
		dialogbox.dialog(dlgOpt);
		dialogbox.dialog('open');
	}


	var launchDialogEditField = function(tab, obj){
		var dialogbox = $("#"+options.editFieldDialogId);
		var form = $("#"+options.editFieldDialogId+"_form");
		var fields = ['uicomponent','default','dateformat','options','required','requiredmessage'
			,'pattern','patternmessage','maxlength','size','class','style'];
		form.data('obj',obj);
		form.getItem = function(name){
			return $(this).find("[name|='"+name+"']");
		}
		form.setField = function(name,value){
			$(this).find("[name|='"+name+"']").val(value);
		}
		form.getField = function(name){
			return $(this).find("[name|='"+name+"']").val();
		}
		
		/*
			required on jquery-validate.js:
			
			alphastrict: function(value, element) {
				return this.optional(element) || /^[A-Za-z0-9]+$/.test(value);
			},
		*/
		form.validate({
			   rules: {
			     page: { required: true , alphastrict: true },
				 label: { required: true },
				 position: { digits: true, required: true}
			   },
			   messages: {
			     page: options.PageRequiredMessage,
				 label: options.LabelRequiredMessage,
				 position: options.PositionRequiredMessage
			   },
			   submitHandler: function(){
					var obj = form.data('obj');
					var entries = [];
					$.each(fields,function(k,name){
						var val = form.getField(name);
						if(name == 'required')
							val = (form.getItem(name).attr('checked') == 'checked') ? 1 : 0;
						entries[k] = {'name': name, 'value': val};
					});
					var object = {
						id: obj.id, 
						item_id: form.getField('page'), 
						label: form.getField('label'), 
						descr: form.getField('descr'), 
						position: form.getField('position'), 
						data: JSON.stringify(entries)
					};
					tab.showLoading();
					
					updateItem(object,function(ok){
						if(ok==true){
							form.data('obj',object);
							tab.updateItem(form.data('obj'));
							dialogbox.dialog("close");
							tab.hideLoading();
						}
					});
			   }
		});		
		
		var dlgOpt = {
			autoOpen: false,
			resizable: false,
			width: '850px',
			//height: "300px",
			open:function(){
				form.setField('page',obj.item_id);
				form.setField('label',obj.label);
				form.setField('descr',obj.descr);
				form.setField('position',obj.position);
				
				$.each(fields,function(k,name){
					form.setField(name,'');
					if(name == 'required')
						form.getItem(name).attr('checked',null);
				});
				
				data = JSON.parse(obj.data);
				$.each(data,function(k,entry){
					if(entry.name == 'required'){
						// is a checkbox
						var input = form.getItem(entry.name);
						if(((entry.value)*1) == 0){
							input.attr('checked',null);
						}else
							input.attr('checked','checked');
					}
					else
					form.setField(entry.name,entry.value);
				})
			},
			close:function(){},
			buttons: {
				"Ok": {
					text: options.OkButtonText,
					click: function() {
						form.submit();
				}},
				"Cancel": {
					text: options.CancelButtonText,
					click: function() { 
						$(this).dialog("close"); 
					}
				}
			
			}
		};
		dialogbox.dialog(dlgOpt);
		dialogbox.dialog('open');
	}


	
	div.find('.tab').each(function(k){
		var tab = $(this);
		tab.id = 'tab_'+k;
		options.logger = logger;
		if(k==0) tabP = new EYuiVerticalItem(tab,options);
		if(k==1) tabG = new EYuiVerticalItem(tab,options);
		if(k==2) tabF = new EYuiVerticalItem(tab,options);
	});

	tabP.getNewItemLink().click(function(){
		newItem(tabP,null,function(ok){
			tabG.clearItems();
			tabF.clearItems();
			curPage = null;
			curGroup = null;
		});
	});
	tabG.getNewItemLink().click(function(){
		newItem(tabG,curPage.id,function(ok){
			tabF.clearItems();
			curGroup = null;
		});
	});
	tabF.getNewItemLink().click(function(){
		newItem(tabF,curGroup.id);
	});
	
	tabP.onClickItem = function(obj){
		// show groups
		curPage = obj;
		refresh(tabG,curPage.id);
		tabF.clearItems();
	}
	tabG.onClickItem = function(obj){
		// show fields
		curGroup = obj;
		refresh(tabF,curGroup.id);
	}
	
	tabP.onEditItem = function(obj){
		launchDialogEdit(tabP,obj);
	}
	tabG.onEditItem = function(obj){
		launchDialogEdit(tabG,obj);
	}
	tabF.onEditItem = function(obj){
		launchDialogEditField(tabF,obj);
	}
	
	tabP.onUpdatePosition = updatePosition;
	tabG.onUpdatePosition = updatePosition;
	tabF.onUpdatePosition = updatePosition;
	tabP.onDeleteItem = function(obj){
		return deleteItem(this,obj,function(ok){
			tabG.clearItems();
			tabF.clearItems();
			curPage = null;
			curGroup = null;
		});
	}
	tabG.onDeleteItem = function(obj){
		return deleteItem(this,obj,function(ok){
			tabF.clearItems();
			curGroup = null;
		});
	}
	tabF.onDeleteItem = function(obj){
		return deleteItem(this,obj);
	}
	
	// loads the current stored pages via ajax
	refresh(tabP,null);
	//launchDialogEditField(tabF,null);
};