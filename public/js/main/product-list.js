var wds_admin_product_list = {
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
			order: [[ 1, "desc" ]],
			columnDefs: [
				{
					"render": function ( data, type, row ) {
						return  '<img alt="" src="'+ data +'">';
					},
					"targets": 0
				},
			],
			columns: [
				{ "data": "title" },
				{ "data": "category" },
				{ "data": "price" },
				{ "data": "quantity" },
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
	},


};

$(function() {
	wds_admin_product_list.init();
});
