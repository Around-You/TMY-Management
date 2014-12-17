var main_sale = {
	init : function() {
	    this.bindEvent();
	},
	bindEvent: function(){
	    var me = this;
	    $('member-code').on('change', function(){
	        me.getMember($(this).val());
	    });
	},
	getMember: function(memberCode){
	    $.get('/sale/getMemberData',{member_code: memberCode}, function(result){
	        if(result.status){
	            var html = '<li><i class="ace-icon fa fa-caret-right blue"></i>姓名: ' + result.data.name + '</li>';
	            html += '<li><i class="ace-icon fa fa-caret-right blue"></i>积分: ' + result.data.point + '</li>';
	            $('.member-info-list').html(html);
	        }else{
	            
	        }
	       
	    });
	}
};

$(function() {
    main_sale.init();
});
