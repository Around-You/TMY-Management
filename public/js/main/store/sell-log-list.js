var tmy_sell_log_list = {
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
			ajax: "/store/getSellLogListData",
			columns: [
				{ 
				    data: "member_code",
				    render: function ( data, type, row ){
				        return '<a href="/member/edit/' + row.member_id + '">' + data + '</a>';
				    }
				},
				{ 
					data: "member_name",
				    render: function ( data, type, row ){
				        return '<a href="/member/edit/' + row.member_id + '">' + data + '</a>';
				    } 
				},
				{ 
					data: "goods_title",
				    render: function ( data, type, row ){
				        return '<a href="/goods/edit/' + row.goods_id + '">' + data + '</a>';
				    } 
				},
				{ 
					data: "price"
				},
				{ 
					data: "quantity"
				},
				{ 
					data: null,
					render: function ( data, type, row ){
						return row.price * row.quantity;
					} 
				},
				{
					data: "user_name" 
				},
                { 
                    "data": null,
                    "orderable": false,
                    render: function ( data, type, row ){
                    	return '';
                    }
                }
			]
		} );
	},


};

$(function() {
	tmy_sell_log_list.init();
});
