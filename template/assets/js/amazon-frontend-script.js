jQuery(document).ready(function($) {
	$('.dynamic_post_home_view').find('#toc_container').remove();
	$('.dynamic_gift_guide_homeview').find('#toc_container').remove();
	$('.dyncmiclink_product').find('#toc_container').remove();
	$('.overlay-control').click(function(e) {
        $(this).closest('.content').toggleClass('amazon-height');
    });
	$('.meta-header .tab').click(function(e) {
		$(this).closest('.item-list').find('.button.tab').removeClass('active')
       	$(this).addClass('active');
		var meta = $(this).data('meta');
		$(this).closest('.item-list').find('.panel').addClass('hidden');
		$(this).closest('.item-list').find('.panel.'+meta).removeClass('hidden');
    });
	

	$(function(){
	  $('.dyncmiclink_product').each(function(index, element) {
		var ids = $(this).attr('id');
		var return_product_url = $(this).data('return_product_url');
		var dataString = 'action=amazon_link_request&return_product_url='+return_product_url;
		var url = ajax_object_frontend.ajax_url;
		$.ajax({
			type:'POST',
			url:url,
			data:dataString,
			success: function(response)
			{
				var result = jQuery.parseJSON(response);
				$('#'+ids).find('.title-wrap h2').html(result.titel_link);
				$('#'+ids).find('.image-wrap p.image').html(result.image_link);
				$('#'+ids).find('.image-wrap .more-by').html(result.more_by_link);
				$('#'+ids).find('.amazon_dynamic_url').html(result.dynamic_button_url);
				$('#'+ids).find('.read_more').html(result.read_more);
			},
			error: function()
			{
				console.log('Something Went Wrong');
			}		
		});
	  });
	});
	
	
	$(function(){
		$('.dynamic_gift_guide').each(function(index, element) {
			var ids = $(this).attr('id');
			var return_product_url = $(this).data('return_product_url');
			var column = $(this).data('product_column');
			var sty = $(this).data('button_style');
			var site = $(this).data('which_site');
			var dataString = 'action=gift_link_request&post_id='+return_product_url+'&col='+column+'&style='+sty+'&site='+site;
			var url = ajax_object_frontend.ajax_url;
			$.ajax({
				type:'POST',
				url:url,
				data:dataString,
				success: function(response)
				{
					var result = jQuery.parseJSON(response);
					$('#'+ids).html(result.html);
				},
				error: function()
				{
					console.log('Something Went Wrong');
				}		
			});
		});
	});
	
	$(function(){
		$('.dynamic_post_gift_guide').each(function(index, element) {
			var ids = $(this).attr('id');
			var return_product_url = $(this).data('return_product_url');
			var column = $(this).data('product_column');
			var sty = $(this).data('button_style');
			var site = $(this).data('which_site');
			var dataString = 'action=gift_link_request_post&post_id='+return_product_url+'&col='+column+'&style='+sty+'&site='+site;
			var url = ajax_object_frontend.ajax_url;
			$.ajax({
				type:'POST',
				url:url,
				data:dataString,
				success: function(response)
				{
					var result = jQuery.parseJSON(response);
					$('#'+ids).html(result.html);
				},
				error: function()
				{
					console.log('Something Went Wrong');
				}		
			});
		});
	});
	
	// Gift Guide and Post view
	$(function(){
		$('.dynamic_gift_guide_with_post').each(function(index, element) {
			var ids = $(this).attr('id');
			var return_product_url = $(this).data('return_product_url');
			var column = $(this).data('product_column');
			var sty = $(this).data('button_style');
			var site = $(this).data('which_site');
			var dataString = 'action=gift_link_request_with_post&post_id='+return_product_url+'&col='+column+'&style='+sty+'&site='+site;
			var url = ajax_object_frontend.ajax_url;
			$.ajax({
				type:'POST',
				url:url,
				data:dataString,
				success: function(response)
				{
					var result = jQuery.parseJSON(response);
					$('#'+ids).html(result.html);
				},
				error: function()
				{
					console.log('Something Went Wrong');
				}		
			});
		});
	});

	// Gift Guid Home view (gift-guide)
	$(function(){
		$('.dynamic_gift_home_view').each(function(index, element) {
			var ids = $(this).attr('id');
			var return_product_url = $(this).data('return_product_url');
			var column = $(this).data('product_column');
			var sty = $(this).data('button_style');
			var site = $(this).data('which_site');
			var more = $(this).data('more_text');
			var count = $(this).data('word_count');
			var afiliatetext = $(this).data('afiliate-button-text');
			var inboundtext = $(this).data('inbound-button-text');
			
			var dataString = 'action=gift_guide_dyn_gift_link&post_id='+return_product_url+'&col='+column+'&style='+sty+'&site='+site+'&count='+count+'&afiliatetext='+afiliatetext+'&inboundtext='+inboundtext+'&moretext='+more;
			var url = ajax_object_frontend.ajax_url;
			$.ajax({
				type:'POST',
				url:url,
				data:dataString,
				success: function(response)
				{
					var result = jQuery.parseJSON(response);
					$('#'+ids).html(result.html);
				},
				error: function()
				{
					console.log('Something Went Wrong');
				}		
			});
		});
	});
	
	// Gift Guid Home view (post)
	$(function(){
		$('.dynamic_post_home_view').each(function(index, element) {
			var ids = $(this).attr('id');
			var return_product_url = $(this).data('return_product_url');
			var column = $(this).data('product_column');
			var sty = $(this).data('button_style');
			var site = $(this).data('which_site');
			var more = $(this).data('more_text');
			var count = $(this).data('word_count');
			var afiliatetext = $(this).data('afiliate-button-text');
			var inboundtext = $(this).data('inbound-button-text');
			
			var dataString = 'action=gift_guide_dyn_link&post_id='+return_product_url+'&col='+column+'&style='+sty+'&site='+site+'&count='+count+'&afiliatetext='+afiliatetext+'&inboundtext='+inboundtext+'&moretext='+more;
			var url = ajax_object_frontend.ajax_url;
			$.ajax({
				type:'POST',
				url:url,
				data:dataString,
				success: function(response)
				{
					var result = jQuery.parseJSON(response);
					$('#'+ids).html(result.html);
				},
				error: function()
				{
					console.log('Something Went Wrong');
				}		
			});
		});
	});
});