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
		$("#goods-tbl").on(ace.click_event,'.use-goods-btn', function() {
			var tr = $(this).closest('tr');
			var showText = '是否对' + $(tr).data('title') + '进行操作？';
			$(".modal-body .col-xs-12").text(showText);
			$("#modal-confirm").data('id', $(tr).data('id'));
			$("#modal-confirm").modal({
				show: true
			});
		});
		$("#btn-use-submit").on(ace.click_event, function() {
			var id = $("#modal-confirm").data('id');
			$.post('/sale/doUseGoods', {id: id}, function(result){
				 if(result.status){
					 window.open(result.data.url, '_blank', 'toolbar=yes, location=yes, directories=no, status=no, menubar=yes, scrollbars=yes, resizable=no, copyhistory=yes, width=400, height=400');
				 }
			});
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
	        		var row = '<tr data-id="' + item.id + '" data-title="' + item.goods_title + '"><td class="center">' + rownum + '</td>';
		        	row += '<td>' + item.goods_title + '</td>';
		        	row += '<td>'+ item.description + '</td>';
		        	row += '<td><a href="javascript:void(0)" class="use-goods-btn green"> <i class="ace-icon glyphicon glyphicon-check green"></i>扣次</a></td>';
		        	row += '</tr>';
		            $('#goods-tbl tbody').append(row);
	        		rownum++;
	        	}
	        }else{
	        }
	    });
	},
	doCardUse: function(){

	}

};

$(function() {
	main_sale_use.init();
});
