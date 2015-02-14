var tmy_member_list = {
	init : function() {
	
		this.bindEvent();
	},
	bindEvent : function() {
		var me = this;
		$(".time-card-only").hide();
		$(".count-card-only").hide();
		$("#goods_id").on('change', function() {
			var type = '';
			var count = 0;
			$("#goods_id option:selected").each(function() {
				type = $(this).data('goods-type');
				count = $(this).data('goods-count');
			});
			if (type == '时间卡') {
				$(".time-card-only").show();
				$(".count-card-only").hide();
		
			} else {
				$(".time-card-only").hide();
				$(".count-card-only").show();
				$("#form-count").val(count);
			}
		});
		$("#btn-submit-buy-modal").on("click", function() {
			var data = $("#buy_goods_form").serialize();
			$.getJSON("/sale/buyMemberCard", data, function(result) {
				console.log(result.status == '1');
				if(result.status == '1'){
					$('#modal-form').modal('hide');
					$memberGoods.draw();
				}
			});
		});

		$("#checkbox-start").on("change", function() {
			if ($(this).prop('checked')) {
				$(".time-card-only .date-picker").prop("disabled", false);
			} else {
				$(".time-card-only .date-picker").prop("disabled", true);
			}
		});
		$('#modal-form').on('shown.bs.modal', function(event) {
			$(this).find('.chosen-container').css('width', '100%');
			$(this).find('.chosen-container').each(function() {
				$(this).find('a:first-child').css('width', '100%');
				$(this).find('.chosen-drop').css('width', '100%');
				$(this).find('.chosen-search input').css('width', '100%');
			});
			me.initMemberGoodsForm(event);
		});
	},
	initMemberGoodsForm: function(event){
		console.log(event.relatedTarget);
		var relatedTarget = event.relatedTarget;
		$('#buy_goods_form')[0].reset();
		if( $(relatedTarget).data("id") != undefined ){
			var rowData = $memberGoods.row("#" + $(relatedTarget).data("id")).data();
			console.log(rowData);
			
			$('#goods_id option[value="' + rowData.goods_id + '"]').prop('selected', true);
			$('#goods_id').trigger('chosen:updated');
			$('#goods_id').change();
			
			$('#buy_goods_form input[name="id"]').val(rowData.id);
			$('#buy_goods_form input[name="count"]').val(rowData.count);
			$('#buy_goods_form textarea[name="description"]').val(rowData.description);
			$('#buy_goods_form input[name="start_date"]').val(rowData.start_date);
			$('#buy_goods_form input[name="end_date"]').val(rowData.end_date);
		}
	}
};

$(function() {
	tmy_member_list.init();
});
