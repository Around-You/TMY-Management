var main_sale_use = {
	init : function() {
	    this.bindEvent();
	},
	bindEvent: function(){
	    var me = this;
	    $('#member-code').on('blur', function(){
	        me.getMember($(this).val());
	        me.getMemberGoods($(this).val());
	    });


	},
	getMember: function(memberCode){
	    $.get('/sale/getMemberData',{member_code: memberCode}, function(result){
	        if(result.status){
	            var html = '<li><i class="ace-icon fa fa-caret-right blue"></i>姓名: ' + result.data.name + '</li>';
	            html += '<li><i class="ace-icon fa fa-caret-right blue"></i>积分: ' + result.data.point + '</li>';
	            $('.member-info-list').html(html);
	            $('.member-info-list').data('code',memberCode);
	        }else{
	            
	        }
	       
	    });
	},
	getMemberGoods: function(memberCode){
		$('#goods-tbl tbody tr').remove();
	    $.get('/sale/getMemberGoodsData',{member_code: memberCode}, function(result){
	        if(result.status){
	        	var rownum = 1;
	        	for(var key in result.data){
	        		var item = result.data[key];
	        		var row = '<tr data-code="' + item.goods_id + '"><td class="center">' + rownum + '</td>';
		        	row += '<td>' + item.goods_title + '</td>';
		        	row += '<td>'+ item.description + '</td>';
		        	row += '<td><a href="javascript:void(0)" class="remove-goods-btn"> <i class="ace-icon glyphicon glyphicon-remove"></i>扣次</a></td>';
		        	row += '</tr>';
		            $('#goods-tbl tbody').append(row);
	        		rownum++;
	        	}
	        	
	        	
	        	
	        	

	        }else{
	            
	        }
	       
	    });
	},

};

$(function() {
	main_sale_use.init();
});
