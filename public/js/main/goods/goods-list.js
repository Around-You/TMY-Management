var main_goods_list = {
	init : function() {
		this.initTable();
	},
	initTable: function() {
		$('#product-table').dataTable( {
	        processing: true,
	        serverSide: true,
	        bAutoWidth: false,
	    	searching: false,
			lengthChange: false,
			info: false,
			ajax: "/goods/getGoodsListData",
			order: [[ 0, "desc" ]],
			columns: [
				{ "data": "code" },
				{ "data": "title" },
				{ "data": "type" },
				{ "data": "category_name" },
				{ "data": "priceString" },
				{ "data": "desc" },
                { 
                    "data": null,
                    "orderable": false,
                    "render": function ( data, type, row ) {
                        var editString = '<a href="/goods/edit/' + row.DT_RowId + '"> <i class="ace-icon glyphicon glyphicon-pencil"></i>编辑</a>';
                        var deleteString = '<a href="/goods/delete/' + row.DT_RowId + '"> <i class="ace-icon glyphicon glyphicon-remove"></i>删除</a>';
                        return editString + ' ' + deleteString;
                    }
                }
			]
		} );
	}
};

$(function() {
	main_goods_list.init();
});
