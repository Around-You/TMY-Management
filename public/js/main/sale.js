var main_sale = {
	init : function() {
	},
	getMember: function(memberCode){
	    $.get('/sale/getMemberData',{member_code: memberCode}, function(result){
	        var html = '<li><i class="ace-icon fa fa-caret-right blue"></i>姓名: ' + result.name + '</li>';
	        html += '<li><i class="ace-icon fa fa-caret-right blue"></i>积分: ' + result.point + '</li>';
	        $('.member-info-list').html(html);
	    });
	}
};

$(function() {
    main_sale.init();
});
