/**
 * EYuiSearch
 * 
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
var EYuiSearch = function(options){
	
	var searchButton = $('#'+options.id+' .searchbar input.button');
	var textToSearch = $('#'+options.id+' .searchbar input.textinput');
	var selectList = $('#'+options.id+' .resultsbar select');
	var loader = $('#'+options.id+' .searchbar img');
	var resultsBar = $('#'+options.id+' .resultsbar');
	var okButton = $('#'+options.id+' .resultsbar input.button');
	var errorSpan = $('#'+options.id+' span.error');
	var attributeTobeSetted = $('#'+options.modelAttributeId);
	
	searchButton.click(function(){
		var txt = jQuery.trim(textToSearch.val()); // IE no soporta xxx.trim()
		errorSpan.html('');
		if(txt.length > 0)
		{
			var url = options.action+"&action=search&data="+options.data+"&searchtext="+txt;
			selectList.find('option').each(function(){ $(this).remove(); });
			loader.show();
			$.getJSON(url, function(data) {
				var nitems=0;
				$.each(data, function(index, val) {
					selectList.append("<option value='"+index+"'>"+val+"</option>");
					nitems++;
				});
				if(nitems == 0){
					// no results found
					loader.hide();
					resultsBar.hide('fast');
					errorSpan.html('no results found');
					setTimeout(function(){
						errorSpan.html('');
					},3000);
					options.onEmptyResult(txt);
				}
				else{
					loader.hide();
					resultsBar.show('fast');
				}
			}).error(function(e){	
				loader.hide();
				options.onError(e);
			});
		}
	});
	
	textToSearch.keydown(function(){
		resultsBar.hide('fast');
		selectList.find('option').each(function(){ $(this).remove(); });
	});
	
	okButton.click(function(){
		if(selectList.val() != ''){
			
			attributeTobeSetted.val(selectList.val());
			
			selectList.find('option').each(function(){
				var opt = $(this);
				if(opt.val() == selectList.val()){
					options.onSuccess(opt.val(),opt.text());
					resultsBar.hide('fast');
				}
			});
		}
	});
	
};