var tmy_member_list = {
	init : function() {
		$('.chosen-select').chosen({
			allow_single_deselect : true
		});
		$('.date-picker').datepicker({
            language: 'zh',
			autoclose: true,
			todayHighlight: true
		}).next().on('click', function(){
			$(this).prev().focus();
		});
		$('#modal-form').on('shown.bs.modal', function() {
			$(this).find('.chosen-container').css('width', '100%');
			$(this).find('.chosen-container').each(function() {
				$(this).find('a:first-child').css('width', '100%');
				$(this).find('.chosen-drop').css('width', '100%');
				$(this).find('.chosen-search input').css('width', '100%');
			});
	
		});
		this.bindEvent();
	},
	bindEvent : function() {
//		$(".time-card-only").hide();
//		$(".count-card-only").hide();
		$(".goods-chosen-select").on('change', function() {
			var type = '';
			$(".goods-chosen-select option:selected").each(function() {
				type = $(this).data('goods-type');
			});
			if (type == '时间卡') {
				$(".time-card-only").show();
				$(".count-card-only").hide();
		
			} else {
				$(".time-card-only").hide();
				$(".count-card-only").show();
			}
		});
		$("#btn-submit-buy-modal").on("click", function() {
			var data = $("#buy_goods_form").serialize();
			$.getJSON("/sale/buyMemberCard", data, function(result) {
				console.log(result);
			});
		});

		$("#checkbox-start").on("change", function() {
			if ($(this).prop('checked')) {
				$(".time-card-only .date-picker").prop("disabled", false);
			} else {
				$(".time-card-only .date-picker").prop("disabled", true);
			}
		});
	},
};

$(function() {
	tmy_member_list.init();
});
