var wds_admin_product_profile = {
    options: {
        productId: 0
    },    
	init : function() {
		this.initTable();
		this.bindEvent();
	},
	initTable: function() {
	    var self = this;
		$('#buyer-table').dataTable( {
	        processing: true,
	        serverSide: true,
	        bAutoWidth: false,
	    	searching: false,
			lengthChange: false,
			info: false,
			ajax: "/product/profile/getBuyerData/" + self.options.productId,
			columns: [
				{ "data": "buyer_weixin" },
                { "data": "quantity" },
                { "data": "total" },
                { 
                    "data": null,
                    "orderable": false,
                    "render": function ( data, type, row ) {
                        var nextString = '<button class="btn btn-xs btn-danger"><i class="ace-icon fa fa-usd"></i>未付款</button>';
                        return nextString;
                    }
                }
			]
		} );
	},
	bindEvent: function(){
        var self = this;
	    $('#recommend').on('change', function(){
	        var data = {
	            id: self.options.productId,
	            recommend: $(this).prop('checked')?1:0
	        };
	        core.ajaxWithFlashMessager('/product/product/recommend', data)
	    });
	}
	

};

$(function() {
    wds_admin_product_profile.init();
});
