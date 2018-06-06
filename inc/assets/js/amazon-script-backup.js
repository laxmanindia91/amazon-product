jQuery(document).ready(function($){
var check_uk_search='false';
	$('.amazonmedia').click(function(){
		$('#amazon_model_popup').css('display','block');
	});
	$('body').on('click','.close_amazon_model',function(e) { 
        $('#amazon_model_popup').css('display','none');
    });
	
	$('.amazonmedia_uk').click(function(){
		//alert('uk search');
		$('#amazon_model_popup_uk').css('display','block');  
	});
		$('body').on('click','.close_amazon_model_uk',function(e) { 
        $('#amazon_model_popup_uk').css('display','none');
    });	
	
	$('#add_pros').click(function(e) {
        $('.amazon_pros_section').append('<div class="remove_section"><br><input type="text" name="amazon_pros[]" style="height:40px; width:97%;" class="amazon-form-control" id="" placeholder="Add Pros"><a href="javascript:void(0)" class="remove_field"><span class="dashicons dashicons-no"></span></a></div>');
    });
	$('#add_cons').click(function(e) {
        $('.amazon_cons_section').append('<div class="remove_section"><br><input type="text" name="amazon_cons[]" id="amazon_cons" style="height:40px; width:97%;" class="amazon-form-control" placeholder="Add Cons"><a href="javascript:void(0)" class="remove_field"><span class="dashicons dashicons-no"></span></a></div>');
    });
	$('body').on('click','.remove_field',function(e) { 
        $(this).closest('.remove_section').remove();
    });
	
	$('#amazon-search-submit').click(function(e) {
		 var $button = $(this);
		$('.load-spinner').fadeIn('fast');
		$('.showing_products').removeClass('active');	
		$('.amazon-search-result-error').hide('slow');
		$('#append_product').html('');
		$('.displaying-num').html('');
        if($('#amazon-search-keywords').val()=='')
		{
			$('.amazon-search-result-error').show('slow').html('There was an issue with your search and no items were found.');
			$('.load-spinner').fadeOut(3000);
			return false;
		}
		var dataString = $(this).closest('form').serialize();
		var url = ajax_object.ajax_url;
		$.ajax({
			type:'POST',
			url:url,
			data:dataString,
			success: function(response)
			{
				
				$('#pages_show').val('1');
				var result = jQuery.parseJSON(response);
				if(result.message!='')
				{
					$('.amazon-search-result-error').show('slow').html(result.message);
					$('.amazon-search-results').fadeOut('slow');
				}else
				{
					$('.amazon-search-results').fadeIn('slow');
				}
				if(result.pagination!='')
				{
					$('.pagination').html(result.pagination);
				}else
				{
					$('.pagination').html('');
				}
				if(result.showing!='')
				{
					$('.displaying-num').html(result.showing);
				}else
				{
					$('.displaying-num').html('');
				}
				$('#append_product').html(result.html);
				$('.load-spinner').fadeOut(3000);
			},
			error: function()
			{
				$('.load-spinner').fadeOut(3000);
				console.log('Something Went Wrong');
				return false;
			}
		});
    });
	$(document).on('click','.showing_products',function()
	{
		$('.showing_products').removeClass('active');
		$(this).addClass('active');
		var pages = $(this).data('pages');
		$('#pages_show').val(pages);
		$('#amazon-search-submit').trigger('click');
	});
	$(document).on('click','.amazon-select-product',function()
	{
		var hide_image_popup = $(this).data('hide_image_popup');
		$('#'+hide_image_popup).fadeOut('fast');
		var image_popup_id = $(this).data('image_popup_id');
		$('#'+image_popup_id).fadeIn('slow');
	});
	$(document).on('click','.amazon-cancel-product',function()
	{
		var image_popup_id = $(this).data('image_popup_id');
		$('#'+image_popup_id).fadeOut('fast');
		var hide_image_popup = $(this).data('hide_image_popup');
		$('#'+hide_image_popup).fadeIn('slow');

	});
	$(document).on('click','.insert_product',function() {
		//alert($(this).data('insertlink'));
		
		var image_popup_id = $(this).data('image_popup_id');
		var hide_image_popup = $(this).data('hide_image_popup');
        var insertTitle = $(this).data('inserttitle');
		var insertPrice = $(this).data('insertprice');
		var insertLink  = $(this).data('insertlink');
		 
		var searchurl  = $(this).data('searchurl');
		var searchtitle  = $(this).data('searchtitle');

		var insertimagename = $(this).data('insertimagename');
		var insertimage = $(this).closest('tr').find('.amazon-popup-state-image-choices-choice-selector:checked').val();
		var checkbox = $(this).closest('tr').find('.amazon-popup-state-image-choices-choice-selector:checked');
		if(checkbox.length==0)
		{
			alert('Please choose product image');
			return false;
		}
		if(insertimage==undefined || insertimage=='' || insertimage=='undefined')
		{
			alert('Please choose product image');
			return false;
		}
		var dataString = 'action=amazon_save_image&urlname='+insertimagename+'&url='+insertimage;
		var url = ajax_object.ajax_url;
		$.ajax({
			type:'POST',
			url:url, 
			data:dataString,
			success: function(response)
			{
				
				if(check_uk_search)
				{
					$('#amazon_link_uk').val(insertLink);
				}
				else{
					
				var result = jQuery.parseJSON(response);
				$('.amazon_product_image_show').fadeIn().attr('src',result.url);
				$('#amazon_product_image').val(result.url);
				$('#title').val(insertTitle);
				$('#amazon_insertPrice').val(insertPrice);
				$('#amazon_link').val(insertLink);
				$('#amazon_insertPrice_show').html(insertPrice);
				$('#amazon_searchurl').val(searchurl);
				$('#amazon_searchtitle').val(searchtitle);
				$('#'+image_popup_id).fadeOut('fast');
				$('#'+hide_image_popup).fadeIn('slow');
				$('#amazon_model_popup').css('display','none');
				
				}
			},
			error: function()
			{
				console.log('Something Went Wrong');
			}
		});
    });
	
	// uk based search function
	$('#amazon-search-submit-uk').click(function(e) {
		//var ukdataStringold = $(this).closest('form').serialize();
		check_uk_search = 'true';
		$('.load-spinner').fadeIn('fast');
		$('.showing_products').removeClass('active');	
		$('.amazon-search-result-error').hide('slow');
		$('#append_product_uk').html('');
		$('.displaying-num-uk').html('');
        if($('#amazon-search-keywords-uk').val()=='')
		{
			$('.amazon-search-result-error').show('slow').html('There was an issue with your search and no items were found.');
			$('.load-spinner').fadeOut(3000);
			return false;
		}
		var ukdataString = $("#amazon-popup-form_uk").serialize();
		var url = ajax_object.ajax_url;
		//alert(ukdataString);
		$.ajax({
			type:'POST',
			url:url,
			data:ukdataString,
			success: function(response)
			{
			//console.log(response);
			//alert('success: ' + response);
			$('#pages_show_uk').val('1');
				var result = jQuery.parseJSON(response);
				//alert('a:' + result);
				
				if(result.message!='')
				{
					$('.amazon-search-result-error').show('slow').html(result.message);
					$('.amazon-search-results-uk').fadeOut('slow');
				}else
				{
					$('.amazon-search-results-uk').fadeIn('slow');
				}
				if(result.pagination!='')
				{
					$('.pagination').html(result.pagination);
				}else
				{
					$('.pagination').html('');
				}
				if(result.showing!='')
				{
					$('.displaying-num').html(result.showing);
				}else
				{
					$('.displaying-num').html('');
				}
				$('#append_product_uk').html(result.html);
				$('.load-spinner').fadeOut(3000);	
			}
		});
		});
}); 