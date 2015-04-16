var tmy_member_list = {
	init : function() {
		this.bindEvent();
	},
	bindEvent : function() {
		var me = this;
		$('#modal-confirm').on('shown.bs.modal', function(event) {
			me.initDeleteMemberGoodsForm(event);
		});
		$("#btn-delete-submit").on("click", function() {
			var url = $("#modal_confirm_form").attr("action");
			$.getJSON(url, function(result) {
				if(result.status == '1'){
					$('#modal-confirm').modal('hide');
					$member.draw();
				}
			});
		});
	},
	initDeleteMemberGoodsForm: function(event){
		var relatedTarget = event.relatedTarget;
		if( $(relatedTarget).data("id") != undefined ){
			var rowData = $member.row("#" + $(relatedTarget).data("id")).data();
			console.log(rowData);
			var modal = $('#modal-confirm');
			$(modal).find("form").attr('action', $(relatedTarget).data("url"));
			$(modal).find("#item-operate").text($(relatedTarget).text());
			$(modal).find("#item-name").text(rowData.name);
//			$(modal).find('input[name="id"]').val(rowData.id);
		}
	}
};

$(function() {
	tmy_member_list.init();
});
