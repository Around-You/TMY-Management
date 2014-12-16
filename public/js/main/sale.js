var main_sale = {
	init : function() {
	},
	getMember: function(memberCode){
	    $.post('/sale/getMemberData',{memberCode: memberCode}, function(result){
	        var html = '';
	        console.log(result);
	    });
	}
	


};

$(function() {
    main_sale.init();
});
