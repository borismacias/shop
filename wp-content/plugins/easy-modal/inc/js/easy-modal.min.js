// Easy Modal v1.3
(function ($)
{
	var currentMousePos = { x: -1, y: -1 };
    var methods = {
        init: function (options)
        {
        	if(!$(this).parent().is('body'))
        	{
        		$(this).appendTo('body');
        	}
            var opts = $.extend({}, $.fn.emodal.defaults, $(this).data(), options);
			return $(this).data('emodal', opts);
        },
        close: function (options)
        {
            var options = $.extend({
				speed: 'fast',
				overlay: true
			}, $.fn.emodal.defaults, options);

			var $this = $(this);

			var opts = $this.data('emodal');
            $this.removeClass('active').fadeOut(options.speed,function(){
                if(options.overlay)
					$('#modal-overlay').fadeOut(options.speed,function(){
						var vids = $('iframe',$this).filter('[src*="youtube"],[src*="vimeo"]');
						if (vids.length > 0 )
						{
							vids.each(function(){
								var src = $(this).attr('src');
								$(this).attr('src', '').attr('src', src);
							})
						}
						options.onClose();
					});
            })
			$(window).unbind('scroll.emodal').unbind('keyup.emodal');
            return this;
        },
		open: function()
		{
			var $this = $(this);
            var opts = $this.data('emodal');
			if(themes[opts.theme] === undefined)
			{
				var theme = themes[1];
			}
			else
			{
				var theme = themes[opts.theme];
			}
			// Check for and create Modal Overlay
			if(!$('#modal-overlay').length){ $('<div id="modal-overlay">').appendTo('body');	}
			
			// If not stackable close all other modals.
			if($('.modal.active').length)
			{
				$('.modal.active').each(function(){$(this).emodal('close',{speed:100,overlay:false})});
			}
			else
			{
				$('#modal-overlay').css('opacity',0).show(0);
			}
			
			$this.addClass('active');
			// Modal Clos Button
			
			if(!opts.closeDisabled && $('.close-modal',$this).length)
			{
				$('.close-modal',$this)
					.unbind('click')
					.click(function(){	$this.emodal('close');	})
					.themeClose(opts);
			}
			$('#modal-overlay')
				.unbind('click')
				.click(function()
				{
					if (opts.overlayClose == true)
					{
						$this.emodal('close');
					}
				})
				.themeOverlay(opts);
				
			if(opts.overlayClose == true)
			{
				$(window).bind('keyup.emodal',function(e){
					if($('.modal.active').length && e.keyCode == 27)
					{
						$this.emodal('close');
					}
				});
			}
			$this.themeModal(opts);
			$this.animation(opts.animation,opts);
			return $this;
		}/*,
        show: function ()
        {
            if (opts.type === 'Image')
            {
                container.css(
                {
                    maxWidth: opts.maxWidth,
                    maxHeight: opts.maxHeight
                });
                var abcs = $("a.eModal-Image")
                var prevButton = $('<a>')
					.attr('id',opts.prevId)
					.click(function (){
                    var current = $('.eModal-Opened')
					var prev = abcs.eq(abcs.index(current) - 1)
					current.removeClass('eModal-Opened')
                    if (prev.length <= 0) prev = abcs.eq(abcs.length)
					prev.addClass('eModal-Opened')
					container
						.animate({opacity: '.01'}, function (){
							var img = $("<img/>")
								.attr('src', prev.attr('href'))
								.css({
									maxWidth: '100%',
									maxHeight: '100%'
								})
								.load(function (){
									if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0)
									{
										alert('broken image!')
									}
									else
									{
										if (this.naturalWidth > opts.maxWidth) img.attr('width', opts.maxWidth)
										if (this.naturalHeight > opts.maxHeight) img.attr('height', opts.maxHeight)
										content
											.html(img)
											.css({opacity:'.01'})
										
										container
											.emodal('center')
											.animate({opacity:'.01'})
											.animate({opacity:'1'})
											
										content.animate({opacity: '1'})
									}
								})
						})
                    return false
                })
                var nextButton = $('<a>')
					.attr('id',opts.nextId)
					.click(function (){
						var current = $('.eModal-Opened')

						var next = abcs.eq(abcs.index(current) + 1)
						current.removeClass('eModal-Opened')
						if (next.length == 0) next = abcs.eq(0)
						next.addClass('eModal-Opened')
						container
							.animate({opacity: '.01'}, function (){
								var img = $("<img/>")
									.attr('src', next.attr('href'))
									.css({
										maxWidth: '100%',
										maxHeight: '100%'
									})
									.load(function (){
										if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0)
										{
											alert('broken image!')
										}
										else
										{
											if (this.naturalWidth > opts.maxWidth) img.attr('width', opts.maxWidth)
											if (this.naturalHeight > opts.maxHeight) img.attr('height', opts.maxHeight)
											content
												.html(img)
												.css({opacity:'.01'})
											
											container
												.emodal('center')
												.animate({opacity:'.01'})
												.animate({opacity:'1'})
												
											content.animate({opacity: '1'})
										}
									})
							})
						return false
					})
				var buttons = $('<div>')
					.attr('id',opts.buttonsId)
					.append(prevButton, nextButton)
					.appendTo(container)
            }
            if (opts.type === 'Link')
			{
                opts.requestData.url = $(this).attr('href')
                opts.requestData.iframeWidth = opts.maxWidth
                opts.requestData.iframeHeight = opts.maxHeight
            }
            var loaded = false
            if (opts.url != null){
                if (opts.type === 'Image'){
                    var img = $("<img/>")
						.attr('src', opts.url)
						.css({
							maxWidth: '100%',
							maxHeight: '100%'
						})
						.load(function (){
							if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0)								alert('broken image!')
							else {
								if (this.naturalWidth > opts.maxWidth) img.attr('width', opts.maxWidth)
								if (this.naturalHeight > opts.maxHeight) img.attr('height', opts.maxHeight)
								content.append(img)
								loaded = true
							}
						})
                }
                else
                {
                    $.post(opts.url, opts.requestData, function (data){
						content.prepend(data)
						if($('form',content).length)
						{
							$orig_action = $('form',content).attr('action').split('#');
							$('form',content).attr('action',"#" + $orig_action[1]).attr('action')
						}
                        container
							.show()
							.css({opacity: '.01'});
						if(opts.userMaxHeight > 0)
						{
							content.css({maxHeight: $(window).height() * (opts.userMaxHeight / 100) + 'px'});
						}
						else if(content.innerHeight() > opts.maxHeight && opts.type != 'Link')
						{
							content.css({maxHeight: (opts.maxHeight - 60) + 'px'});
						}
						
						if(opts.userHeight > 0)
						{
							content.css({height: opts.userHeight + 'px'});
						}
						
						if(opts.userMaxWidth > 0)
						{
							content.css({maxWidth: $(window).width() * (opts.userMaxWidth / 100) + 'px'});
						}
						
						if(opts.userWidth > 0)
						{
							content.css({width: opts.userWidth + 'px'});
						}
                        var title = content
							.find("#eModal-Title")
							.css({
								color: theme.contentTitleFontColor,
								fontFamily: theme.contentTitleFontFamily,
								fontSize: theme.contentTitleFontSize + 'px'
							})
                        if(title) title.attr('title', title.text()).appendTo(controls)
                        opts.onLoad()
                        if(opts.cf7form == true) loadCf7()
                        if(opts.gravityform == true) loadGravityForms()
                        loaded = true
                    })
                }
            }
        },*/
    };
    $.fn.emodal = function(method)
    {
        // Method calling logic
        if (methods[method])
        {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method)
        {
            return methods.init.apply(this, arguments);
        }
        else
        {
            $.error('Method ' + method + ' does not exist on jQuery.emodal');
        }
    };
	$.fn.themeOverlay = function(opts)
	{
		var $this = $(this);
		if(themes[opts.theme] === undefined)
		{
			var theme = themes[1];
		}
		else
		{
			var theme = themes[opts.theme];
		}
		return $this.addClass('theme-'+opts.theme).animate({
			backgroundColor: theme.overlayColor,
			opacity: theme.overlayOpacity / 100
		},opts.duration);
	};
	$.fn.themeModal = function(opts)
	{
		var $this = $(this);
		if(themes[opts.theme] === undefined)
		{
			var theme = themes[1];
		}
		else
		{
			var theme = themes[opts.theme];
		}
		if(opts.size == 'custom')
		{
			$this.css({
				'height': opts.userHeight ? opts.userHeight + opts.userHeightUnit : $this.css('height'),
				'width': opts.userWidth ? opts.userWidth + opts.userWidthUnit : $this.css('width'),
				'margin-left': opts.userWidth ? -(opts.userWidth / 2) + opts.userWidthUnit : $this.css('margin-left')
			});
		}
		$this
			.addClass(opts.size)
			.addClass('theme-'+opts.theme)
			.css({
				color: theme.contentFontColor,
				backgroundColor: theme.containerBgColor,
				padding: theme.containerPadding + 'px',
				border: theme.containerBorderColor + ' ' + theme.containerBorderStyle + ' ' + theme.containerBorderWidth + 'px',
				"-webkit-border-radius": theme.containerBorderRadius + 'px',
				"border-radius": theme.containerBorderRadius + 'px'
			});
		$('.title', $this).css({
			color: theme.contentTitleFontColor,
			fontFamily: theme.contentTitleFontFamily,
			fontSize: theme.contentTitleFontSize
		});
		return $this;
	};
	$.fn.themeClose = function(opts)
	{
		var $this = $(this);
		if(themes[opts.theme] === undefined)
		{
			var theme = themes[1];
		}
		else
		{
			var theme = themes[opts.theme];
		}
		if(theme.closeLocation == 'outside')		
		{
			var val = theme.closeSize;
			var top = bottom = left = right = 'auto';
			switch (theme.closePosition)
			{
				case 'topright':
					top = -(val / 2) + 'px';
					right = -(val / 2) + 'px';
					break;
				case 'topleft':
					top = -(val / 2) + 'px';
					left = -(val / 2) + 'px';
					break;
				case 'bottomright':
					bottom = -(val / 2) + 'px';
					right = -(val / 2) + 'px';
					break;
				case 'bottomleft':
					bottom = -(val / 2) + 'px';
					left = -(val / 2) + 'px';
					break;
			}
			$this
				.addClass('outside')
				.css({
					left: left,
					right: right,
					top: top,
					bottom: bottom,
					height: theme.closeSize + 'px',
					fontSize: theme.closeFontSize + 'px',
					width: theme.closeSize + 'px',
					backgroundColor: theme.closeBgColor,
					"-webkit-border-radius": theme.closeBorderRadius + 'px',
					"border-radius": theme.closeBorderRadius + 'px',
					lineHeight: theme.closeSize + 'px'
				});
		}
		return $this.addClass('theme-'+opts.theme)
			.html(theme.closeText)
			.css({
				color: theme.closeFontColor,
			});
	};
	
	var animations = {
		fade: function(options)
		{
			var $this = $(this).show(0).css({'opacity':0,'top':$(window).scrollTop() + 100 +'px'});
			var opts = $.extend($.fn.animation.defaults, options);
			$this.animate({
				opacity: 1
			},parseInt(opts.duration),opts.easing,function(){
				$this
					.removeAttr('style')
					.css({'display':'block','visibility':'visible','top': ($(window).scrollTop() + 100) +'px'})
					.themeModal(opts);
			});
		},
		fadeAndSlide: function(options)
		{
			var $this = $(this).show(0).css('opacity',0);
			var opts = $.extend($.fn.animation.defaults, options);
			switch(opts.direction)
			{
				case 'mouse': $this.css({'top': currentMousePos.y + 'px','left': currentMousePos.x +'px'}); break;
				case 'top': $this.css({'top':  $(window).scrollTop() - $this.outerHeight(true) + 'px'}); break;
				case 'left': $this.css({'left': '-'+$this.outerWidth(true)+'px','top':$(window).scrollTop() + 100 +'px'}); break;
				case 'bottom': $this.css({'top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
				case 'right': $this.css({'left': $(window).innerWidth()+'px','top':$(window).scrollTop() + 100 +'px'}); break;
				case 'topleft': $this.css({'left': '-'+$this.outerWidth(true)+'px','top':$(window).scrollTop() + 100 +'px','top': $(window).scrollTop() - $this.outerHeight(true) + 'px'}); break;
				case 'topright': $this.css({'left': $(window).innerWidth()+'px','top': $(window).scrollTop() - $this.outerHeight(true) + 'px'}); break;
				case 'bottomleft': $this.css({'left': '-'+$this.outerWidth(true)+'px','top':$(window).scrollTop() + 100 +'px','top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
				case 'bottomright': $this.css({'left': $(window).innerWidth()+'px','top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
			}
			$('html').css('overflow-x','hidden');
			$this.animate({
				'left': '50%',
				'top': $(window).scrollTop() + 100 +'px'
			},{duration: opts.duration , queue:false},opts.easing);
			setTimeout(function()
			{
				$this.animate({
					'opacity': 1
				},opts.duration * .75,opts.easing,function(){
					$this
						.removeAttr('style')
						.css({'display':'block','visibility':'visible','top': ($(window).scrollTop() + 100) +'px'})
						.themeModal(opts);
					$('html').css('overflow-x','inherit');
				});
			},opts.duration * .25);
		},
		grow: function(options)
		{
			var $this = $(this).show(0);
			var opts = $.extend($.fn.animation.defaults, options);
			var currently = {
				width: parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100 + '%',
				height: parseInt($this.css('height')),
				marginLeft: '-' + parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100  / 2 + '%',
				padding: parseInt($this.css('padding-left')) / parseInt($this.css('font-size')) + 'em'
			};
			$('*',$this).fadeOut(0);
			$this.css({
				'top': (currently.height/10) * 5 + $(window).scrollTop() + 100 +'px',
				'left': (currently.width/10) * 5 + ($(window).innerWidth() / 2) +'px',
				'height': 0,
				'width': 0,
				'padding': 0,
				'margin-left': 0
			}).animate({
				'top': $(window).scrollTop() + 100 +'px',
				'left': '50%',
				'padding': currently.padding,
				'height': currently.height,
				'width': currently.width,
				'margin-left': currently.marginLeft
			},opts.duration,function(){
				$this
					.removeAttr('style')
					.css({'display':'block','visibility':'visible','top': ($(window).scrollTop() + 100) +'px'})
					.themeModal(opts);
				$('*',$this).fadeIn('fast');
			});
		},
		growAndSlide: function(options)
		{
			var $this = $(this).show(0);
			var opts = $.extend($.fn.animation.defaults, options);
			var currently = {
				width: parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100 + '%',
				height: parseInt($this.css('height')),
				marginLeft: '-' + parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100  / 2 + '%',
				padding: parseInt($this.css('padding-left')) / parseInt($this.css('font-size')) + 'em'
			};
			$('html,body').css('overflow-x','hidden');
			$('*',$this).fadeOut(0);
			$this.css({
				'opacity': 1,
				'height': 0,
				'width': 0,
				'padding': 0,
				'margin-left': 0
			});
			switch(opts.direction)
			{
				case 'mouse': $this.css({'top': currentMousePos.y + 'px','left': currentMousePos.x +'px'}); break;
				case 'top': $this.css({'top':  $(window).scrollTop() + $this.outerHeight(true) + 'px'}); break;
				case 'left': $this.css({'left': 0,'top':$(window).scrollTop() + 100 +'px'}); break;
				case 'bottom': $this.css({'top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
				case 'right': $this.css({'left': $(window).innerWidth()+'px','top':$(window).scrollTop() + 100 +'px'}); break;
				case 'topleft': $this.css({'left': 0,'top':$(window).scrollTop() + 100 +'px','top': $(window).scrollTop() - $this.outerHeight(true) + 'px'}); break;
				case 'topright': $this.css({'left': $(window).innerWidth()+'px','top': $(window).scrollTop() - $this.outerHeight(true) + 'px'}); break;
				case 'bottomleft': $this.css({'left': 0,'top':$(window).scrollTop() + 100 +'px','top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
				case 'bottomright': $this.css({'left': $(window).innerWidth()+'px','top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
			}
			$this.animate({
				'height': currently.height,
				'padding': currently.padding,
				'width': currently.width,
				'margin-left': currently.marginLeft
			},{duration: opts.duration , queue:false},opts.easing);
			setTimeout(function()
			{
				$this.animate({
					'height': 'auto',
					'top': ($(window).scrollTop() + 100) +'px',
					'left': '50%'
				},opts.duration * .95,opts.easing,function(){
					$this
						.removeAttr('style')
						.css({'display':'block','visibility':'visible','top': ($(window).scrollTop() + 100) +'px'})
						.themeModal(opts);
					$('*',$this).fadeIn('fast');	
					$('html').css('overflow-x','inherit');
				});
			},opts.duration * .05);
		}/*,
		canvas: function(options)
		{
			var $this = $(this)
			$this.css('top',$(document).height() + $this.height()).show(0);
			html2canvas($this, {
				onrendered: function(canvas) {
					var canvas = canvas;
					//$('body').append(canvas);
					var x = 4,
						y = 3,
						random = true,
						width = parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100,
						height = parseInt($this.css('height')),
						marginLeft = '-' + parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100  / 2 + '%',
						$img = canvas.toDataURL(),
						n_tiles = x * y, // total number of tiles
						tiles = [],
						$tiles = {};
						
					for ( var i = 0; i < n_tiles; i++ ) {
						tiles.push('<div class="tile"/>');
					}
						 
					$tiles = $( tiles.join('') );
					// Hide original image and insert tiles in DOM
					$this.hide().after(
						$('<div class="modal-placeholder"/>').attr('style', $this.attr('style')).css({
							'opacity': 1,
							'height': height,
							'margin-left': '-' + (parseInt(width) / 2) + '%',
							'padding': 0,
							'width': parseInt(width) + '%',
							'top': $(window).scrollTop() + 100
						})
						.show(0)
						.append( $tiles )
					);
					// Adjust position
					$tiles.each(function(){
						var pos = $(this).position();
						
						console.log($this.outerHeight() / y + 'px');
						$(this).css({
							'border': 0,
							'backgroundPosition': -pos.left +'px ' + -pos.top + 'px',
							'width': $this.outerWidth() / x + 'px',
							'height': $this.outerHeight() / y + 'px',
							'background-image': 'url('+ $img +')'
						});
					});
					
				}
			});
			//$this.hide(0);
			var opts = $.extend($.fn.animation.defaults, options);
		}*/
	};
    $.fn.animation = function(style)
    {
        // Method calling logic
        if (animations[style])
        {
            return animations[style].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else
        {
            $.error('Animation style ' + animations + ' does not exist on jQuery.animation');
        }
    };
    
	$.fn.animation.defaults = {
		duration:350,
		direction: 'top',
		easing: 'swing'
	};
    $.fn.emodal.defaults = {
        theme: 1,
        onLoad: function (){},
        onClose: function (){},
        type: null,
		userHeight: null,
		userWidth: null,
		animation: 'fadeAndSlide',
		direction: 'bottom',
		duration: 350,
        overlayClose: false,
        escClose: false,
        closeDisabled: false
    };
    var modals = easymodal.modals;
    var themes = easymodal.themes;
    var settings = easymodal.settings;
	
    $(document).ready(function()
    {
		$('.modal').each(function()
		{
			var $this = $(this).css({visibility:'visible'}).hide(0);
			var modalId = $this.attr('id').split("-")[1];
			$this.emodal(modals[modalId]);
			$(document).on('click','.'+$this.attr('id'),function(e){
				e.preventDefault();
				e.stopPropagation();
				currentMousePos.x = e.pageX;
				currentMousePos.y = e.pageY;
				$this.emodal('open');
			});
			$('.'+$this.attr('id')).css('cursor','pointer');
		});
		if(easymodal.force_user_login)
		{
			$('#eModal-Login .close-modal, #eModal-Register .close-modal, #eModal-Forgot .close-modal').hide();
			$('#eModal-Register, #eModal-Forgot').append($('<a class="eModal-Login">Back to Login Form</a>').css('cursor','pointer'));
		}
		if(easymodal.autoOpen && !$.cookie("eModal-autoOpen-"+easymodal.autoOpen.id))
		{
			setTimeout(function(){
				$('#eModal-'+easymodal.autoOpen.id).emodal('open');
				var date = new Date();
				date.setTime(date.getTime() + (easymodal.autoOpen.timer * 24 * 60 * 1000));
				$.cookie("eModal-autoOpen-"+easymodal.autoOpen.id, true, { expires : date });
			},easymodal.autoOpen.delay);
		}
		if(easymodal.autoExit && !$.cookie("eModal-autoExit-"+easymodal.autoExit.id))
		{
			$('body').one('mouseleave',function(){
				if(easymodal.force_user_login)
				{
					return false;	
				}
				$this = $('#eModal-'+easymodal.autoExit.id).emodal('open');
				var date = new Date();
				date.setTime(date.getTime() + (easymodal.autoExit.timer * 24 * 60 * 1000));
				$.cookie("eModal-autoExit-"+easymodal.autoExit.id, true, { expires : date });
			});	
		}
		
	// Run our login ajax
	$('#eModal-Login form').on('submit', function(e) {
		$form = $(this);
		// Stop the form from submitting so we can use ajax.
		e.preventDefault();
		// Check what form is currently being submitted so we can return the right values for the ajax request.
		// Display our loading message while we check the credentials.
		$form.append('<p class="message notice">' + easymodal.loadingtext + '</p>');
		// Check if we are trying to login. If so, process all the needed form fields and return a faild or success message.
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: easymodal.ajaxLogin,
			data: {
				'action'     : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
				'username'   : $('#user_login',$form).val(),
				'password'   : $('#user_pass',$form).val(),
				'rememberme' : $('#rememberme',$form).is(':checked') ? true : false,
				'login'      : true,
				'easy-modal' : $('#safe_csrf_nonce_easy_modal',$form).val()
			},
			success: function(results) {
				// Check the returned data message. If we logged in successfully, then let our users know and remove the modal window.
				if(results.loggedin === true) {
					$('p.message',$form).removeClass('notice').addClass('success').text(results.message).show();
					setTimeout(function(){
						$('#eModal-Login').emodal('close',{onClose: function(){
							window.location.href = easymodal.redirecturl;
						}});
						
					},2000);
					
				} else {
					$('p.message',$form).removeClass('notice').addClass('error').text(results.message).show();
				}
			}
		});
	});
	// Run our register ajax
	$('#eModal-Register form').on('submit', function(e) {
		$form = $(this);
		// Stop the form from submitting so we can use ajax.
		e.preventDefault();
		// Check what form is currently being submitted so we can return the right values for the ajax request.
		// Display our loading message while we check the credentials.
		if(!$('p.message',$form).length)
		{
			$form.append('<p class="message notice">' + easymodal.loadingtext + '</p>');
		}
		// Check if we are trying to login. If so, process all the needed form fields and return a faild or success message.
		if($('#reg_pass',$form).length)
		{
			if($('#reg_pass',$form).val() != $('#reg_confirm',$form).val())
			{
				$('p.message',$form).removeClass('notice').addClass('error').html('Passwords don\'t match.');
				return;
			}
		}
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: easymodal.ajaxLogin,
			data: {
				'action'     : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
				'user_login'   : $('#reg_user',$form).val(),
				'user_email'   	 : $('#reg_email',$form).val(),
				'user_pass'   : $('#reg_pass',$form).val(),
				'register'      : true,
				'easy-modal' : $('#safe_csrf_nonce_easy_modal',$form).val()
			},
			success: function(results) {
				// Check the returned data message. If we logged in successfully, then let our users know and remove the modal window.
				if(results.loggedin === true) {
					$('p.message',$form).removeClass('notice').addClass('success').html(results.message);
					setTimeout(function(){
						$('#eModal-Login').emodal('close',{onClose: function(){
							window.location.href = easymodal.redirecturl;
						}});
						
					},2000);
					
				} else {
					$('p.message',$form).removeClass('notice').addClass('error').html(results.message);
				}
			}
		});
	});
	// Run our forgot password ajax
	$('#eModal-Forgot form').on('submit', function(e) {
		$form = $(this);
		// Stop the form from submitting so we can use ajax.
		e.preventDefault();
		// Check what form is currently being submitted so we can return the right values for the ajax request.
		// Display our loading message while we check the credentials.
		$form.append('<p class="message notice">' + easymodal.loadingtext + '</p>');
		// Check if we are trying to login. If so, process all the needed form fields and return a faild or success message.
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: easymodal.ajaxLogin,
			data: {
				'action'     : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
				'username'   : $('#forgot_login',$form).val(),
				'forgotten'      : true,
				'easy-modal' : $('#safe_csrf_nonce_easy_modal',$form).val()
			},
			success: function(results) {
				// Check the returned data message. If we logged in successfully, then let our users know and remove the modal window.
				if(results.loggedin === true) {
					$('p.message',$form).removeClass('notice').addClass('success').text(results.message).show();
					setTimeout(function(){
						$('#eModal-Login').emodal('close',{onClose: function(){
							window.location.href = easymodal.redirecturl;
						}});
						
					},2000);
					
				} else {
					$('p.message',$form).removeClass('notice').addClass('error').text(results.message).show();
				}
			}
		});
	});
	/*
		} else if ( form_id === 'register' ) {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: wpml_script.ajax,
				data: {
					'action'   : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
					'username' : $('#form #reg_user').val(),
					'email'    : $('#form #reg_email').val(),
					'register' : $('#form input[name="register"]').val(),
					'security' : $('#form #security').val()
				},
				success: function(results) {
					if(results.registerd === true) {
						$('.wpml-content > p.message').removeClass('notice').addClass('success').text(results.message).show();
						$('#register #form input:not(#user-submit)').val('');
					} else {
						$('.wpml-content > p.message').removeClass('notice').addClass('error').text(results.message).show();
					}
				}
			});
		} else if ( form_id === 'forgotten' ) {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: wpml_script.ajax,
				data: {
					'action'    : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
					'username'  : $('#form #forgot_login').val(),
					'forgotten' : $('#form input[name="register"]').val(),
					'security'  : $('#form #security').val()
				},
				success: function(results) {
					if(results.reset === true) {
						$('.wpml-content > p.message').removeClass('notice').addClass('success').text(results.message).show();
						$('#forgotten #form input:not(#user-submit)').val('');
					} else {
						$('.wpml-content > p.message').removeClass('notice').addClass('error').text(results.message).show();
					}
				}
			});
		} else {
			// if all else fails and we've hit here... something strange happen and notify the user.
			$('.wpml-content > p.message').text('Something  Please refresh your window and try again.');
		}
	});
		
		
		
		
		
		/*
		$.expr[':'].external = function (obj)
		{
			return !obj.href.match(/^mailto\:/) && (obj.hostname != location.hostname);
		};
		$('a:external').addClass('external eModal-Link').emodal(easymodal.modals['Link']);
        $('a[href$=".gif"], a[href$=".jpg"], a[href$=".png"], a[href$=".bmp"]').children('img').each(function ()
        {
            var anch = $(this).parents('a').addClass('eModal-Image');
            var url = $(anch).attr('href');
            $(anch).emodal(
            {
                url: url,
                theme: '1',
                type: 'Image'
            });
        });
		if(settings.autoOpen == 'true')
		{
			setTimeout(function(){
				$('#emModal-'+settings.autoOpenId).emodal('open');
			}, settings.autoOpenDelay);
		}
		*/
    })
})(jQuery);