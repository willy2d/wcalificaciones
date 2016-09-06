/**
 * EYuiVerticalItem class file.
 *
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
var EYuiVerticalItem = function(_tab,_options){
	var tab = _tab;
	var opt = _options;
	var previous;
	
	/*
		<div class='tab'>
			<h1>{$this->tabPagesTitle}</h1>
			<a title='{$this->newPageTitle}'>{$this->newPageLabel}</a><img src='..' class='loading'>
			<div class='items'>
				<div class='item'>
					<span class='leftspan'>Long title for this item</span>
					<span class='rightspan'>
						<img src='...' class='edit'>
						<img src='...' class='up'>
						<img src='...' class='dw'>
						<img src='...' class='remove'>
					</span>
				</div>
			</div>
		</div>
	*/
	
	// events:
	
	this.onClickItem = function(obj){ }		// void return
	this.onEditItem = function(obj){ }		// void return
	this.onDeleteItem = function(obj){ } 	// must return true or false
	this.onUpdatePosition = function(obj1,obj2) {}; // must return true or false
	
	// methods
	
	this.getItemsDiv = function(){
		return tab.find('.items');
	}
	this.getNewItemLink = function(){
		return tab.find('a');
	}
	
	this.getLoading = function(){
		return tab.find('img.loading');
	}
	this.showLoading = function(){
		this.getLoading().show();
	}
	this.hideLoading = function(){
		this.getLoading().hide();
	}
	
	this.clearItems = function(){
		var items = this.getItemsDiv();
		items.html("");
	}
	
	this.findItem = function(obj){
		var items = this.getItemsDiv();
		var result = null;
		items.find('.item').each(function(){
			var _obj = $(this).data('obj');
			if(((_obj.id)*1) == ((obj.id)*1)){
				result = $(this);
			}
		});
		return result;
	}
	
	this.findNextItem = function(item){
		var items = this.getItemsDiv();
		var id = item.data('obj').id;
		var result = null;
		var next=false;
		items.find('.item').each(function(){
			var _curItem = $(this);
			if(next == true && result==null){
				result = _curItem;
			}else
			if(_curItem.data('obj').id == id)
				next=true;
		});
		return result;
	}

	this.findPrevItem = function(item){
		var items = this.getItemsDiv();
		var id = item.data('obj').id;
		var prev=null;
		var result=null;
		items.find('.item').each(function(){
			var _curItem = $(this);
			if(_curItem.data('obj').id == id && result==null)
				result = prev;
			prev = _curItem;
		});
		return result;
	}

	
	this.updateItem = function(obj){
		var item = this.findItem(obj);
		if(item != null){
			item.data('obj',obj);
			var span = item.find('.leftspan');
			span.html(obj.label+"<span class='itemid'>("+obj.item_id+",pos:"+obj.position+")</span>");
			this.reorder();
		}
	}
	
	this._prepareItem = function(item,obj){
		var _this = this;
		// store the object on it
		item.data('obj',obj);
		item.data('cancelclick',false);
		item.click(function(){
			 if(item.data('cancelclick')==true){
				item.data('cancelclick',false);
			}else{
				var previous = _this.previous;
				if(previous != null)
					previous.removeClass('eyuiformeditor_itemactive');
				$(this).addClass('eyuiformeditor_itemactive');
				_this.previous= $(this);
				_this.onClickItem(item.data('obj'));
			}
		});
		item.find('img.edit').click(function(){
			item.data('cancelclick',true);
			_this.onEditItem(item.data('obj'));
		});
		
		item.find('img.up').click(function(){
			item.data('cancelclick',true);
			var other = _this.findPrevItem(item);
			if(other != null){
				var tmp = item.data('obj').position;
				item.data('obj').position = other.data('obj').position;
				other.data('obj').position = tmp;
				if(_this.onUpdatePosition(item.data('obj'),other.data('obj')))
					_this.reorder();
			}
		});
		item.find('img.dw').click(function(){
			item.data('cancelclick',true);
			var other = _this.findNextItem(item);
			if(other != null){
				var tmp = item.data('obj').position;
				item.data('obj').position = other.data('obj').position;
				other.data('obj').position = tmp;
				if(_this.onUpdatePosition(item.data('obj'),other.data('obj')))
					_this.reorder();
			}
		});
		
		item.find('img.remove').click(function(){
			item.data('cancelclick',true);
			if(_this.onDeleteItem(item.data('obj'))==true)
					item.remove();
		});
		_this.reorder();
	}
	
	this.addItem = function(obj){
		var _this = this;
		var items = this.getItemsDiv();
		// appends a new item
		items.append("<div class='item' alt='"+obj.id+"'><span class='leftspan'>"
			+obj.label+"<span class='itemid'>("+obj.item_id+",pos:"+obj.position+")</span>"
			+"</span><span class='rightspan'>"
				+"<img src='"+opt.iconedit+"' class='edit'>"
				+"<img src='"+opt.iconup+"' class='up'>"
				+"<img src='"+opt.icondw+"' class='dw'>"
				+"<img src='"+opt.icondelete+"' class='remove'>"
			+"</span></div>");
		// finds a child item in items collection containing an alt attribute with value obj.id
		var item = items.find("[alt|='"+obj.id+"']");
		_this._prepareItem(item,obj);
	}
	
	this._mustSwap = function() {
		var items = this.getItemsDiv();
		var last=null;
		var result=null;
		items.find('.item').each(function(){
			var obj = $(this).data('obj');
			if(result==null)	
				if(last != null)
					if((last.position > obj.position))
						result = {a: last, b: obj};
			last = obj;
		});
		return result;
	}
	
	this.reorder = function(){
		var n=1;
		while((swp = this._mustSwap()) != null){
			var item1 = this.findItem(swp.a);
			var item2 = this.findItem(swp.b);
			this.getItemsDiv().prepend(item2);
		}
	}
};