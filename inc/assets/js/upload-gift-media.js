jQuery(document).ready(function($) {
    var mediaUploader;
	var $last_click ='';
	  $(document).on('click','.upload_image_button',function(e) {
		  $last_click = $(this);
   		  e.preventDefault();
		  // If the uploader object has already been created, reopen the dialog
	      if (mediaUploader) {
    		  mediaUploader.open();
		      return;
		    } 
		    // Extend the wp.media object
		    mediaUploader = wp.media.frames.file_frame = wp.media({
		      title: 'Choose Image',
		      button: {
			      text: 'Choose Image'
			    }, multiple: false });

			    // When a file is selected, grab the URL and set it as the text field's value
			    mediaUploader.on('select', function() {
   				    attachment = mediaUploader.state().get('selection').first().toJSON();
					var oldUrl = attachment.url;
					var url = WPOPTION.siteurl;
					var newurl = oldUrl.replace(url, '');
					$last_click.closest('.upload_image_button_url').find('.upload_image_button_image').val(newurl);
			    	$last_click='';
			    });
	
		    // Open the uploader dialog
		    mediaUploader.open();
	  });
});	  
jQuery(document).ready(function($){
	$("#add_more_gift").click(function(){
		var trappend = '<tr><td><p>Title:</p><input type="text" name="gift_title[]" id="gift_title" value="" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Gift Title"></td><td><p>Link US: </p><input type="text" name="gift_link[]" id="gift_link" value="" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Gift Link"></td><td><p>Link UK: </p> <input type="text" name="gift_link_uk[]" id="gift_link_uk" value="" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Link UK"></td><td><p>Link Inbound: </p> <input type="text" name="gift_link_inbound[]" id="gift_link_inbound" value="" style="height:40px; width:100%;" class="amazon-form-control" placeholder="Link Inbound"></td><td class="upload_image_button_url"><p>Image: </p><input type="text" name="gift_image[]" id="gift_image" value="" style="height:40px; width:80%;" class="amazon-form-control upload_image_button_image" placeholder="Gift Image"><input id="upload_image_button" class="upload_image_button" type = "button" value = "Upload"></td><td><p>Description: </p><textarea name="gift_description[]" id="gift_description" style="height:80px; width:100%;" class="amazon-form-control" placeholder="Gift Description"></textarea></td><td><a href="javascript:void(0)" class="btn btn-default remove_more_gift">Remove</a></td></tr>'; 
		$("#gifttr").append(trappend);
	});
				
	$(document).on('click','.remove_more_gift',function(){
		$(this).closest('td').parent('tr').remove();	
	});
});

jQuery(document).ready(function ($){
    var acs_action = 'get_post_name';
	$(document).on('focus', '.search_gift_guide', function(){
		$(this).autocomplete({
			source: function(req, response){
				var term = $('.search_gift_guide').val();
				var dataString =  'action=get_post_name&term='+term;
				 $.ajax({
					 	type:'POST',
						url: ajax_object_auto.ajax_url,
						data: dataString,
						dataType: "json",
						success: function( data ) {
							response( $.map( data.myData, function( item ) {
								return {
									label: item.label,
									value: item.label,
									ID: item.value
								}
							}));
						}
					});
			},
			select: function(event, ui) {
				$(document).find('.hidden_post_value').val(ui.item.ID);
				//console.log(event);
				//window.location.href=ui.item.link;
			},
			minLength: 3,
		});
	});
});
