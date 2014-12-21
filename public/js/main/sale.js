var main_sale = {
	init : function() {
	    this.bindEvent();
	},
	bindEvent: function(){
	    var me = this;
	    $('#member-code').on('blur', function(){
	        me.getMember($(this).val());
	    });
	    $('#goods-code').on('blur', function(){
	        me.addGoods($(this).val());
	    });
	    $('#goods-tbl').on('click', '.remove-goods-btn', function(){
	        me.removeGoods(this);
	    });
	    $("#buy-button").on('click', function(){
	    	me.buyGoods();
	    })
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
	addGoods: function(goodsCode){
	    $.get('/sale/getGoodsData',{goods_code: goodsCode}, function(result){
	        if(result.status){
	        	var goods = result.data;
	        	var rownum = $("#goods-tbl").data("rownum") + 1;
	        	var total = Math.round($("#goods-tbl").data("total") + parseFloat(goods.price), 2);
	        	var row = '<tr data-price="' + goods.price + '" data-code="' + goods.code + '"><td class="center">' + rownum + '</td>';
	        	row += '<td>' + goods.title + '</td>';
	        	row += '<td class="hidden-xs">' + '--' + '</td>';
	        	row += '<td class="hidden-480">'+ '--' + '</td>';
	        	row += '<td>'+ goods.priceString + '</td>';
	        	row += '<td><a href="javascript:void(0)" class="remove-goods-btn"> <i class="ace-icon glyphicon glyphicon-remove"></i>删除</a></td>';
	        	row += '</tr>';

	            $('#goods-tbl tbody').append(row);
	            $("#goods-tbl").data("rownum", rownum);
	            $("#goods-tbl").data("total", total);
	            $("#total-price").text(total);
	        }else{
	            
	        }
	       
	    });
	},

	removeGoods: function(obj){
		var row = $(obj).closest('tr');
		var rownum = $("#goods-tbl").data("rownum") - 1;
		var total = parseFloat($("#goods-tbl").data("total")) - parseFloat($(row).data("price"));
        $("#total-price").text(total);
        row.remove();
        for(var i=1; i<=rownum; i++){
        	console.log(i);
        	$("#goods-tbl tr:nth-child(" + i + ") td:nth-child(1)").text(i);
        }
	},
	buyGoods: function(){
		var memberCode = $('.member-info-list').data('code');
		var goodsCodeArr = new Array();
		$("#goods-tbl tbody tr").each(function(){
			goodsCodeArr.push($(this).data('code'));
		})
		$("#member_code").val(memberCode);
		$("#goods_code_arr").val(goodsCodeArr);
		$("#buyGoodsForm").submit();
	}
};

$(function() {
    main_sale.init();
});
