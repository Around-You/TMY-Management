var tmy_member_list = {
	init : function() {
		this.bindEvent();
	},
	bindEvent : function() {
		var me = this;
		$('#modal-confirm').on('shown.bs.modal', function(event) {
			me.initDeleteMemberGoodsForm(event);
		});
		$('#modal-disable').on('shown.bs.modal', function(event) {
			me.initDisableForm(event);
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
		$("#btn-disable-submit").on("click", function(event) {
			var form = $("#modal_disable_form");
			var data = $(form).serialize();
			if($("#input-disable-type").val() == ''){
				$(form).find(".help-block").text("请选择一个禁用原因！");
				return;
			}else{
				$(form).find(".help-block").text("");
			}
			var url = $(form).attr("action");
			console.log(url);
			$.getJSON(url, data, function(result) {
				if(result.status == '1'){
					$('#modal-disable').modal('hide');
					$member.draw();
				}
			});
		});
	},
	initDeleteMemberGoodsForm: function(event){
		var relatedTarget = event.relatedTarget;
		if( $(relatedTarget).data("id") != undefined ){
			var rowData = $member.row("#" + $(relatedTarget).data("id")).data();
//			console.log(rowData);
			var modal = $('#modal-confirm');
			$(modal).find("form").attr('action', $(relatedTarget).data("url"));
			$(modal).find("#item-operate").text($(relatedTarget).text());
			$(modal).find("#item-name").text(rowData.name);
//			$(modal).find('input[name="id"]').val(rowData.id);
		}
	},
	initDisableForm: function(event){
		var relatedTarget = event.relatedTarget;
		if( $(relatedTarget).data("id") != undefined ){
			var rowData = $member.row("#" + $(relatedTarget).data("id")).data();
			var modal = $('#modal-disable');
			$(modal).find("form").attr('action', $(relatedTarget).data("url"));
			$(modal).find(".item-name").text(rowData.name);
		}
	}
};

$(function() {
	tmy_member_list.init();
});
