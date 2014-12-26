var tmy_product_list = {
	init : function() {
		this.initTable();
	},
	initTable: function() {
		$('#data-table').dataTable( {
	        processing: true,
	        serverSide: true,
	        bAutoWidth: false,
	    	searching: false,
			lengthChange: false,
			info: false,
			ajax: "/member/getMemberListData",
			order: [[ 1, "desc" ]],
			columns: [
				{ 
				    data: "code",
				    render: function ( data, type, row ){
				        return '<a href="/member/edit/' + row.DT_RowId + '">' + data + '</a>';
				    }
				},
				{ "data": "name" },
				{ "data": "phone" },
				{ "data": "parent_name" },
				{ "data": "point" },
                { 
                    "data": null,
                    "orderable": false,
                    "render": function ( data, type, row ) {
                        var editString = '<a href="/member/edit/' + row.DT_RowId + '"> <i class="ace-icon glyphicon glyphicon-pencil"></i>编辑</a>';
                        var deleteString = '<a href="/member/delete/' + row.DT_RowId + '"> <i class="ace-icon glyphicon glyphicon-remove"></i>删除</a>';
                        return editString + ' ' + deleteString;
                    }
                }
			]
		} );
	},


};

$(function() {
	tmy_product_list.init();
});
