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
			ajax: "/product/product/getProcustsListData",
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
				{ "data": "img", "orderable": false },
				{ 
				    "data": "title",
				    render: function ( data, type, row ){
				        var renderString = '<p>' + data + '</p>';
				        var categoryString = '<span class="label">' + row.category + '</span>';
                        var recommendString = '<span class="label label-purple">' + row.recommend + '</span>';
				        renderString += '<p>' + categoryString + ' ' + recommendString + ' ' + row.price + '</p>';
				        return renderString;
				    }
				},
				{ 
					"data": null,
					"orderable": false,
					className:'cols-actions',
					"render": function ( data, type, row ) {
					    var shareStringLarge = '<p class="hidden-xs"><a data-target="#qrcodeModal" data-method="modal-qrcode" data-toggle="modal" data-text="http://115.29.19.195:9001/p/' + row.DT_RowId + '"><i class="ace-icon fa fa-comments"></i>分享</a></p>';
					    var shareStringSmall = '<p class="visible-xs"><a href="http://115.29.19.195:9001/p/' + row.DT_RowId + '"><i class="ace-icon fa fa-comments"></i>分享</a></p>';
					    var profileString = '<p><a href="/product/profile/index/' + row.DT_RowId + '"><i class="ace-icon fa fa-file"></i>详细</a></p>';
//						var recommendString = '';
//						if(row.recommend == 1){
//						    recommendString = '<p><a data-target="/admin/product/product/recommend" data-method="ajax" data-id="' + row.DT_RowId + '" data-recommend="0" ><i class="ace-icon fa fa-heart-o"></i>取消推荐</a></p>';
//						}else{
//						    recommendString = '<p><a data-target="/admin/product/product/recommend" data-method="ajax" data-id="' + row.DT_RowId + '" data-recommend="1" ><i class="ace-icon fa fa-heart"></i>推荐</a></p>';
//						}
						// data-toggle="modal" data-target="#myModal"
						return shareStringLarge + shareStringSmall + profileString;
					}
				}
			]
		} );
	},


};

$(function() {
	wds_admin_product_list.init();
});
