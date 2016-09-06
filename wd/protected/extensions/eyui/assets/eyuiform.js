/**
 * EYuiForm class file. 
 * 
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
var EYuiForm = function(options){
	
	var form = $("#"+options.formid);
	var logger = $("#"+options.formid+"_logger");
	var saving = $("#"+options.formid+"_saving");
	var submit = $("#"+options.formsubmitbuttonid);	
	
	
	submit.click(function(){
	
		logger.html("");
		var fieldvalues=[];
		form.find("[alt|='input']").each(function(k){
			var input = $(this);
			var value = input.val();
			// special case: checkbox
			if(input.attr('type')=='checkbox')
				value = input.attr('checked') == 'checked' ? value : 0;
			fieldvalues[k] = { id: input.attr('id') , val: value };
		});
		
		submit.attr("disabled","disabled");
		saving.html("<img src='"+options.loadingUrl+"'>");
		var clearFn = function(){
			setTimeout(function(){
				saving.html("");
				submit.attr("disabled",null);
			},1000);
		}
		
		var post = JSON.stringify(fieldvalues);
		// ajax call
		$.ajax({
			url: options.action+'&action=submit&data=nothing',
			type: 'post',
			async: false,
			contentType: "application/json",
			data: post,
			success: function(data, textStatus, jqXHR){
				clearFn();
				// data format:
				//	data.result 	boolean
				//  data.mode		'text','jsonarray'
				//	data.fieldname	'anyfieldname'
				//	data.message	'a message to be shown to user'
				if(data.result == true){
					logger.removeClass("error");
					logger.addClass("success");
					logger.append(options.formSubmittedOkLabel);
				}
				else{
					logger.removeClass("success");
					logger.addClass("error");
					logger.html(options.submitErrorText);
					
					// data.errors is an array.
					// each entry is described in:
					// EYuiForm.php::runAction, array: errorFields
					var ulErrId = options.formid+"_errorlist";
					var fullErrorList = "<ul id='"+ulErrId+"' class='errorresume' style='display:none;'>";
					$.each(data.errors,function(k,item){
						fullErrorList += "<li>["+item.label+"]<br/><b>"+item.message+". "+item.help
							+"</b><p>"+item.page+" / "+item.group+"</p></li>";
					});
					fullErrorList += "</ul>";
						
					// colorize error fields:
					$.each(data.errors,function(k,item){
						$('#'+item.fieldname).removeClass("success");
						$('#'+item.fieldname).addClass("error");
						//$('#'+item.fieldname).attr('title',item.message+"\n"+item.help);
					});
					
					var errId = options.formid+"_showerror";
					logger.append("&nbsp;<a id='"+errId+"'>"+options.showErrorsLabel+"</a>");
					logger.append(fullErrorList);
					$('#'+errId).css("cursor","pointer");
					$('#'+errId).click(function(){
						$('#'+ulErrId).toggle();
					});
					if(options.showErrorResume == true)
						$('#'+ulErrId).toggle();
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				clearFn();
				logger.html("ERROR:\n"+jqXHR.responseText);
			}
		});
	});
	
	
	// to each field marked with alt='input' it will bind a change event on it
	form.find("[alt|='input']").each(function(k){
		$(this).change(function(){
			var input = $(this);
			var loader = input.parent().parent().find("span.loader");
			var errorinfo = input.parent().parent().find("div.error");
			var label = input.parent().parent().find("label");
		
			var imp = $(this);
			loader.html("<img src='"+options.loadingUrl+"'>");
			submit.attr("disabled","disabled");
			imp.attr("disabled","disabled");
			errorinfo.html("...");
			var clear_fn = function(){
				setTimeout(function(){
					loader.html("");
					submit.attr("disabled",null);
					imp.attr("disabled",null);
				},50);
			}
			
			// launch ajax validator
			var post = JSON.stringify({ id:input.attr('id'), val: input.val()});
			$.ajax({
				url: options.action+'&action=fieldchange&data=nothing',
				type: 'post',
				async: false,
				contentType: "application/json",
				data: post,
				success: function(data, textStatus, jqXHR){
					// data format:
					//	data.result 	boolean
					//  data.mode		'text','jsonarray'
					//	data.fieldname	'anyfieldname'
					//	data.message	'a message to be shown to user'
					if(data.result == true){
						logger.removeClass("error");
						logger.addClass("success");
						logger.html(data.message);
						errorinfo.removeClass("error");
						errorinfo.addClass("success");
						errorinfo.html(data.message);
						$('#'+data.fieldname).removeClass("error");
						$('#'+data.fieldname).addClass("success");
						//$('#'+data.fieldname).attr('title','');
						clear_fn();
					}
					else{
						logger.removeClass("success");
						logger.addClass("error");
						logger.html(data.message+" ["+label.html()+"]");
						errorinfo.removeClass("success");
						errorinfo.addClass("error");
						errorinfo.html(data.message);
						$('#'+data.fieldname).removeClass("success");
						$('#'+data.fieldname).addClass("error");
						//$('#'+data.fieldname).attr('title',data.message+"\n"+data.help);
						clear_fn();
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					logger.html("ERROR:\n"+jqXHR.responseText);
					clear_fn();
				}
			});
		});
	});

	
	
	
};