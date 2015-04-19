var main_sale_use = {
	init : function() {
	    this.bindEvent();
	    this.showPopup();
	},
	bindEvent: function(){
	    var me = this;
        $("#member-code").autocomplete({
            source: '/sale/getMemberByCode',
            select: function( event, ui ) {
                 me.getMember(ui.item.id);
                 me.getMemberGoods(ui.item.id);
            }
        });
		$("#goods-tbl").on(ace.click_event,'.use-goods-btn', function() {
			var tr = $(this).closest('tr');
			var showText = '是否对 ' + $(tr).data('title') + ' 进行 扣次/使用 操作？';
			$(".modal-content #myModalLabel").text(showText);
			$("#member_goods_code").val($(tr).data('id'));
			$("#modal-confirm").modal();
		});
		$("#btn-use-submit").on(ace.click_event, function() {
			$("#useGoodsForm").submit();
		});
		$('#use_count').ace_spinner({
			value:1, min:0, max:10, step:1, on_sides: true, 
			icon_up:'ace-icon fa fa-plus bigger-110', 
			icon_down:'ace-icon fa fa-minus bigger-110', 
			btn_up_class:'btn-success' , 
			btn_down_class:'btn-danger'
		});
	},
	showPopup: function(){
		if($('#showpopup').val()!=''){
			window.open($('#showpopup').val(), '_blank', 'toolbar=yes, location=yes, directories=no, status=no, menubar=yes, scrollbars=yes, resizable=no, copyhistory=yes, width=250, height=400');
		}
	},
	getMember: function(memberCode){
	    $.get('/sale/getMemberData',{member_code: memberCode}, function(result){
	        if(result.status){
	            var html = '<li><i class="ace-icon fa fa-caret-right blue"></i>姓名: ' + result.data.name + '</li>';
	            html += '<li><i class="ace-icon fa fa-caret-right blue"></i>积分: ' + result.data.point + '</li>';
	            html += '<li><i class="ace-icon fa fa-caret-right blue"></i>备注: ' + result.data.description + '</li>';
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
	        		var row = '<tr data-id="' + item.id + '" data-title="' + item.goods_title + '"><td class="center">' + rownum + '</td>';
		        	row += '<td>' + item.goods_title + '</td>';
		        	row += '<td>'+ item.detail + '</td>';
		        	row += '<td>'+ item.description + '</td>';
		        	row += '<td><a href="javascript:void(0)" class="use-goods-btn green"> <i class="ace-icon glyphicon glyphicon-check green"></i>扣次</a></td>';
		        	row += '</tr>';
		            $('#goods-tbl tbody').append(row);
	        		rownum++;
	        	}
	        }else{
	        }
	    });
	}
};

$(function() {
	main_sale_use.init();
});
