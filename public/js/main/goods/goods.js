var main_goods = {
	init : function() {
		this.bindEvent();
		this.showSelectedOption();
		
	},
	bindEvent: function(){
		var me = this;
		$('select[name="type"]').on('change',function(){
			me.showSelectedOption();
		})
	},
	showSelectedOption: function(){
		this.hideOptionalInput();
		switch($('select[name="type"]').val()){
			case '次卡':
				$('input[name="count"]').closest(".form-group").show();
				break;
			case '时间卡':
				$('select[name="date_range"]').closest(".form-group").show();
				break;
			case '普通商品':
			case '礼品':
				$('input[name="quantity"]').closest(".form-group").show();
				$('input[name="cost"]').closest(".form-group").show();
				break;
		}
	},
	hideOptionalInput: function(){
		$('input[name="count"]').closest(".form-group").hide();
		$('input[name="quantity"]').closest(".form-group").hide();
		$('select[name="date_range"]').closest(".form-group").hide();
		$('input[name="cost"]').closest(".form-group").hide();
	}

};

$(function() {
	main_goods.init();
});
