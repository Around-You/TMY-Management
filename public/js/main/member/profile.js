var tmy_member_list = {
	init : function() {
		$('.chosen-select').chosen({allow_single_deselect:true}); 

		$('#modal-form').on('shown.bs.modal', function () {
			$(this).find('.chosen-container').css('width' , '100%');
			$(this).find('.chosen-container').each(function(){
				$(this).find('a:first-child').css('width' , '100%');
				$(this).find('.chosen-drop').css('width' , '100%');
				$(this).find('.chosen-search input').css('width' , '100%');
			});
		})
	},
	bindEvent: function(){
		$(".goods-chosen-select").on('change', function(){
			
		})
	},
};

$(function() {
	tmy_member_list.init();
});
