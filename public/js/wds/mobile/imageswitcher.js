var imageswitcher = {
	init: function(){
		$('.imageswitcher .thumbnails-col img ').on('click', function(e){
		    e.preventDefault();
		    e.stopPropagation();
			var thumb = this;
			$('.imageswitcher .thumbnails-col img').removeClass('thumb-active');
			$(this).addClass('thumb-active');
			$(this).parents('.imageswitcher').find('.image-col img').attr('src', $(this).attr('data-fullimage'));
		});
	},	
};
$(function(){
	imageswitcher.init();
})
