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
			order: [[ 1, "desc" ]]
		} );
	},


};

$(function() {
	tmy_product_list.init();
});
