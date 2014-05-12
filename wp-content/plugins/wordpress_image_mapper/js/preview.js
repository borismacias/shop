/*

Wordpress Image Mapper

Pin mapper for custom images.

Copyright (c) 2013 Br0 (shindiristudio.com)

Project site: http://codecanyon.net/
Project demo: http://shindiristudio.com/timeline

*/

(function($){

	$(document).ready(function() {
		$("div[id*='imagemapper']").each( function() {
				
			var id = $(this).attr('id').substring(11, $(this).attr('id').indexOf('-'));
			var openStyle = $('#imapper' + id + '-value-item-open-style').html();
			var width = $(this).find('.imapper-content').width();
			var height = $(this).find('.imapper-content').height();
			var map_width = $(this).find('#imapper' + id + '-map-image').width();
			var map_height = $(this).find('#imapper' + id + '-map-image').height();
			var clicked = new Array();
			var tab_clicked = new Array();
			
			$(this).css('width', map_width);
			$(this).css('height', map_height);
			
			$('.imapper' + id + '-pin').each( function() {
				clicked[$(this).attr('id').substring($(this).attr('id').indexOf('-pin') + 4)] = 0;
				tab_clicked[$(this).attr('id').substring($(this).attr('id').indexOf('-pin') + 4)] = 1;
				
				if ($(this).attr('src').indexOf('images/icons/4/') >= 0)
					$(this).addClass('pin-mini-style');
				else
					$(this).addClass('pin-style');
					
				if ($(this).attr('src').indexOf('images/icons/2/') >= 0 || $(this).attr('src').indexOf('images/icons/1/') >= 0)
					$(this).parent().find('.imapper-content').wrapInner('<div class="imapper-content-inner" style="width: ' + width + 'px; height: ' + height + 'px;" />');			
				
				if ($(this).attr('src').indexOf('images/icons/5/') >= 0)
				{
					$(this).parent().find('.imapper-pin-icon').css('left', -$(this).parent().find('.imapper-pin-icon').width() / 2 - 1 + 'px');
					/*if (window.PIE)
						$(this).parent().find('.imapper-pin-color').each(function() {
							PIE.attach(this);
						});*/
				}
					
				if ($(this).attr('src').indexOf('/images/icons/1/') < 0 && $(this).attr('src').indexOf('/images/icons/2/') < 0 && $(this).attr('src').indexOf('/images/icons/3/') < 0 && $(this).attr('src').indexOf('/images/icons/4/') < 0 && $(this).attr('src').indexOf('/images/icons/5/') < 0)
				{
					$(this).css('top', -$(this).height() + 'px');
					$(this).css('left', -$(this).width()/2 + 'px');
				}
			});
			
			$('.imapper' + id + '-pin').mouseenter(function() {
				if ($(this).attr('src').indexOf('images/icons/1/') >= 0)
				{
					var position = $(this).attr('src').indexOf('/images/');
					var pluginUrl = $(this).attr('src').substring(0, position);
					$(this).attr('src', pluginUrl + '/images/icons/1/1-1.png');
				}
				else if ($(this).attr('src').indexOf('images/icons/3/') >= 0)
				{
					$(this).animate({
						marginTop: -10,
						'padding-bottom': 10
					},
					{
						duration: 200,
						queue: false
					});
					
					$(this).parent().find('.imapper-pin-shadow').animate({
						marginTop: -80,
						marginLeft: -46
					},
					{
						duration: 200,
						queue: false
					});
				}
			});
				
			$('.imapper' + id + '-pin').mouseleave(function() {
				if ($(this).attr('src').indexOf('images/icons/1/') >= 0)
				{
					var position = $(this).attr('src').indexOf('/images/');
					var pluginUrl = $(this).attr('src').substring(0, position);
					$(this).attr('src', pluginUrl + '/images/icons/1/1.png');
				}
				else if ($(this).attr('src').indexOf('images/icons/3/') >= 0)
				{
					$(this).animate({
						marginTop: 0,
						'padding-bottom': 0
					},
					{
						duration: 200,
						queue: false
					});
					
					$(this).parent().find('.imapper-pin-shadow').animate({
						marginTop: -75,
						marginLeft: -41
					},
					{
						duration: 200,
						queue: false
					});
				}
			});
			
			$('.imapper' + id + '-pin-content-wrapper').each(function() {
				var position = $(this).parent().find('.imapper' + id + '-value-item-open-position').html();
				var img_width = $('#item-icon').width();
				var img_height = $('#item-icon').height();
				var borderColor = $(this).parent().find('.imapper-value-border-color').html();
			
				if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/2/') < 0)
				{
					if (position == 'top')
					{	
						$(this).find('.imapper-content').css('position', 'absolute');
						$(this).find('.imapper-content').css('right', '0px');
						
						$(this).find('.arrow-top-border').css('top', height + 'px');
						$(this).find('.arrow-top-border').css('left', width/2 - 11 + 'px');
						$(this).find('.arrow-top-border').css('border-top-color', borderColor);
					
						$(this).css('width', width + 'px');
						$(this).css('height', height + img_height/4 + 35 + 'px');
						$(this).css('right', width/2 + 'px');
						$(this).css('bottom', height + img_height + 40 + 'px');
						
						if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/4/') >= 0)
						{
							$(this).css('right', width/2 - 4 + 'px');
							$(this).css('bottom', height + 50 + 'px');
						}
						else if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/5/') >= 0)
							$(this).css('bottom', height + img_height + 20 + 'px');
							
						$(this).find('.imapper-arrow').addClass('arrow-down');
						$(this).find('.imapper-arrow').css('top', height + 'px');
					}
					else if (position == 'bottom')
					{
						$(this).find('.imapper-content').css('position', 'absolute');
						$(this).find('.imapper-content').css('bottom', '0px');
						$(this).find('.imapper-content').css('right', '0px');
						
						$(this).find('.arrow-bottom-border').css('top', img_height + 39 + 'px');
						$(this).find('.arrow-bottom-border').css('left', width/2 - 11 + 'px');
						$(this).find('.arrow-bottom-border').css('border-bottom-color', borderColor);
								
						$(this).css('width', width + 'px');
						$(this).css('height', height + img_height/4 + 25 + 'px');
						$(this).css('right', width/2 + 'px');
						$(this).css('bottom', img_height/4 - 20 + 'px');
						
						if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/4/') >= 0)
						{
							$(this).css('right', width/2 - 4 + 'px');
							$(this).css('bottom', '0px');
						}
						else if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/5/') >= 0)
							$(this).css('bottom', img_height/4 - 10 + 'px');
								
						$(this).find('.imapper-arrow').addClass('arrow-up');
						var color = $(this).find('.imapper-arrow').css('border-top-color');
						$(this).find('.imapper-arrow').css('border-top-color', 'transparent');
						$(this).find('.imapper-arrow').css('border-bottom-color', color);
						$(this).find('.imapper-arrow').css('top', img_height/4 + 10 + 'px');
					}
					else if (position == 'right')
					{
						$(this).find('.imapper-content').css('position', 'absolute');
						$(this).find('.imapper-content').css('right', '0px');
						$(this).find('.imapper-content').css('bottom', '0px');
						
						$(this).find('.arrow-right-border').css('top', height/2 - 11 + 'px');
						$(this).find('.arrow-right-border').css('left', img_width + 34 + 'px');
						$(this).find('.arrow-right-border').css('border-right-color', borderColor);
						
						$(this).css('width', width + img_width/4 + 40 + 'px');
						$(this).css('height', height + 'px');
						$(this).css('right', '-30px');
						$(this).css('bottom', height/2 + img_height/2 + 'px');
						
						if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/4/') >= 0)
						{
							$(this).css('right', '0px');
							$(this).css('bottom', height/2 + 10 + 'px');
						}
						else if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/5/') >= 0)
							$(this).css('right', '-10px');
						
						$(this).find('.imapper-arrow').addClass('arrow-left');
						var color = $(this).find('.imapper-arrow').css('border-top-color');
						$(this).find('.imapper-arrow').css('border-top-color', 'transparent');
						$(this).find('.imapper-arrow').css('border-right-color', color);
						$(this).find('.imapper-arrow').css('top', height/2 - 10 + 'px');
						$(this).find('.imapper-arrow').css('left', img_width/4 + 25 + 'px');
					}
					else if (position == 'left')
					{
						$(this).find('.imapper-content').css('position', 'absolute');
						$(this).find('.imapper-content').css('bottom', '0px');
						
						$(this).find('.arrow-left-border').css('top', height/2 - 11 + 'px');
						$(this).find('.arrow-left-border').css('left', width + 'px');
						$(this).find('.arrow-left-border').css('border-left-color', borderColor);
						
						$(this).css('width', width + img_width/4 + 40 + 'px');
						$(this).css('height', height + 'px');
						$(this).css('right', width + img_width - 2 + 'px');
						$(this).css('bottom', height/2 + img_height/2 + 'px');
						
						if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/4/') >= 0)
						{
							$(this).css('right', width + 44 + 'px');
							$(this).css('bottom', height/2 + 10 + 'px');
						}
						else if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/5/') >= 0)
							$(this).css('right', width + img_width - 12 + 'px');
						
						$(this).find('.imapper-arrow').addClass('arrow-right');
						var color = $(this).find('.imapper-arrow').css('border-top-color');
						$(this).find('.imapper-arrow').css('border-top-color', 'transparent');
						$(this).find('.imapper-arrow').css('border-left-color', color);
						$(this).find('.imapper-arrow').css('top', height/2 - 10 + 'px');
						$(this).find('.imapper-arrow').css('left', width + 'px');
					}
				}
				else if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/2/') >= 0)
				{
					$(this).find('.imapper-content-header').css('padding', '0px 10px 0px 10px');
					if (position == 'right')
					{
						$(this).find('.imapper-content').css('position', 'absolute');
						$(this).find('.imapper-content').css('left', '20px');
						$(this).find('.imapper-content').css('border-top-left-radius', '0px');
						$(this).find('.imapper-content').css('border-bottom-left-radius', '0px');
						$(this).find('.imapper-content').css('border-left', 'none');
						
						$(this).css('width', width + 20 + 'px');
						$(this).css('height', height + 'px');
						$(this).css('right', '-2px');
						$(this).css('bottom', '75px');
						
						$(this).find('.imapper-content').css('width', '0px');
						
						$(this).find('.triangle-right-border').css('border-top-color', borderColor);
						$(this).find('.triangle-right-border').css('border-bottom-color', borderColor);
						
						$(this).find('.imapper-arrow').addClass('triangle-right');
						var color = $(this).find('.imapper-arrow').css('border-top-color');
						$(this).find('.imapper-arrow').css('border-bottom-color', color);
						$(this).find('.imapper-arrow').css('position', 'absolute');
						$(this).find('.imapper-arrow').css('top', '1px');
					}
					else if (position == 'left')
					{
						$(this).find('.imapper-content').css('position', 'absolute');
						$(this).find('.imapper-content').css('left', '0px');
						$(this).find('.imapper-content').css('border-top-right-radius', '0px');
						$(this).find('.imapper-content').css('border-bottom-right-radius', '0px');
						$(this).find('.imapper-content').css('border-right', 'none');
						
						$(this).find('.imapper-content').css('width', '0px');
						$(this).find('.imapper-content').css('margin-left', width + 'px');
						
						$(this).find('.triangle-left-border').css('border-top-color', borderColor);
						$(this).find('.triangle-left-border').css('border-bottom-color', borderColor);
						
						$(this).css('width', width + 20 + 'px');
						$(this).css('height', height + 'px');
						$(this).css('right', width + 18 + 'px');
						$(this).css('bottom', '75px');
						
						$(this).find('.imapper-arrow').addClass('triangle-left');
						var color = $(this).find('.imapper-arrow').css('border-top-color');
						$(this).find('.imapper-arrow').css('border-bottom-color', color);
						$(this).find('.imapper-arrow').css('position', 'absolute');
						$(this).find('.imapper-arrow').css('right', '0px');
						$(this).find('.imapper-arrow').css('top', '1px');
					}
				}
				
				if ($(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/1/') >= 0)
				{
					var radius = (parseInt($(this).find('.imapper-content').css('border-top-left-radius')) / 2 + 1);
					var zindextab = 100;
					
					if (position == 'left' || position == 'right')
					{
						var bottom = parseInt($(this).css('height')) + 25 - radius;
						var bottom_tab = parseInt($(this).css('height'));
						
						$(this).find('.imapper-content-additional').each(function() {
							$(this).css('height', '0px');
							$(this).find('.imapper-content-inner').css('display', 'none');
							$(this).css('bottom', bottom + 'px');
							bottom += 25 - radius;
						});
						
						$(this).find('.imapper-content-tab').each(function(index) {
							$(this).css('height', '25px');	
							$(this).css('width', width + 'px');
							
							$(this).css('border-top-left-radius', $(this).parent().find('.imapper-content').css('border-top-left-radius'));	
							$(this).css('border-top-right-radius', $(this).parent().find('.imapper-content').css('border-top-right-radius'));
							$(this).css('border-style', 'solid');
							$(this).css('border-width', '1px 1px 0 1px');
							$(this).css('border-color', borderColor);
							
							$(this).find('a').css('padding', '0 0 0 10px');
							
							if (position == 'right')
								$(this).css('right', '0px');
								
							$(this).css('bottom', bottom_tab + 'px');
							bottom_tab += 25 - radius;
							
							$(this).css('z-index', zindextab);
							zindextab--;
						});
						
						$(this).find('.imapper-content').each(function(index) {
							
							if ($(this).parent().parent().find('.imapper-value-tab-number').html() != '1')
							{
								if (index == 0)
								{
									$(this).css('border-top-left-radius', '0px');	
									$(this).css('border-top-right-radius', '0px');
								}
								else
									$(this).css('border-radius', '0px');
									
								$(this).find('.imapper-content').css('border-width', '0 1px 1px 1px');
							}
						});
					}
					else if (position == 'top' || position == 'bottom')
					{
						var right = parseInt($(this).css('width')) + 25 - radius;
						var right_tab = parseInt($(this).css('width'));
						
						$(this).find('.imapper-content-additional').each(function() {
							$(this).css('width', '0px');
							$(this).find('.imapper-content-inner').css('display', 'none');
							$(this).css('right', right + 'px');
							right += 25 - radius;
						});
						
						$(this).find('.imapper-content-tab').each(function() {
							$(this).css('width', '25px');	
							$(this).css('height', height + 'px');
							$(this).find('a').css('height', height + 'px');
							
							$(this).css('border-top-left-radius', $(this).parent().find('.imapper-content').css('border-top-left-radius'));	
							$(this).css('border-bottom-left-radius', $(this).parent().find('.imapper-content').css('border-bottom-left-radius'));
							$(this).css('border-style', 'solid');
							$(this).css('border-width', '1px 0 1px 1px');
							$(this).css('border-color', borderColor);
							
							$(this).find('a').css('padding', '5px 0 0 5px');
							
							if (position == 'bottom')
								$(this).css('bottom', '0px');
								
							$(this).css('right', right_tab + 'px');
							right_tab += 25 - radius;
							
							$(this).css('z-index', zindextab);
							zindextab--;
						});
						
						$(this).find('.imapper-content').each(function(index) {
							
							if ($(this).parent().parent().find('.imapper-value-tab-number').html() != '1')
							{
								if (index == 0)
								{
									$(this).css('border-top-left-radius', '0px');	
									$(this).css('border-bottom-left-radius', '0px');
								}
								else
									$(this).css('border-radius', '0px');
									
								$(this).find('.imapper-content').css('border-width', '1px 1px 1px 0');
							}
						});
					}
				}
				
				var position = $(this).parent().find('.imapper' + id + '-pin').attr('src').indexOf('/images/');
				var pluginUrl = $(this).parent().find('.imapper' + id + '-pin').attr('src').substring(0, position);
				$(this).parent().find('.imapper-pin-color').css('behavior', 'url(' + pluginUrl + 'pie/PIE.htc)');
			});		
						
			var hheight;
			$('.imapper-content-text').each(function(index) {
				if (index == 0)
					hheight = $(this).parent().find('.imapper-content-header').height();
					
				if ($(this).parent().find('.imapper-content-header').html() != '')
				{
					var dis;
					if ($(this).parent().attr('class') == 'imapper-content-inner')
						dis = $(this).parent();
					else
						dis = $(this);
				
					if ($(dis).parent().parent().parent().find('img').attr('src').indexOf('images/icons/2/') >= 0)
						$(this).css('height', $(this).parent().height() - hheight - 20 + 'px');
					else
						$(this).css('height', $(this).parent().height() - hheight - 30 + 'px');
				}
				else
				{
					$(this).parent().find('.imapper-content-header').css('padding', '0px');
					$(this).css('height', $(this).parent().height() - 20 + 'px');
				}
					
				$(this).imCustomScrollbar();
			});
			
			$(this).css('visibility', 'visible');
			
			$('.imapper-content-tab').find('a').live('click', function(e) {
				e.preventDefault();
				
				var pinId = $(this).parent().parent().parent().find('.imapper' + id + '-pin').attr('id').substring($(this).parent().parent().parent().find('.imapper' + id + '-pin').attr('id').indexOf('-pin') + 4);
				var newClick = parseInt($(this).html());
				var dis = $(this).parent().parent();
				var position = $(this).parent().parent().parent().find('.imapper' + id + '-value-item-open-position').html();
				
				if (newClick != tab_clicked[pinId])
				{			
					if (position == 'left' || position == 'right')
					{	
						if (newClick > tab_clicked[pinId])
						{
							$(dis).find('.imapper-content').eq(tab_clicked[pinId] - 1).find('.imapper-content-inner').fadeOut('fast');
							$(dis).find('.imapper-content').eq(tab_clicked[pinId] - 1).animate({ height: 0}, {duration: 400});
							
							for (var i = tab_clicked[pinId]; i < newClick; i++)
							{
								var bottomNew = parseInt($(dis).find('.imapper-content-tab').eq(i - 1).css('bottom')) - height;
								$(dis).find('.imapper-content-tab').eq(i - 1).animate({ bottom: bottomNew}, {duration: 400});
								
								if (i != tab_clicked[pinId])
									$(dis).find('.imapper-content').eq(i - 1).css('bottom', parseInt($(dis).find('.imapper-content').eq(i - 1).css('bottom')) - height);
							}
							
							$(dis).find('.imapper-content').eq(newClick - 1).find('.imapper-content-inner').fadeIn('fast');
							var bottomNew2 = parseInt($(dis).find('.imapper-content').eq(newClick - 1).css('bottom')) - height;
							$(dis).find('.imapper-content').eq(newClick - 1).animate({ height: height, bottom: bottomNew2}, {duration: 400});
						}
						else
						{
							$(dis).find('.imapper-content').eq(tab_clicked[pinId] - 1).find('.imapper-content-inner').fadeOut('fast');
							var bottomNew = parseInt($(dis).find('.imapper-content').eq(tab_clicked[pinId] - 1).css('bottom')) + height;
							$(dis).find('.imapper-content').eq(tab_clicked[pinId] - 1).animate({ height: 0, bottom: bottomNew}, {duration: 400});
								
							$(dis).find('.imapper-content').eq(newClick - 1).find('.imapper-content-inner').fadeIn('fast');
							$(dis).find('.imapper-content').eq(newClick - 1).animate({ height: height}, {duration: 400});
							
							for (var i = newClick; i < tab_clicked[pinId]; i++)
							{
								var bottomNew2 = parseInt($(dis).find('.imapper-content-tab').eq(i - 1).css('bottom')) + height;
								$(dis).find('.imapper-content-tab').eq(i - 1).animate({ bottom: bottomNew2}, {duration: 400});
								
								if (i != newClick)
									$(dis).find('.imapper-content').eq(i - 1).css('bottom', parseInt($(dis).find('.imapper-content').eq(i - 1).css('bottom')) + height);
							}
						}
					}
					else if (position == 'top' || position == 'bottom')
					{
						if (newClick > tab_clicked[pinId])
						{
							$(dis).find('.imapper-content').eq(tab_clicked[pinId] - 1).find('.imapper-content-inner').fadeOut('fast');
							$(dis).find('.imapper-content').eq(tab_clicked[pinId] - 1).animate({ width: 0}, {duration: 400});
							for (var i = tab_clicked[pinId]; i < newClick; i++)
							{
								var rightNew = parseInt($(dis).find('.imapper-content-tab').eq(i - 1).css('right')) - width;
								$(dis).find('.imapper-content-tab').eq(i - 1).animate({ right: rightNew}, {duration: 400});
								
								if (i != tab_clicked[pinId])
									$(dis).find('.imapper-content').eq(i - 1).css('right', parseInt($(dis).find('.imapper-content').eq(i - 1).css('right')) - width);
							}
							
							$(dis).find('.imapper-content').eq(newClick - 1).find('.imapper-content-inner').fadeIn('fast');
							var rightNew2 = parseInt($(dis).find('.imapper-content').eq(newClick - 1).css('right')) - width;
							$(dis).find('.imapper-content').eq(newClick - 1).animate({ width: width, right: rightNew2}, {duration: 400});
						}
						else
						{
							$(dis).find('.imapper-content').eq(tab_clicked[pinId] - 1).find('.imapper-content-inner').fadeOut('fast');
							var rightNew = parseInt($(dis).find('.imapper-content').eq(tab_clicked[pinId] - 1).css('right')) + width;
							$(dis).find('.imapper-content').eq(tab_clicked[pinId] - 1).animate({ width: 0, right: rightNew}, {duration: 400});
								
							$(dis).find('.imapper-content').eq(newClick - 1).find('.imapper-content-inner').fadeIn('fast');
							$(dis).find('.imapper-content').eq(newClick - 1).animate({ width: width}, {duration: 400});
							
							for (var i = newClick; i < tab_clicked[pinId]; i++)
							{
								var rightNew2 = parseInt($(dis).find('.imapper-content-tab').eq(i - 1).css('right')) + width;
								$(dis).find('.imapper-content-tab').eq(i - 1).animate({ right: rightNew2}, {duration: 400});
								
								if (i != newClick)
									$(dis).find('.imapper-content').eq(i - 1).css('right', parseInt($(dis).find('.imapper-content').eq(i - 1).css('right')) + width);
							}
						}
					}
					
					$(dis).find('.imapper-content').eq(newClick - 1).find('.imapper-content-text').imCustomScrollbar('update');
					tab_clicked[pinId] = newClick;
				}
			});
			
			if (openStyle == 'hover')
			{
				$('.imapper' + id + '-pin').mouseover( function() {
					
					var pinId = $(this).attr('id').substring($(this).attr('id').indexOf('-pin') + 4);
					var properties2 = {};
					var duration = {duration: 400, queue: false};
					
					$(this).css('z-index', '12');
					$('#imapper' + id + '-value-item' + pinId + '-tab-number').css('z-index', '12');
					$('#imapper' + id + '-pin' + pinId + '-content-wrapper').css('z-index', '11');
					
					if ($('#imapper' + id + '-pin' + pinId + '-content-wrapper').css('visibility') == 'hidden')
						$('#imapper' + id + '-pin' + pinId + '-content-wrapper').css('visibility', 'visible');
					
					if ($(this).attr('src').indexOf('images/icons/2/') >= 0)
						{
							if ($(this).parent().find('.imapper' + id + '-value-item-open-position').html() == 'right')
								properties2 = {width: width};
							else
								properties2 = {width: width, marginLeft: 0};
								
							duration = {duration: 400, queue: false}
						}
					
					$('#imapper' + id + '-pin' + pinId + '-content-wrapper').stop(true).animate({
						opacity: 1
					}, duration);
					
					if ($(this).attr('src').indexOf('images/icons/2/') >= 0)
						$('#imapper' + id + '-pin' + pinId + '-content-wrapper').find('.imapper-content').stop(true).animate(properties2, {
									duration: 400,
									queue: false
								});
					
				});
				
				$('.imapper-pin-wrapper').mouseleave( function() {

					var pinId = $(this).find('.imapper' + id + '-pin').attr('id').substring($(this).find('.imapper' + id + '-pin').attr('id').indexOf('-pin') + 4);
					var properties = {opacity: 0};
					var properties2 = {};
					var duration = {};
					
					if ($(this).find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/2/') >= 0)
					{
						if ($(this).find('.imapper' + id + '-value-item-open-position').html() == 'right')
							properties2 = {width: 0};
						else
							properties2 = {width: 0, marginLeft: width};
									
						duration = {duration: 400, queue: false};
					}
					else
					{
						duration = {
							duration: 400,
							queue: false,
							complete: function() {
								$(this).find('.imapper-content').parent().css('visibility', 'hidden');
							}
						};
					}
					
					$(this).find('.imapper-content-wrapper').stop(true).animate(properties, duration);
					
					if ($(this).find('.imapper' + id + '-pin').attr('src').indexOf('images/icons/2/') >= 0)
						$(this).find('.imapper-content').stop(true).animate(properties2, {
									duration: 400,
									queue: false,
									complete: function() {
										$(this).find('.imapper-content').parent().css('visibility', 'hidden');
									}
							});
							
					$(this).find('.imapper' + id + '-pin').css('z-index', '10');
					$('#imapper' + id + '-value-item' + pinId + '-tab-number').css('z-index', '10');
					$('#imapper' + id + '-pin' + pinId + '-content-wrapper').css('z-index', '9');
						
				});
			}
			else if (openStyle == 'click')
			{
				$('.imapper' + id + '-pin').click( function() {
					var pinId = $(this).attr('id').substring($(this).attr('id').indexOf('-pin') + 4);
					
					if (clicked[pinId] == 0)
					{
						var properties = {opacity: 1};
						var properties2 = {};
						var duration = {duration: 400, queue: false};
						
						$(this).css('z-index', '12');
						$('#imapper' + id + '-value-item' + pinId + '-tab-number').css('z-index', '12');
						$('#imapper' + id + '-pin' + pinId + '-content-wrapper').css('z-index', '11');
						
						if ($(this).attr('src').indexOf('images/icons/2/') >= 0)
						{
							if ($(this).parent().find('.imapper' + id + '-value-item-open-position').html() == 'right')
								properties2 = {width: width};
							else
								properties2 = {width: width, marginLeft: 0};
						}
						
						$('#imapper' + id + '-pin' + pinId + '-content-wrapper').css('visibility', 'visible').animate(properties, duration);
						
						if ($(this).attr('src').indexOf('images/icons/2/') >= 0)
							$('#imapper' + id + '-pin' + pinId + '-content-wrapper').find('.imapper-content').animate(properties2,
							{
								duration: 400,
								queue: false
							});
						
						$('.imapper' + id + '-pin').each(function() {
							var pid = $(this).attr('id').substring($(this).attr('id').indexOf('-pin') + 4);
						 	if (clicked[pid] == 1)
							{
								$(this).css('z-index', '10');
								$('#imapper' + id + '-pin' + pid + '-content-wrapper').css('z-index', '9');
								$(this).trigger('click');
							}
						});
						
						clicked[pinId] = 1;
					}
					else
					{
						var properties = {opacity: 0};
						var properties2 = {};
						var duration = {};
						
						if ($(this).attr('src').indexOf('images/icons/2/') >= 0)
						{
							if ($(this).parent().find('.imapper' + id + '-value-item-open-position').html() == 'right')
								properties2 = {width: 0};
							else
								properties2 = {width: 0, marginLeft: width};
								
							duration = {duration: 400, queue: false};
						}
						else
							duration = {
							duration: 400,
							queue: false,
							complete: function() {
								$(this).css('visibility', 'hidden');
							}
						};

						$('#imapper' + id + '-pin' + pinId + '-content-wrapper').animate(properties, duration);
						
						if ($(this).attr('src').indexOf('images/icons/2/') >= 0)
							$('#imapper' + id + '-pin' + pinId + '-content-wrapper').find('.imapper-content').animate(properties2,
							{
								duration: 400,
								queue: false,
								complete: function() {
									$(this).parent().css('visibility', 'hidden');
								}
						});
						
						$(this).css('z-index', '10');
						$('#imapper' + id + '-value-item' + pinId + '-tab-number').css('z-index', '10');
						$('#imapper' + id + '-pin' + pinId + '-content-wrapper').css('z-index', '9');
						clicked[pinId] = 0;
					}
				});
				
				$('#imapper' + id + '-map-image').click(function() {
					$('.imapper' + id + '-pin').each(function() {
						var pid = $(this).attr('id').substring($(this).attr('id').indexOf('-pin') + 4);
						 if (clicked[pid] == 1)
						{
							$(this).css('z-index', '10');
							$('#imapper' + id + '-pin' + pid + '-content-wrapper').css('z-index', '9');
							$(this).trigger('click');
						}
					});
				});
			}
		});
	});
})(jQuery);