var $ = jQuery, file_frame = false, image_frame = false;

jQuery(document).ready(function() {

	jQuery("#slider_images").sortable({

		axis: 'y',

		handle: 'img'

	});

	jQuery('.deleteslide').live("click", function() {

		var c = confirm('Are you sure?');

		if(!c) return false;

		

		jQuery(this).parent().parent().parent().slideUp('slow', function() {

			jQuery(this).remove();

		})

	});

});

function validateURL(value) {
	return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
}

function add_super_image_url() {
	var superurl = prompt("Please enter Image URL", "");
	
	if(superurl!=null && superurl!='') {
		if(validateURL(superurl)) {
			var imgdata = new Array();
			imgdata[0] = new Array();
			imgdata[0]['sizes'] = new Array();
			imgdata[0]['sizes']['thumbnail'] = new Array();
			imgdata[0]['sizes']['thumbnail']['url'] = superurl;
			imgdata[0]['title'] = '';
			imgdata[0]['url'] = superurl;
			imgdata[0]['id'] = '';
			appendImages(imgdata);
		}else {
			alert("Invalid URL");
		}
	}
}

function appendImages(images) {

	var $html = '', rnd;

	for(i=0;i<images.length;i++) {

		rnd = Math.floor((Math.random()*100000)+1);

		$html += '<ul class="sprcrsl-admin img-panel"><li class="w2 txt-center">';
		
		$html += '<div><img width="80%" class="fl pad12 thumbimg" src="'+get_thumb(images[i]['sizes'])+'" /><span class="note">Move Image</span></div><b>'+images[i]['title']+'</b></li>';

		$html += '<li class="w8">';
		
		$html += '<div class="clr">';
		
		$html += '<div class="deleteslide">Delete</div>';
		
		$html += '<input type="hidden" name="images[image][]" value="'+images[i]['url']+'" />';
		
		$html += '<input type="hidden" name="images[title][]" value="'+images[i]['title']+'" />';
		
		$html += '<input type="hidden" name="images[id][]" value="'+images[i]['id']+'" />';
		
		$html += '</div>';

		$html += '<p>';
		
		$html += '<label for="images'+rnd+'" class="w30pc">Lightbox URL:</label>';

		$html += '<input type="text" class="ip1 w50pc" id="images'+rnd+'" name="images[lightboxurl][]" />';

		$html += '<a href="javascript: void(0);" onclick="select_image(\'images'+rnd+'\');" class="button">Image</a>';

		$html += '</p><p>';
		
		$html += '<label for="linkurl'+i+'" class="w30pc">Link URL:</label> <input id="linkurl'+i+'" type="text" class="ip1 w40pc" name="images[linkurl][]" />'; 

		$html += 'Target<select name="images[target][]"><option value="_self">_self</option><option value="_new">_new</option><option value="_parent">_parent</option></select>';

		$html += '</p><p>';
		
		$html += '<label for="caption'+i+'" class="w30pc">Caption:</label>';

		$html += '<textarea id="caption'+i+'" rows="1" name="images[caption][]" cols="60" class="w70pc"></textarea>';
		
		$html += '</p>';
		
		$html += '</li></ul>';

	}

	jQuery('#slider_images').prepend($html);

}



function get_thumb(img) {
	
	if(img['thumbnail']) {
		return img['thumbnail']['url'];
	}else if(img['medium']) {
		return img['medium']['url'];
	}else {
		return img['full']['url'];
	}
}



function media_lib_img() {

	if(file_frame) {

		file_frame.open();

		return;

	}



	file_frame = wp.media.frames.file_frame = wp.media( {

		title : 'Select Images',

		button : {

			text : 'Select Images'

		},

		library: {

        	type: 'image'

    	},

		multiple : true

	});

	file_frame.on('select', function() {

		attachment = file_frame.state().get('selection').toJSON();
		
		appendImages(attachment);

	});

	

	file_frame.open();

}

var imgtarget;

function select_image(tar) {

	imgtarget = tar;

	if(image_frame) {

		image_frame.open();

		return;

	}



	image_frame = wp.media.frames.file_frame = wp.media( {

		title : 'Select Image',

		button : {

			text : 'Insert Image'

		},

		library: {

        	type: 'image'

    	},

		multiple : true

	});

	image_frame.on('select', function() {

		attachment = image_frame.state().get('selection').toJSON();
		
		jQuery('#'+imgtarget).val(attachment[0].url);

	});

	

	image_frame.open();

}