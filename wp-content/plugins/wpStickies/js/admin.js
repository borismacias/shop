(function( $ ) {

	$.fn.customCheckbox = function() {

		return this.each(function() {

			// Get the original element
			var el = this;

			// Hide the checkbox
			$(this).hide();

			// Create replacement element
			var rep = $('<a href="#"><span></span></a>').addClass('wps-checkbox').insertAfter(this);

			// Set default state
			if($(this).is(':checked')) {
				$(rep).addClass('on');
			} else {
				$(rep).addClass('off');
			}

			// Click event
			$(rep).click(function(e) {

				e.preventDefault();

				if( $(el).is(':checked') ) {
					$(el).prop('checked', false);
					$(rep).removeClass('on').addClass('off');
				} else {
					$(el).prop('checked', true);
					$(rep).removeClass('off').addClass('on');
				}
			});
		});
	};

})( jQuery );


function lsSetCookie(c_name,value,exdays) {
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function lsGetCookie(c_name) {
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++) {
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name) {
			return unescape(y);
		}
	}
}

var wpStickies = {

	selectMainTab : function(el) {

		// Remove highlight from the other tabs
		jQuery('#wps-main-nav-bar a').removeClass('active');

		// Highlight selected tab
		jQuery(el).addClass('active');

		// Hide other pages
		jQuery('#wps-pages .wps-page').removeClass('active');

		// Show selected page
		jQuery('#wps-pages .wps-page').eq( jQuery(el).index() ).addClass('active')

		// Set cookie
		lsSetCookie('wps-main-tab', jQuery(el).index(), 100);
	},

	selectStickyTab : function(el) {

		// Close other layers
		jQuery('#wps-stickies-tabs a').removeClass('active');
		jQuery('.wps-stickies-box').removeClass('active');

		// Open new layer
		jQuery(el).addClass('active');
		jQuery('.wps-stickies-box').eq( jQuery(el).index() ).addClass('active');

		// Set cookie
		lsSetCookie('wps-sticky-tab', jQuery(el).index(), 100);
	},

	openPreview : function(el) {

	},

	acceptSticky : function(el) {

		// Get params
		var params = jQuery(el).attr('rel').split(',');

		// Post query
		jQuery.post( ajaxurl, { action : params[0], id : params[1] } );

		// Fade out
		jQuery(el).closest('tr').fadeOut(500, function() {

			// Create placeholder if needed
			if(jQuery(el).closest('table').find('tr').length < 3) {

				// Append success message
				var replacement = jQuery(el).closest('tr').after( jQuery('<tr>', { 'class' : 'empty' })
					.append( jQuery('<td>', { 'colspan' : '6', 'text' : 'There are no more pending stickies at the moment.' }))
				);

				// Animate changes
				jQuery(el).closest('table').find('tr.empty').hide().fadeIn(500);

				// Remove every table row
				jQuery(el).closest('tr').prependTo('.wpstickies-latest-table').show().find('.created').attr('class', 'modified');

				// Remove placeholder if any
				jQuery(el).closest('tbody').find('tr.empty').remove();

				// Get actions holder TD
				var parent = jQuery(el).parent();

				// Remove previous actions
				jQuery(el).closest('tr').find('.wpstickies-actions a').remove();

				// Add new actions
				parent.append( jQuery('<a>', { 'href' : '#', 'class' : 'remove', 'title' : 'Remove', 'rel' : 'wpstickies_remove,'+params[1]+'' } ));
			}
		});
	},

	removeSticky : function(el) {

		// Get params
		var params = jQuery(el).attr('rel').split(',');

		// Post query
		jQuery.post( ajaxurl, { action : params[0], id : params[1] } );

		// Fade out
		jQuery(el).closest('tr').fadeOut(500, function() {

			// Create placeholder if needed
			if(jQuery(el).closest('table').find('tr').length < 3) {

				// Info msg
				if(jQuery(el).hasClass('reject')) {
					var msg = 'There are no more pending stickies at the moment.';
				} else {
					var msg = 'No stickies yet.';
				}

				// Append success message
				var replacement = jQuery(el).closest('tr').after( jQuery('<tr>', { 'class' : 'empty' })
					.append( jQuery('<td>', { 'colspan' : '6', 'text' : msg }))
				);

				// Animate changes
				jQuery(el).closest('table').find('tr.empty').hide().fadeIn(500);
			}

			// Insert it into the removed stickies
			jQuery(el).closest('tr').prependTo('.wpstickies-restore-table tbody').show().find('.created').attr('class', 'modified');

			// Get actions holder TD
			var parent = jQuery(el).parent();

			// Get actions holder TD
			var parent = jQuery(el).parent();

			// Replace action buttons
			jQuery(el).closest('tr').find('.wpstickies-actions a').remove();

			// Append restore action
			parent.append( jQuery('<a>', { 'href' : '#', 'class' : 'restore', 'rel' : 'wpstickies_restore,'+params[1]+'', 'title' : 'Restore' }));
			parent.append( jQuery('<a>', { 'href' : '#', 'class' : 'delete', 'rel' : 'wpstickies_delete,'+params[1]+'', 'title' : 'Delete permanently' }));
		});
	},

	restoreSticky : function(el) {

		// Get params
		var params = jQuery(el).attr('rel').split(',');

		// Post query
		jQuery.post( ajaxurl, { action : params[0], id : params[1] } );

		// Fade out
		jQuery(el).closest('tr').fadeOut(500, function() {

			// Create placeholder if needed
			if(jQuery(el).closest('table').find('tr').length < 3) {

				// Append success message
				var replacement = jQuery(el).closest('tr').after( jQuery('<tr>', { 'class' : 'empty' })
					.append( jQuery('<td>', { 'colspan' : '6', 'text' : 'There are no more removed stickies.' }))
				);

				// Animate changes
				jQuery(el).closest('table').find('tr.empty').hide().fadeIn(500);
			}

			// Insert it into the removed stickies
			jQuery(el).closest('tr').prependTo('.wpstickies-latest-table tbody').show();

			// Remove notification if any
			jQuery(el).closest('table').find('tr.empty').remove();

			// Get actions holder TD
			var parent = jQuery(el).parent();

			// Replace action buttons
			jQuery(el).closest('tr').find('.wpstickies-actions a').remove();

			// Append restore action
			parent.append( jQuery('<a>', { 'href' : '#', 'class' : 'remove', 'title' : 'Remove', 'rel' : 'wpstickies_remove,'+params[1]+'' } ));
		});
	},

	deleteSticky : function(el) {

		// Get params
		var params = jQuery(el).attr('rel').split(',');

		// Post query
		jQuery.post( ajaxurl, { action : params[0], id : params[1] } );

		// Fade out
		jQuery(el).closest('tr').fadeOut(500, function() {

			// Create placeholder if needed
			if(jQuery(el).closest('table').find('tr').length < 3) {

				// Append success message
				var replacement = jQuery(el).closest('tr').after( jQuery('<tr>', { 'class' : 'empty' })
					.append( jQuery('<td>', { 'colspan' : '6', 'text' : 'There are no more removed stickies.' }))
				);

				// Animate changes
				jQuery(el).closest('table').find('tr.empty').hide().fadeIn(500);
			}
		});
	},

	submit : function(el) {

		// Search and rewrite the name attribute of form elements
		jQuery(el).find('input, select').each(function() {

			// Skip this form element
			if(jQuery(this).attr('name') == 'posted') {
				return true;
			}

			// Rewrite name ATTR
			jQuery(this).attr('name', 'wpstickies-options['+jQuery(this).attr('name')+']');
		});

		// Post the form
		jQuery.post( jQuery(el).attr('action'), jQuery(el).serialize(), function(data) {
			if(data != 'SUCCESS') {
				alert(data);
			} else {
				window.location.reload(true);
			}
		});
	}
};

