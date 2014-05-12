(function($){

var numberOfTabs = new Array();

$(window).load(function(){	
	var numItems = 0;
	var itemClicked = 0;
	var first = 0;
	var itemIconListClicked = 0;
	var url = $('#plugin-url').val();
	
	$( '.color-picker-iris' ).each(function(){
            $(this).css('background', $(this).val());
            $(this).iris({
				height: 145,
                target:$(this).parent().find('.color-picker-iris-holder'),
				change: function(event, ui) {
                    $(this).val(ui.color.toString());
                    $(this).css( 'background-color', ui.color.toString());
                }
            });
    });
	
	$(document).on('click', '.color-picker-iris', function() {
		$('.color-picker-iris-holder').each(function() {
			$(this).css('display', 'none');
		});
		$(this).parent().find('.color-picker-iris-holder').css('display', 'block');
	});
	
	$('body').not('.color-picker-iris').click(function() {
		$('.color-picker-iris-holder').each(function() {
				$(this).css('display', 'none');
			});
	});
	
	//initialization for different pins
	
	if ($('#item-icon').attr('src').indexOf('images/icons/2/') >= 0)
	{
			$('#item-font-size').html('12');
			$('#item-font-size').attr('value', '12');
			$('#item-font-size').attr('readonly', 'readonly');
			$('#item-header-font-size').html('12');
			$('#item-header-font-size').attr('value', '12');
			$('#item-header-font-size').attr('readonly', 'readonly');
			$('#item-height').html('75');
			$('#item-height').attr('value', '75');
			$('#item-height').attr('readonly', 'readonly');
			
			$('#dummy-imapper-item-open-position').find('option').each(function() {
				if ($(this).attr('value') == 'top' || $(this).attr('value') == 'bottom')
					$(this).remove();	
			});
			
			$('.imapper-sortable-real').each(function() {
				$(this).find('option').each(function() {
					if ($(this).attr('value') == 'top' || $(this).attr('value') == 'bottom')
						$(this).remove();
				});
			});
	}
	
	if ($('#item-icon').attr('src').indexOf('images/icons/1/') >= 0)
	{
		$('#imapper-sortable-dummy').append('<li><input type="button" value="+ Add new tab" id="item-content-button-new" style="margin-left: 130px;" /><input type="button" value="- Remove last tab" id="item-content-button-remove" style="margin-left: 15px;" /></li>');
	}
	
	if ($('#item-icon').attr('src').indexOf('images/icons/5/') >= 0)
	{
		var icons = createIconList();

		$('#imapper-sortable-dummy>li:eq(1)').after('<li id="dummy-li-item-picture" style="position: relative;"><label for="dummy-imapper-item-picture" style="display: inline-block; margin-top: -12px;">Item Pin Image</label><input id="dummy-imapper-item-picture" name="dummy-imapper-item-picture" value="icon-cloud-download" type="hidden"><i id="dummy-imapper-pin-icon" class="fawesome icon-2x icon-cloud-download" style="width: 32px; height: 27px; border: 1px solid black; margin: 0 5px 0 45px;"></i><div class="icon-list-button"><a class="arrow-down-admin-link" href="#"><div class="arrow-down-admin" style=""></div></a></div>' + icons + '</li>');
		
		$('.imapper-item-icon-list').imCustomScrollbar();	
	}
	
	$('.imapper-sortable-real').each(function() {
		var selected = -1;
	
		$(this).find('option').each(function(index) {
			if ($(this).attr('selected') == 'selected')
				selected = index;
		});
		
		if (selected == -1)
			$(this).find('option').eq(0).attr('selected', 'selected');
			
		var id = $(this).attr('id').substring(17);
		numberOfTabs[id] = $(this).find('textarea').length;
	});
	
	$('.imapper-sortableItem').each( function(index) {
		if (parseInt($(this).attr('id').substring(4)) > numItems)
		{
			if (index == 0)
				first = parseInt($(this).attr('id').substring(4));
		
			numItems = parseInt($(this).attr('id').substring(4));
			var ind = numItems;
			
			var left = $('#sort' + numItems + '-imapper-item-x').attr('value');
			var top = $('#sort' + numItems + '-imapper-item-y').attr('value');
			
			var pinWrapper = createPin(numItems, left, top);		
			$('.mapper-sort-image').append(pinWrapper);
			
			$('#sort' + numItems + '-mapper-pin').css('top', -$('#sort' + numItems + '-mapper-pin').height() + 'px');
			$('#sort' + numItems + '-mapper-pin').css('left', -($('#sort' + numItems + '-mapper-pin').width()/2) + 'px');
			$('#sort' + numItems + '-mapper-pin-delete').css('top', -$('#sort' + numItems + '-mapper-pin').height() + 'px');
			$('#sort' + numItems + '-mapper-pin-delete').css('left', $('#sort' + numItems + '-mapper-pin').width()/2 - 15 + 'px');
			
			pinWrapper.draggable({
			 	containment: "parent",
				start: function() {
					itemClicked = itemIsClicked($(this).attr('id').substring(4, $(this).attr('id').indexOf('-')), itemClicked);
				},
				stop: function() {
					var coordX = $(this).offset().left;
					var coordY = $(this).offset().top;
					
					var mapCoord = $('#map-image').offset();
					var mapCoordX = mapCoord.left;
					var mapCoordY = mapCoord.top;
					
					var newPosX = (coordX - mapCoordX) / $(this).parent().width() * 100;
					var newPosY = (coordY - mapCoordY) / $(this).parent().height() * 100;
					
					$(this).css('left', newPosX + '%');
					$(this).css('top', newPosY + '%');
					
					$('#sort' + ind + '-imapper-item-x').attr('value', newPosX + '%');
					$('#sort' + ind + '-imapper-item-y').attr('value', newPosY + '%');
				}
			});
		}
	});
	
	$('.imapper-pin-text').each(function () {
		$(this).css('right', ($(this).width() / 2) + 'px');	
	});
	
	if (numItems > 0)
	{	
		$('#imapper-sortable-dummy').css('visibility', 'visible');
		
		itemClicked = itemIsClicked(first, itemClicked);
	}

	// COLORPICKER
	var colPickerOn = false,
		colPickerShow = false, 
		pluginUrl = $('#plugin-url').val(),
		timthumb = pluginUrl + 'timthumb/timthumb.php';
	// colorpicker field
	$('.cw-color-picker').each(function(){
		var $this = $(this),
			id = $this.attr('rel');
 
		$this.farbtastic('#' + id);
		$(document).on('click', $this, function(){
			$this.show();
		});
		$(document).on('click', '#' + id, function(){
			$('.cw-color-picker:visible').hide();
			$('#' + id + '-picker').show();
			colPickerOn = true;
			colPickerShow = true;
		});
		$(document).on('click', $this, function(){
			colPickerShow = true;	
		});
		
	});

	// IMAGE UPLOAD
	var thickboxId =  '',
		thickItem = false; 
	
	// backgorund images
	$('.cw-image-upload').click(function(e) {
		e.preventDefault();
		thickboxId = '#' + $(this).attr('id');
		formfield = $(thickboxId + '-input').attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});
	

	window.send_to_editor = function(html) {
		if(html.search('src="') != -1) {
			imgurl = html.substr(html.search('src="')+5);
			imgurl = imgurl.substr(0,imgurl.search('"'));
		}
		else
			imgurl = '';
		$(thickboxId + '-input').val(imgurl);
		if (thickItem) {
			thickItem = false;
			$(thickboxId).attr('src', imgurl);
			$(thickboxId).parent().find('input').attr('value', imgurl);
			$(thickboxId).parent().find('.imapper-pin-wrapper').each(function() {
				$(this).css('display', 'block');	
			});	
			
			if (thickboxId == '#item-icon')
				iconUploadBehavior();
		}
		else {
			$(thickboxId).css('background', 'url('+imgurl+') repeat');
		}
		tb_remove();
		
	}
	
	$(document).on('click', '.imapper-pin-delete', function() {
		var id = $(this).attr('id').substring(4, $(this).attr('id').indexOf('-'));
		$('#sort' + id).find('.imapper-delete').trigger('click');
	});
	
	$('.remove-image').click(function(e){
		e.preventDefault();
		$(this).parent().parent().find('input').val('');
		$(this).parent().parent().find('.cw-image-upload').css('background-image', 'url(' + pluginUrl + 'images/no_image.jpg)');
	});
	
	$(document).on('click', '.imapper-item-icon-list a', function(e) {
		e.preventDefault();
		
		$('#dummy-imapper-pin-icon').removeClass();
		$('#dummy-imapper-pin-icon').addClass($(this).find('i').attr('class'));
		$('#dummy-imapper-item-picture').attr('value', $(this).find('i').attr('class'));
		$('#dummy-imapper-pin-icon').addClass('icon-2x');
		$('#dummy-imapper-pin-icon').addClass('fawesome');
		
		$('.imapper-item-icon-list').css('display', 'none');
		itemIconListClicked = 0;
	});
	
	$(document).on('click', '.arrow-down-admin-link', function(e) {
		e.preventDefault();
		
		if (itemIconListClicked == 0)
		{
			$('.imapper-item-icon-list').css('display', 'block');
			$('.imapper-item-icon-list').imCustomScrollbar('update');
			itemIconListClicked = 1;
		}
		else
		{
			$('.imapper-item-icon-list').css('display', 'none');
			itemIconListClicked = 0;
		}
	});
	
	$(document).on('click', '#item-content-button-new', function() {
		numberOfTabs[itemClicked]++;
		
		$('#li-item-content').append('<textarea rows="6" style="margin-left: 128px; width: 230px; resize: none;" id="dummy-imapper-item-content-' + numberOfTabs[itemClicked] + '" class="textarea-additional" name="dummy-imapper-item-content-' + numberOfTabs[itemClicked] + '" value="" type="text" ></textarea>');
		
		$('#imapper-sortable-' + itemClicked).find('li').eq(3).append('<textarea rows="6" id="sort' + itemClicked + '-imapper-item-content-' + numberOfTabs[itemClicked] + '" class="textarea-additional" name="sort' + itemClicked + '-imapper-item-content-' + numberOfTabs[itemClicked] + '" value="" type="text" ></textarea>');
	});
	
	$(document).on('click', '#item-content-button-remove', function() {
		$('#dummy-imapper-item-content-' + numberOfTabs[itemClicked]).remove();
		$('#sort' + itemClicked + '-imapper-item-content-' + numberOfTabs[itemClicked]).remove();
		
		if (numberOfTabs[itemClicked] > 1)
			numberOfTabs[itemClicked]--;
	});
	
	$(document).on('click', '.imapper-sort-header', function(){
		itemClicked = itemIsClicked($(this).attr('id').substring(12, $(this).attr('id').substring(8).indexOf('-') + 8), itemClicked);
	});
	
	$(document).on('click', '.imapper-pin', function() {
		itemClicked = itemIsClicked($(this).attr('id').substring(4, $(this).attr('id').indexOf('-')), itemClicked);
	});
	
	$(document).on('input', '#dummy-imapper-item-title', function(e) {
		e.preventDefault();
		$('#imapper-sort' + itemClicked + '-header').find('small').find('i').find('span').html($('#dummy-imapper-item-title').attr('value'));
		
		$('#sort' + itemClicked + '-mapper-pin-text').html($('#dummy-imapper-item-title').attr('value'));
		$('#sort' + itemClicked + '-mapper-pin-text').css('right', ($('#sort' + itemClicked + '-mapper-pin-text').width() / 2 - 8) + 'px');
	});
	
	$('#map-image').click(function(e){
		e.preventDefault();
		
		var pluginUrl = $('#plugin-url').val();
		
		if ($('#map-image').attr('src') != pluginUrl + 'images/no_image.jpg')
		{
		
			numItems++;
			
			var mapCoord = $(this).offset();
			var mapCoordX = mapCoord.left;
			var mapCoordY = mapCoord.top;
			
			var clickCoordX = e.pageX;
			var clickCoordY = e.pageY;
			
			var posX = clickCoordX - mapCoordX;
			var posY = clickCoordY - mapCoordY;
			
			var posPercentX = posX / $(this).width() * 100;
			var posPercentY = posY / $(this).height() * 100;
			
			var pinWrapper = createPin(numItems, posPercentX + '%', posPercentY + '%');
			$(this).parent().append(pinWrapper);
			
			$('#sort' + numItems + '-mapper-pin').css('top', -$('#sort' + numItems + '-mapper-pin').height() + 'px');
			$('#sort' + numItems + '-mapper-pin').css('left', -($('#sort' + numItems + '-mapper-pin').width()/2) + 'px');
			$('#sort' + numItems + '-mapper-pin-delete').css('top', -$('#sort' + numItems + '-mapper-pin').height() + 'px');
			$('#sort' + numItems + '-mapper-pin-delete').css('left', $('#sort' + numItems + '-mapper-pin').width()/2 - 15 + 'px');
			
			if (numItems > 0)
			{
				$('#imapper-sortable-dummy').css('visibility', 'visible');
				var icon = $('#dummy-imapper-item-picture').val();
				var color = $('#dummy-imapper-item-pin-color').val();
			}
			else
			{
				var icon = 'icon-cloud-download';
				var color = '#0000ff';
			}
			
			var items_options = '<ul id="imapper-sortable-' + numItems + '" class="imapper-sortable-real" style="display:none;" >'
							+ '<li>'
								+ '<input type="hidden" id="sort' + numItems + '-imapper-item-x" name="sort' + numItems + '-imapper-item-x" value="' + posPercentX +'%" />'
								+ '<input type="hidden" id="sort' + numItems + '-imapper-item-y" name="sort' + numItems + '-imapper-item-y" value="' + posPercentY +'%" />'
							+ '</li>'
							+ '<li>'
								+ '<label style="margin-left:5px;" for="sort' + numItems + '-imapper-item-title">Item title</label>'
								+ '<input style="margin-left:5px;" id="sort' + numItems + '-imapper-item-title" name="sort' + numItems + '-imapper-item-title" value="" type="text" />'
							+ '</li>';
							
			if ($('#item-icon').attr('src').indexOf('images/icons/5/') >= 0)
				items_options	+=	  '<li>'
									+	'<input id="sort' + numItems + '-imapper-item-pin-color" name="sort' + numItems + '-imapper-item-pin-color" class="imapper-item-pin-color" value="' + color + '" type="text" style="">'
								+	  '</li>'
								+	  '<li>'
									+	'<input id="sort' + numItems + '-imapper-item-picture" name="sort' + numItems + '-imapper-item-picture" class="imapper-item-picture" value="' + icon + '" type="text">'
								+	  '</li>';				
							
			items_options +=  '<li>'
								+ '<label style="margin-left:5px;" for="sort' + numItems + '-imapper-item-open-position">Item Open Position</label>'
								+ '<select name="sort' + numItems + '-imapper-item-open-position" id="sort' + numItems + '-imapper-item-open-position">'
									+ '<option value="left">Left</option>'
									+ '<option value="right">Right</option>';
			
			if ($('#item-icon').attr('src').indexOf('images/icons/2/') < 0)
				items_options	+= 	  '<option value="top">Top</option>'
									+ '<option value="bottom">Bottom</option>';
									
				items_options  += '</select>'
							+ '</li>'
							+ '<div class="clear"></div>'
							+ '<li>'
								+ '<label style="margin-left:5px;" for="sort' + numItems + '-imapper-item-content">Item content</label>'
								+ '<input style="margin-left:5px;" id="sort' + numItems + '-imapper-item-content" name="sort' + numItems + '-imapper-item-content" value="" type="text" />'
							+ '</li>'
						+ '</ul>';
						
			$('.imapper_items_options').append(items_options);
			
			var itemss = '<li id="sort' + numItems + '" class="imapper-sortableItem">'
							+	'<div id="imapper-sort' + numItems + '-header" class="imapper-sort-header">Item ' + numItems + ' <small><i>- <span></span></i></small> &nbsp;</div><a href="#" class="imapper-delete">delete</a>'
							+ '</li>';
							
			$('#imapper-sortable-items').append(itemss);
			
			if ($('.imapper-sort-header').length > 0)
			{
				itemClicked = itemIsClicked(numItems, itemClicked);
			}
			
			numberOfTabs[numItems] = 1;
			
			pinWrapper.draggable({
			 	containment: "parent",
				start: function() {
					itemClicked = itemIsClicked($(this).attr('id').substring(4, $(this).attr('id').indexOf('-')), itemClicked);
				},
				stop: function() {
					var coordX = $(this).offset().left;
					var coordY = $(this).offset().top;
					
					var newPosX = (coordX - mapCoordX) / $(this).parent().width() * 100;
					var newPosY = (coordY - mapCoordY) / $(this).parent().height() * 100;
					
					$(this).css('left', newPosX + '%');
					$(this).css('top', newPosY + '%');
					
					$('#sort' + numItems + '-imapper-item-x').attr('value', newPosX + '%')
					$('#sort' + numItems + '-imapper-item-y').attr('value', newPosY + '%')
				}
			});
		
		}
	});
	
	// delete pin
	$(document).on('click', '.imapper-delete', function(e){
		e.preventDefault();
		
		$('#sort' + $(this).parent().attr('id').substring(4) + '-mapper-pin-wrapper').remove();
		$('#imapper-sortable-' + $(this).parent().attr('id').substring(4)).remove();
		$(this).parent().remove();
		
		if ($('.imapper-sortableItem').length == 0)
		{
			$('#dummy-imapper-item-title').attr('value', '');
			$('#dummy-imapper-item-content').attr('value', '');
			$('#imapper-sortable-dummy').css('visibility', 'hidden');
		}
		
		$('.imapper-sortableItem').each( function(index) {
			if (index == 0)
				first = parseInt($(this).attr('id').substring(4));
		});
		
		if (itemClicked == parseInt($(this).parent().attr('id').substring(4)))
			itemClicked = itemIsClicked(first, 0);
	});
	
	$(document).on('click', '.tsort-remove', function(e){
		e.preventDefault();
		$(this).parent().find('input').val('');
		$(this).parent().find('img').attr('src', pluginUrl + 'images/no_image.jpg');
		$(this).parent().find('img').attr('src', pluginUrl + 'images/no_image.jpg');
	});
	
	$('#map-image-remove').click(function() {
		$('.imapper-pin-wrapper').each(function() {
			$(this).css('display', 'none');	
		});
	});
	
	
	// map select
	$(document).on('click', '#map-change', function(e) {
		e.preventDefault();
		thickItem = true;
		thickboxId = '#' + $(this).parent().find('img').attr('id');
		formfield = $(thickboxId + '-input').attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});
	
	
	//icon select functions
	$(document).on('click', '#icon-change', function(e) {
		e.preventDefault();
		
		$('body').append('<div id="TB_overlay" class="TB_overlayBG"></div><div id="TB_window" style="margin-left: -315px; width: 630px; height: 220px; margin-top: -200px; visibility: visible;"><div id="TB_title"><div id="TB_ajaxWindowTitle">Select your icon</div><div id="TB_closeAjaxWindow"><a id="closeIconUpload" title="Close" href="#"><div class="tb-close-icon"></div></a></div></div><a id="ourIconsContent" href="#" class="icon-upload-tab" style="margin-top: 12px; border-width: 1px 1px 0 1px; border-style: solid; border-color: black;">Our icons</a><a id="customIconsContent" href="#" class="icon-upload-tab" style="margin: 11px 0 0 220px; border: 1px solid black;">Import your own icon</a><div class="clear"></div><div id="iconUploadContent" style="width:629px;height:150px;position:absolute;bottom:0px; border-top: 1px solid black;" name="TB_iframeContent427" hspace="0"></div><iframe id="TB_iframeContentIcon" frameborder="0" style="width:629px;height:412px;position:absolute;top:69px;border-top:1px solid black;padding-top:10px;display:none;" onload="tb_showIframe()" name="TB_iframeContent484" src="media-upload.php?type=image&" hspace="0"></iframe></div>');
		
		$('#iconUploadContent').append('<a class="iconImage" href="#"><img style="margin:40px;" src="' + pluginUrl + '/images/icons/4/1.png"></a>');
		$('#iconUploadContent').append('<a class="iconImage" href="#"><img style="margin:25px;" src="' + pluginUrl + '/images/icons/2/1.png"></a>');
		$('#iconUploadContent').append('<a class="iconImage" href="#"><img style="margin:25px;" src="' + pluginUrl + '/images/icons/3/1.png"></a>');
		$('#iconUploadContent').append('<a class="iconImage" href="#"><img style="margin:25px;" src="' + pluginUrl + '/images/icons/1/1.png"></a>');
		$('#iconUploadContent').append('<a class="iconImage" href="#"><img style="margin:25px;" src="' + pluginUrl + '/images/icons/5/purple.png"></a>');
	});
	
	$(document).on('click', '#customIconsContent', function(e) {
		e.preventDefault();
		
		$('#iconUploadContent').css('display', 'none');
		$('#TB_iframeContentIcon').css('display', 'block');
		
		/*$("#TB_iframeContentIcon").load(function(){
		$('#TB_iframeContentIcon').contents().find('.current').trigger('click');
		});*/
		
		$('#customIconsContent').css('border-bottom', '0px');
		$('#customIconsContent').css('margin-top', '12px');
		
		$('#ourIconsContent').css('border-bottom', '1px solid black');
		$('#ourIconsContent').css('margin-top', '11px');
		
		thickItem = true;
		thickboxId = '#' + $('#item-icon').attr('id');
		formfield = $(thickboxId + '-input').attr('name');
	});
	
	$(document).on('click', '#ourIconsContent', function(e) {
		e.preventDefault();
		
		$('#TB_iframeContentIcon').css('display', 'none');
		$('#iconUploadContent').css('display', 'block');
		
		$('#ourIconsContent').css('border-bottom', '0px');
		$('#ourIconsContent').css('margin-top', '12px');
		
		$('#customIconsContent').css('border-bottom', '1px solid black');
		$('#customIconsContent').css('margin-top', '11px');
	});
	
	$(document).on('click', '.iconImage', function(e) {
		e.preventDefault();	
		
		$('#item-icon').attr('src', $(this).find('img').attr('src'));
		if ($('#item-icon').attr('src').indexOf('images/icons/5/') < 0)
			$('#item-icon-input').attr('value', $(this).find('img').attr('src'));
		else
			$('#item-icon-input').attr('value', $(this).find('img').attr('src').substring(0, $(this).find('img').attr('src').indexOf('images/icons/5/')) + 'images/icons/5/1.png');
		
		iconUploadBehavior();
		
		$('#TB_overlay').remove();
		$('#TB_window').remove();	
	});
	
	$(document).on('click', '#closeIconUpload', function(e) {
		$('#TB_overlay').remove();
		$('#TB_window').remove();	
	});
	
	// item images
	$(document).on('click', '.tsort-start-item', function(e) {
		$('.tsort-start-item').attr('checked', false);
		$(this).attr('checked', 'checked');
	});
	
	// ----------------------------------------
	
	// AJAX subbmit
	$('#save-timeline').click(function(e){
		e.preventDefault();
		
		$('#sort' + itemClicked + '-imapper-item-title').attr('value', $('#dummy-imapper-item-title').attr('value'));
		$('#sort' + itemClicked + '-imapper-item-content').attr('value', $('#dummy-imapper-item-content').attr('value'));
		$('#sort' + itemClicked + '-imapper-item-open-position').find('option').eq($('#dummy-imapper-item-open-position').prop('selectedIndex')).attr('selected', 'selected');
		$('#sort' + itemClicked + '-imapper-item-pin-color').attr('value', $('#dummy-imapper-item-pin-color').attr('value'));
		$('#sort' + itemClicked + '-imapper-item-picture').attr('value', $('#dummy-imapper-item-picture').attr('value'));
		
		for (var i = 2; i <= numberOfTabs[itemClicked]; i++)
			$('#sort' + itemClicked + '-imapper-item-content-' + i).attr('value', $('#dummy-imapper-item-content-' + i).attr('value'));
		
		$('#save-loader').show();
		$.ajax({
			type:'POST', 
			url: 'admin-ajax.php', 
			data:'action=mapper_save&' + $('#post_form').serialize(), 
			success: function(response) {
				$('#image_mapper_id').val(response);
				$('#save-loader').hide();
			}
		});
	});
	
	$('#preview-timeline').click(function(e){
		e.preventDefault();
		
		itemClicked = itemIsClicked(itemClicked, itemClicked);
		
		var id = ($('#image_mapper_id').attr('value') != '') ? $('#image_mapper_id').attr('value') : '1';
		var font = $('#item-font-type').val();
		font = font.replace('+', ' ');
		
		$('body').append('<div id="TB_overlay" class="TB_overlayBG"></div><div id="TB_window" style="width: 960px; height: 500px; margin-top: -250px; visibility: visible; margin-left: -480px;"><div id="TB_title"><div id="TB_ajaxWindowTitle">Preview</div><div id="TB_closeAjaxWindow"><a id="TBct_closeWindowButton" title="Close" href="#"><div class="tb-close-icon"></div></a></div></div></div>');
		
		var frontHtml = '';
		
		if ($('#map-image').attr('src').indexOf('images/no_image.jpg') < 0)
		{
		
		if ($('#item-font-type').val() != 'def')
			frontHtml += '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' + $('#item-font-type').val() + '">';

		frontHtml += '<div id="imagemapper' + id + '-wrapper" style="visibility: hidden; position:relative; margin: 0 auto;">'
		+	'<div id="imapper' + id + '-values" style="display: none">'
		+		'<div id="imapper' + id + '-value-item-open-style">' + $('#item-open-style').val() + '</div>'
		+	'</div>'
		+	'<img id="imapper' + id + '-map-image" style="max-width: 100%; max-height: 473px;" src="' + $('#map-image').attr('src') + '" />';
	

		$('.imapper-sortable-real').each(function() {
			var num = $(this).attr('id').substring(17);
	 		var count = 2;

			frontHtml += '<div id="imapper' + id + '-pin' + num + '-wrapper" class="imapper' + id + '-pin-wrapper imapper-pin-wrapper" style="position: absolute; left: ' + $('#sort' + num + '-imapper-item-x').attr('value') + '; top: ' + $('#sort' + num + '-imapper-item-y').attr('value') + ';" >'
								+ '<img id="imapper' + id + '-pin' + num + '" class="imapper' + id + '-pin" src="' + $('#item-icon-input').attr('value') + '">';
				
			if ($('#item-icon').attr('src').indexOf('images/icons/5/') >= 0)				
				frontHtml +=		'<div id="imapper' + id + '-pin' + num + '-color" class="imapper-pin-color" style="background-color: ' + $(this).find('.imapper-item-pin-color').attr('value') + ';"></div>'
									+ '<i id="imapper' + id + '-pin' + num + '-icon" class="imapper-pin-icon fawesome icon-large ' + $(this).find('.imapper-item-picture').attr('value') + '"></i>';
									
			if ($('#item-icon').attr('src').indexOf('images/icons/3/') >= 0)
				frontHtml +=		'<img id="imapper' + id + '-pin' + num + '" class="imapper-pin-shadow" src="' + $('#item-icon-input').attr('value').substring(0, $('#item-icon-input').attr('value').indexOf('/icons/3/') + 9) + '1-1.png'  + '">';
				
				frontHtml +=		'<div id="imapper' + id + '-pin' + num + '-content-wrapper" class="imapper' + id + '-pin-content-wrapper imapper-content-wrapper" style="color: ' + $('#item-font-color').attr('value') + ';">'
									+	'<div id="imapper' + id + '-pin' + num + '-content" class="imapper-content" style="background-color: ' + $('#item-back-color').attr('value') + '; border-color: ' + $('#item-border-color').attr('value') + '; border-radius: ' + $('#item-border-radius').attr('value') + 'px; width: ' + $('#item-width').attr('value') + 'px; height: ' + $('#item-height').attr('value') + 'px; font-family: &quot;' + font + '&quot;; font-size: ' + $('#item-font-size').attr('value') + 'px;"><p class="imapper-content-header" style="font-size: ' + $('#item-header-font-size').attr('value') + 'px !important;">' + $('#sort' + num + '-imapper-item-title').attr('value') + '</p><div class="imapper-content-text">' + $('#sort' + num + '-imapper-item-content').attr('value') + '</div></div>';
				
						
			if ($(this).find('.textarea-additional').length > 0)
			{
				frontHtml += 			'<div id="imapper' + id + '-pin' + num + '-content-tab" class="imapper-content-tab" style="background-color: ' + $('#item-back-color').attr('value') + ';"><a href="#">1</a></div>';
				
				$(this).find('.textarea-additional').each(function() {
					frontHtml += 		'<div id="imapper' + id + '-pin' + num + '-content-' + count + '" class="imapper-content imapper-content-additional" style="background-color: ' + $('#item-back-color').attr('value') + '; border-color: ' + $('#item-border-color').attr('value') + '; border-radius: ' + $('#item-border-radius').attr('value') + 'px; width: ' + $('#item-width').attr('value') + 'px; height: ' + $('#item-height').attr('value') + 'px; font-family: &quot;' + font + '&quot;; font-size: ' + $('#item-font-size').attr('value') + 'px;"><div class="imapper-content-header" style="font-size: ' + $('#item-header-font-size').attr('value') + 'px !important;">' + $('#sort' + num + '-imapper-item-title').attr('value') + '</div><div class="imapper-content-text">' + $(this).attr('value') + '</div></div>';
					frontHtml +=		'<div id="imapper' + id + '-pin' + num + '-content-' + count + '-tab" class="imapper-content-tab" style="background-color: ' + $('#item-back-color').attr('value') + ';"><a href="#">' + count + '</a></div>';
					count++;
				});
				
			}
			
			frontHtml +=			'<div class="imapper-arrow" style="border-color: ' + $('#item-back-color').attr('value') + ' transparent transparent transparent;"></div>';
			
			if ($('#item-icon').attr('src').indexOf('images/icons/2/') < 0)
				frontHtml +=		'<div class="arrow-' + $('#sort' + num + '-imapper-item-open-position').val() + '-border"></div>';
			else
				frontHtml +=		'<div class="triangle-' + $('#sort' + num + '-imapper-item-open-position').val() + '-border"></div>';
			
			frontHtml +=			'</div><div id="imapper' + id + '-value-item' + num + '-open-position" class="imapper' + id + '-value-item-open-position" style="display:none;">' + $('#sort' + num + '-imapper-item-open-position').val() + '</div>';
				
			var tabStyle;				
			if (count > 2)
				tabStyle = 'display: block; width: 16px; height: 16px; border-radius: 8px; border: 1px solid #191970; background-color: #4f92d3; color: white; font-size: 10px; line-height: 1.4; text-align: center; position: absolute; top: -50px; left: 10px; z-index: 10;';
			else
				tabStyle = 'display: none;';
								
			frontHtml +=		'<div id="imapper' + id + '-value-item' + num + '-tab-number" class="imapper-value-tab-number" style="' + tabStyle + '">' + (count - 1) + '</div>'
							+	'<div id="imapper' + id + '-value-item' + num + '-border-color" class="imapper-value-border-color" style="display: none;">' + $('#item-border-color').attr('value') + '</div>'
						  + '</div>';
		});

		frontHtml += '</div>';

		$('#TB_window').append(frontHtml);
		$('#TB_window').append('<script type="text/javascript" src="' + url + 'js/preview.js"></script>');
		$('#TB_window').find('#imagemapper' + id + '-wrapper').css('width', $('#imapper' + id + '-map-image').width());
		$('#TB_window').find('#imagemapper' + id + '-wrapper').css('visibility', 'visible');
		
		}
			
		$('#TBct_closeWindowButton').click(function(ev){
			ev.preventDefault();
			$('#TB_overlay').remove();
			$('#TB_window').remove();
		});
	});
	
	
});

function itemIsClicked(clicked, prevClicked) {
	if (prevClicked > 0)
	{
		$('#sort' + prevClicked + '-imapper-item-title').attr('value', $('#dummy-imapper-item-title').attr('value'));
		$('#sort' + prevClicked + '-imapper-item-content').attr('value', $('#dummy-imapper-item-content').attr('value'));
		$('#sort' + prevClicked + '-imapper-item-content').html($('#dummy-imapper-item-content').attr('value'));
		
		$('#sort' + prevClicked + '-imapper-item-pin-color').attr('value', $('#dummy-imapper-item-pin-color').attr('value'));
		$('#sort' + prevClicked + '-imapper-item-picture').attr('value', $('#dummy-imapper-item-picture').attr('value'));
		
		$('#sort' + prevClicked + '-mapper-pin-delete').css('display', 'none');
		
		$('#sort' + prevClicked + '-imapper-item-open-position').find('option').each(function(){
			$(this).removeAttr('selected');	
		});
		
		var selected = 0;
		$('#dummy-imapper-item-open-position').find('option').each(function(index) {
			if ($(this).attr('selected') == 'selected')
				selected = index;
		});
		
		$('#sort' + prevClicked + '-imapper-item-open-position').find('option').eq(selected).attr('selected', 'selected');
		
		for (var i = 2; i <= numberOfTabs[prevClicked]; i++)
		{
			$('#sort' + prevClicked + '-imapper-item-content-' + i).attr('value', $('#dummy-imapper-item-content-' + i).attr('value'));
			$('#sort' + prevClicked + '-imapper-item-content-' + i).html($('#dummy-imapper-item-content-' + i).attr('value'));
		}
	}
		
	$('.imapper-sort-header').each( function() {
		$(this).removeAttr('style');
	});
	
	$('#sort' + prevClicked + '-mapper-pin').css('border', 'none');
	$('#sort' + clicked + '-mapper-pin').css('border', '1px dashed #ffffff');
	
	$('#imapper-sort' + clicked + '-header').css('background-image', 'none');
	$('#imapper-sort' + clicked + '-header').css('background-color', 'rgb(200, 200, 200)');
	
	
	$('#sort' + clicked + '-mapper-pin-delete').css('display', 'block');
		
	$('#dummy-imapper-item-title').attr('value', $('#sort' + clicked + '-imapper-item-title').attr('value'));
	$('#dummy-imapper-item-content').attr('value', $('#sort' + clicked + '-imapper-item-content').attr('value'));
	$('#dummy-imapper-item-content').html($('#sort' + clicked + '-imapper-item-content').html());
	
	$('#dummy-imapper-item-pin-color').attr('value', $('#sort' + clicked + '-imapper-item-pin-color').attr('value'));
	$('#dummy-imapper-item-pin-color').css('background-color', $('#sort' + clicked + '-imapper-item-pin-color').attr('value'));
	$('#dummy-imapper-item-picture').attr('value', $('#sort' + clicked + '-imapper-item-picture').attr('value'));
	$('#dummy-imapper-pin-icon').removeClass();
	$('#dummy-imapper-pin-icon').addClass('fawesome');
	$('#dummy-imapper-pin-icon').addClass('icon-2x');
	$('#dummy-imapper-pin-icon').addClass($('#dummy-imapper-item-picture').attr('value'));
	
	$('#dummy-imapper-item-open-position').find('option').each(function(){
		$(this).removeAttr('selected');	
	});
	
	var selected2 = 0;
	$('#sort' + clicked + '-imapper-item-open-position').find('option').each(function(index) {
		if ($(this).attr('selected') == 'selected')
			selected2 = index;
	});
	$('#dummy-imapper-item-open-position').find('option').eq(selected2).attr('selected', 'selected');
	
	for (var i = 2; i <= numberOfTabs[prevClicked]; i++)
		$('#dummy-imapper-item-content-' + i).remove();		
	for (var i = 2; i <= numberOfTabs[clicked]; i++)
		$('#li-item-content').append('<textarea class="textarea-additional" rows="6" style="margin-left: 125px; width: 230px; resize: none;" id="dummy-imapper-item-content-' + i + '" name="dummy-imapper-item-content-' + i + '" value="' + $('#sort' + clicked + '-imapper-item-content-' + i).attr('value') + '" type="text" >' + $('#sort' + clicked + '-imapper-item-content-' + i).html() + '</textarea>');

	return clicked;
}

function iconUploadBehavior() {
	
		$('.imapper-pin').each(function() {
			$(this).attr('src', $('#item-icon').attr('src'));
			$(this).css('top', -$(this).height() + 'px');
			$(this).css('left', -($(this).width()/2) + 'px');
		});
	
		$('#item-font-size').removeAttr('readonly');
		$('#item-header-font-size').removeAttr('readonly');
		$('#item-height').removeAttr('readonly');
			
		if ($('#dummy-imapper-item-open-position').find('option').length == 2)
		{
			$('#dummy-imapper-item-open-position').append('<option value="top">Top</option>');
			$('#dummy-imapper-item-open-position').append('<option value="bottom">Bottom</option>');
		}
			
		$('.imapper-sortable-real').each(function() {
			if ($(this).find('option').length == 2)
			{
				$(this).find('select').append('<option value="top">Top</option>');
				$(this).find('select').append('<option value="bottom">Bottom</option>');
			}
			$(this).find('.imapper-item-pin-color').parent().remove();
			$(this).find('.imapper-item-picture').parent().remove();
		});
		
		$('#item-content-button-new').remove();
		$('#item-content-button-remove').remove();
		$('#dummy-li-item-pin-color').remove();
		$('#dummy-li-item-picture').remove();	
		
		if ($('#item-icon').attr('src').indexOf('images/icons/1/') < 0)
		{	
			$('.textarea-additional').each(function() {
				$(this).remove();	
			});
			
			$('.imapper-sortable-real').each(function() {
				numberOfTabs[$(this).attr('id').substring(17)] = 1;
			});
		}
		
		if ($('#item-icon').attr('src').indexOf('images/icons/2/') >= 0)
		{
			$('#item-font-size').html('12');
			$('#item-font-size').attr('value', '12');
			$('#item-font-size').attr('readonly', 'readonly');
			$('#item-header-font-size').html('12');
			$('#item-header-font-size').attr('value', '12');
			$('#item-header-font-size').attr('readonly', 'readonly');
			$('#item-height').html('75');
			$('#item-height').attr('value', '75');
			$('#item-height').attr('readonly', 'readonly');
			
			$('#dummy-imapper-item-open-position').find('option').each(function() {
				if ($(this).attr('value') == 'top' || $(this).attr('value') == 'bottom')
					$(this).remove();	
			});
			
			$('.imapper-sortable-real').each(function() {
				$(this).find('option').each(function() {
					$(this).removeAttr('selected');	
				});
				
				$(this).find('option').each(function() {
					if ($(this).attr('value') == 'left')
						$(this).attr('selected', 'selected');
					
					if ($(this).attr('value') == 'top' || $(this).attr('value') == 'bottom')
						$(this).remove();
				});
			});
		}
		
		else if ($('#item-icon').attr('src').indexOf('images/icons/1/') >= 0)
			$('#imapper-sortable-dummy').append('<li><input type="button" value="+ Add new tab" id="item-content-button-new" style="margin-left: 130px;" /><input type="button" value="- Remove last tab" id="item-content-button-remove" style="margin-left: 15px;" /></li>');
			
		else if ($('#item-icon').attr('src').indexOf('images/icons/5/') >= 0)
		{
			$('.imapper-sortable-real').each(function() {
				var sortId = $(this).attr('id').substring(17);
				
				$(this).append('<li><input id="sort' + sortId + '-imapper-item-pin-color" name="sort' + sortId + '-imapper-item-pin-color" class="imapper-item-pin-color" value="#0000ff" type="text" style=""></li><li><input id="sort' + sortId + '-imapper-item-picture" name="sort' + sortId + '-imapper-item-picture" class="imapper-item-picture" value="icon-cloud-download" type="text"></li>');
			});
			
			var icons = createIconList();
			
			$('#imapper-sortable-dummy>li:eq(1)').after('<li id="dummy-li-item-pin-color"><label for="dummy-imapper-item-pin-color">Item Pin Color</label><input id="dummy-imapper-item-pin-color" class="color-picker-iris" name="dummy-imapper-item-pin-color"  value="#0000ff" type="text" style="margin-left: 50px; background:#0000ff; color:#ffffff;"><div class="color-picker-iris-holder"></div></li><li id="dummy-li-item-picture" style="position: relative;"><label for="dummy-imapper-item-picture" style="display: inline-block; margin-top: -12px;">Item Pin Image</label><input id="dummy-imapper-item-picture" name="dummy-imapper-item-picture" value="icon-cloud-download" type="hidden"><i id="dummy-imapper-pin-icon" class="fawesome icon-2x icon-cloud-download" style="width: 32px; height: 27px; border: 1px solid black; margin: 0 5px 0 45px;"></i><div class="icon-list-button"><a class="arrow-down-admin-link" href="#"><div class="arrow-down-admin" style=""></div></a></div>' + icons + '</li>');
		
			$('.imapper-item-icon-list').imCustomScrollbar();
			
			$('#imapper-sortable-dummy').find('.color-picker-iris').each(function()
			{
				$(this).css('background', $(this).val());
	            $(this).iris({
					height: 145,
	                target:$(this).parent().find('.color-picker-iris-holder'),
					change: function(event, ui) {
	                    $(this).val(ui.color.toString());
	                    $(this).css( 'background-color', ui.color.toString());
	                }
	            });
			});
		}
}

function createPin(numItems, left, top) {
	var pinWrapper = $(document.createElement('div'));
	pinWrapper.attr('id', 'sort' + numItems + '-mapper-pin-wrapper');
	pinWrapper.attr('class', 'imapper-pin-wrapper');
	pinWrapper.css('position', 'absolute');
	pinWrapper.css('left', left);
	pinWrapper.css('top', top);
			
	var pin = $(document.createElement('img'));
	pin.attr('id', 'sort' + numItems + '-mapper-pin');
	pin.attr('class', 'imapper-pin');
	pin.attr('src', $('#item-icon').attr('src'));
	pin.css('position', 'absolute');
			
	var pinText = $(document.createElement('div'));
	pinText.attr('id', 'sort' + numItems + '-mapper-pin-text');
	pinText.attr('class', 'imapper-pin-text');
	pinText.css('position', 'relative');
	pinText.css('color', '#000000');
	pinText.css('top', '5px');
	pinText.html($('#sort' + numItems + '-imapper-item-title').attr('value'));
	
	var pinDelete = $(document.createElement('img'));
	pinDelete.attr('id', 'sort' + numItems + '-mapper-pin-delete');
	pinDelete.attr('class', 'imapper-pin-delete');
	pinDelete.attr('src', $('#plugin-url').val() + 'images/tb-close.png');
	pinDelete.css('position', 'absolute');
	pinDelete.css('cursor', 'pointer');
	pinDelete.css('display', 'none');
			
	pinWrapper.append(pin);
	pinWrapper.append(pinText);
	pinWrapper.append(pinDelete);
	
	return pinWrapper;
}

function createIconList()
{
	var iconList = ['icon-cloud-download', 'icon-cloud-upload', 'icon-lightbulb', 'icon-exchange', 'icon-bell-alt', 'icon-file-alt', 'icon-beer', 'icon-coffee', 'icon-food', 'icon-fighter-jet', 'icon-user-md', 'icon-stethoscope', 'icon-suitcase', 'icon-building', 'icon-hospital', 'icon-ambulance', 'icon-medkit', 'icon-h-sign', 'icon-plus-sign-alt', 'icon-spinner', 'icon-angle-left', 'icon-angle-right', 'icon-angle-up', 'icon-angle-down', 'icon-double-angle-left', 'icon-double-angle-right', 'icon-double-angle-up', 'icon-double-angle-down', 'icon-circle-blank', 'icon-circle', 'icon-desktop', 'icon-laptop', 'icon-tablet', 'icon-mobile-phone', 'icon-quote-left', 'icon-quote-right', 'icon-reply', 'icon-github-alt', 'icon-folder-close-alt', 'icon-folder-open-alt', 'icon-adjust', 'icon-asterisk', 'icon-ban-circle', 'icon-bar-chart', 'icon-barcode', 'icon-beaker', 'icon-beer', 'icon-bell', 'icon-bell-alt', 'icon-bolt', 'icon-book', 'icon-bookmark', 'icon-bookmark-empty', 'icon-briefcase', 'icon-bullhorn', 'icon-calendar', 'icon-camera', 'icon-camera-retro', 'icon-certificate', 'icon-check', 'icon-check-empty', 'icon-circle', 'icon-circle-blank', 'icon-cloud', 'icon-cloud-download', 'icon-cloud-upload', 'icon-coffee', 'icon-cog', 'icon-cogs', 'icon-comment', 'icon-comment-alt', 'icon-comments', 'icon-comments-alt', 'icon-credit-card', 'icon-dashboard', 'icon-desktop', 'icon-download', 'icon-download-alt', 'icon-edit', 'icon-envelope', 'icon-envelope-alt', 'icon-exchange', 'icon-exclamation-sign', 'icon-external-link', 'icon-eye-close', 'icon-eye-open', 'icon-facetime-video', 'icon-fighter-jet', 'icon-film', 'icon-filter', 'icon-fire', 'icon-flag', 'icon-folder-close', 'icon-folder-open', 'icon-folder-close-alt', 'icon-folder-open-alt', 'icon-food', 'icon-gift', 'icon-glass', 'icon-globe', 'icon-group', 'icon-hdd', 'icon-headphones', 'icon-heart', 'icon-heart-empty', 'icon-home', 'icon-inbox', 'icon-info-sign', 'icon-key', 'icon-leaf', 'icon-laptop', 'icon-legal', 'icon-lemon', 'icon-lightbulb', 'icon-lock', 'icon-unlock', 'icon-magic', 'icon-magnet', 'icon-map-marker', 'icon-minus', 'icon-minus-sign', 'icon-mobile-phone', 'icon-money', 'icon-move', 'icon-music', 'icon-off', 'icon-ok', 'icon-ok-circle', 'icon-ok-sign', 'icon-pencil', 'icon-picture', 'icon-plane', 'icon-plus', 'icon-plus-sign', 'icon-print', 'icon-pushpin', 'icon-qrcode', 'icon-question-sign', 'icon-quote-left', 'icon-quote-right', 'icon-random', 'icon-refresh', 'icon-remove', 'icon-remove-circle', 'icon-remove-sign', 'icon-reorder', 'icon-reply', 'icon-resize-horizontal', 'icon-resize-vertical', 'icon-retweet', 'icon-road', 'icon-rss', 'icon-screenshot', 'icon-search', 'icon-share', 'icon-share-alt', 'icon-shopping-cart', 'icon-signal', 'icon-signin', 'icon-signout', 'icon-sitemap', 'icon-sort', 'icon-sort-down', 'icon-sort-up', 'icon-spinner', 'icon-star', 'icon-star-empty', 'icon-star-half', 'icon-tablet', 'icon-tag', 'icon-tags', 'icon-tasks', 'icon-thumbs-down', 'icon-thumbs-up', 'icon-time', 'icon-tint', 'icon-trash', 'icon-trophy', 'icon-truck', 'icon-umbrella', 'icon-upload', 'icon-upload-alt', 'icon-user', 'icon-user-md', 'icon-volume-off', 'icon-volume-down', 'icon-volume-up', 'icon-warning-sign', 'icon-wrench', 'icon-zoom-in', 'icon-zoom-out', 'icon-file', 'icon-file-alt', 'icon-cut', 'icon-copy', 'icon-paste', 'icon-save', 'icon-undo', 'icon-repeat', 'icon-text-height', 'icon-text-width', 'icon-align-left', 'icon-align-center', 'icon-align-right', 'icon-align-justify', 'icon-indent-left', 'icon-indent-right', 'icon-font', 'icon-bold', 'icon-italic', 'icon-strikethrough', 'icon-underline', 'icon-link', 'icon-paper-clip', 'icon-columns', 'icon-table', 'icon-th-large', 'icon-th', 'icon-th-list', 'icon-list', 'icon-list-ol', 'icon-list-ul', 'icon-list-alt', 'icon-angle-left', 'icon-angle-right', 'icon-angle-up', 'icon-angle-down', 'icon-arrow-down', 'icon-arrow-left', 'icon-arrow-right', 'icon-arrow-up', 'icon-caret-down', 'icon-caret-left', 'icon-caret-right', 'icon-caret-up', 'icon-chevron-down', 'icon-chevron-left', 'icon-chevron-right', 'icon-chevron-up', 'icon-circle-arrow-down', 'icon-circle-arrow-left', 'icon-circle-arrow-right', 'icon-circle-arrow-up', 'icon-double-angle-left', 'icon-double-angle-right', 'icon-double-angle-up', 'icon-double-angle-down', 'icon-hand-down', 'icon-hand-left', 'icon-hand-right', 'icon-hand-up', 'icon-circle', 'icon-circle-blank', 'icon-play-circle', 'icon-play', 'icon-pause', 'icon-stop', 'icon-step-backward', 'icon-fast-backward', 'icon-backward', 'icon-forward', 'icon-fast-forward', 'icon-step-forward', 'icon-eject', 'icon-fullscreen', 'icon-resize-full', 'icon-resize-small', 'icon-phone', 'icon-phone-sign', 'icon-facebook', 'icon-facebook-sign', 'icon-twitter', 'icon-twitter-sign', 'icon-github', 'icon-github-alt', 'icon-github-sign', 'icon-linkedin', 'icon-linkedin-sign', 'icon-pinterest', 'icon-pinterest-sign', 'icon-google-plus', 'icon-google-plus-sign', 'icon-sign-blank', 'icon-ambulance', 'icon-beaker', 'icon-h-sign', 'icon-hospital', 'icon-medkit', 'icon-plus-sign-alt', 'icon-stethoscope', 'icon-user-md' ];
		
	var iconDiv = '<div class="imapper-item-icon-list">';
	
	for (var i = 0; i < iconList.length; i++)
		if ((i + 1) % 10 != 0)
			iconDiv += '<a href="#"><i class="' + iconList[i] + ' fawesome" style="margin: 10px 0 0 10px;"></i></a>';
		else
			iconDiv += '<a href="#"><i class="' + iconList[i] + ' fawesome" style="margin: 10px 10px 0 10px;"></i></a><div class="clear"></div>';
	
	iconDiv += '</div>';
	
	return iconDiv;
}

})(jQuery)

