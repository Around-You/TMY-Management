var main_category_list = {
	init : function() {
		this.initTable();
	},
	initTable: function() {
		$('#category-table').dataTable( {
	        processing: true,
	        serverSide: true,
	        bAutoWidth: false,
	    	searching: false,
			lengthChange: false,
			info: false,
			ajax: "/category/getCategoriesListData",
			columns: [
				{ "data": "title" },
				{ "data": "type" },
                { 
                    "data": null,
                    "orderable": false,
                    "render": function ( data, type, row ) {
                        var editString = '<a href="/category/edit/' + row.DT_RowId + '"> <i class="ace-icon glyphicon glyphicon-pencil"></i>编辑</a>';
                        var deleteString = '<a href="/category/delete/' + row.DT_RowId + '"> <i class="ace-icon glyphicon glyphicon-remove"></i>删除</a>';
                        return editString + ' ' + deleteString;
                    }
                }
			]
		} );
	},


};

$(function() {
	main_category_list.init();
});