jQuery(document).ready(function() {

	// Settings: checkboxes
	jQuery('.wps-settings :checkbox').customCheckbox();

	// wpStickies selectors
	jQuery('.wps-settings input.selector').tagsInput({
		width : '600px',
		height : 'auto',
		defaultText : 'add a rule'
	});

	jQuery('.wps-settings input.custom_roles').tagsInput({
		width : '600px',
		height : 'auto',
	});

	// Main tab bar page select
	jQuery('#wps-main-nav-bar a:not(.unselectable)').click(function(e) {
		e.preventDefault();
		wpStickies.selectMainTab( this );
	});

	// Restore last main tab
	if(typeof lsGetCookie('wps-main-tab') != "undefined") {
		jQuery('#wps-main-nav-bar a').eq( parseInt(lsGetCookie('wps-main-tab')) ).click();
	}

	// Documentation menu item
	jQuery('#wps-main-nav-bar a.support').click(function(e) {
		e.preventDefault();
		jQuery('#contextual-help-link').click();
	});

	// Select sticky tab
	jQuery('#wps-stickies-tabs a:not(.unsortable)').click(function(e) {
		e.preventDefault();
		wpStickies.selectStickyTab(this);
	});

	// Restore last main tab
	if(typeof lsGetCookie('wps-sticky-tab') != "undefined") {
		jQuery('#wps-stickies-tabs a').eq( parseInt(lsGetCookie('wps-sticky-tab')) ).click();
	}

	// Preview
	jQuery('a[href*="wpstickies_preview"]').click(function(e) {
		e.preventDefault();
		tb_show('wpStickies image preview', jQuery(this).attr('href') + '&type=image&amp;TB_iframe=true&width=900&height=700');
	});

	// Accept
	jQuery('#wps-stickies').on('click', '.accept', function(e) {
		e.preventDefault();
		wpStickies.acceptSticky(this);
	});

	// Reject + Remove
	jQuery('#wps-stickies').on('click', '.reject, .remove', function(e) {
		e.preventDefault();
		wpStickies.removeSticky(this);
	});

	// Restore
	jQuery('#wps-stickies').on('click', '.restore', function(e) {
		e.preventDefault();
		wpStickies.restoreSticky(this);
	});

	// Delete
	jQuery('#wps-stickies').on('click', '.delete', function(e) {
		e.preventDefault();
		wpStickies.deleteSticky(this);
	});

	// Save
	jQuery('#wps-form').submit(function(e) {
		e.preventDefault();
		wpStickies.submit(this);
	});

	// Auto-update
	jQuery('.wps-auto-update').submit(function(e) {

		// Prevent browser default submission
		e.preventDefault();

		// Set progress text
		jQuery('.wps-auto-update tfoot span').text('Validating ...').css('color', '#333');

		// Post it
		jQuery.post( ajaxurl, jQuery(this).serialize(), function(data) {

			// Parse data
			data = jQuery.parseJSON(data);

			// Check success
			jQuery('.wps-auto-update tfoot span').text(data['message']);

			// Check success
			if(data['success'] == true) {
				jQuery('.wps-auto-update tfoot span').css('color', '#4b982f');
			} else {
				jQuery('.wps-auto-update tfoot span').css('color', '#c33219');
			}
		});
	});
})