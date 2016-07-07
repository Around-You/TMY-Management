var tmy_member_list = {
	memberList : null,
	init : function() {
		this.initList();
		this.bindEvent();
		
		$('.date-picker').datepicker({
            language: 'zh',
			autoclose: true,
			todayHighlight: true
		}).next().on('click', function(){
			$(this).prev().focus();
		});
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
					tmy_member_list.memberList.draw();
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
					tmy_member_list.memberList.draw();
				}
			});
		});
		
		$("#search-submit").on("click", function(){
			var searchStr = $('#w0').serialize();
			me.memberList.search(searchStr).draw();
		})
	},
	
	initDeleteMemberGoodsForm: function(event){
		var relatedTarget = event.relatedTarget;
		if( $(relatedTarget).data("id") != undefined ){
			var rowData = tmy_member_list.memberList.row("#" + $(relatedTarget).data("id")).data();
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
			var rowData = tmy_member_list.memberList.row("#" + $(relatedTarget).data("id")).data();
			var modal = $('#modal-disable');
			$(modal).find("form").attr('action', $(relatedTarget).data("url"));
			$(modal).find(".item-name").text(rowData.name);
		}
	},
	
	initList: function(){
		tmy_member_list.memberList = $('#member').DataTable( {
			dom: "lrtip",
		    processing: false,
		    serverSide: true,
		    bAutoWidth: false,
			searching: true,
			lengthChange: false,
			info: true,
			ajax: {
				"url":"\/member\/getMemberListData","data":{
                   
				}
			},
		    order: [[0,"desc"]],
			columns: [
				{ data: "code", orderable: 1, render: function ( data, type, row ){
					return '<a href="/member/profile/' + row.DT_RowId + '">' + data + "</a>";
				}},
				{ data: "name", orderable: 1 },
				{ data: "phone", orderable: 1 },
				{ data: "parent_name", orderable: 1 },
				{ data: "point", orderable: 1 },
				{ data: "created_time", orderable: 1 },
				{ data: "dob", orderable: 1 },
				{ data: "referral_staff_name", orderable: 1 },
				{ data: "statusString", orderable: 1 },
				{ data: null, orderable: false, render: function ( data, type, row ) { 
					var editUrl = '<a href="/member/profile/' + row.DT_RowId + '"> <i class="ace-icon glyphicon glyphicon-pencil"></i>编辑</a>';
					if(row.status=='0'){ var deleteUrl = '<a href="/member/delete/' + row.DT_RowId + '"> <i class="ace-icon glyphicon glyphicon-remove"></i>删除</a>';}
					else{var deleteUrl = ''}
					if(row.status=='1'){ var disable = '<a  href="#modal-disable" data-id="' + row.DT_RowId + '" class="modal-button" data-toggle="modal" data-url="/member/disableMember/' + row.DT_RowId + '"> 禁用</a>';}
					else{var disable = ''}
					if(row.status=='0'){ var enable = '<a  href="#modal-confirm" data-id="' + row.DT_RowId + '" class="modal-button" data-toggle="modal" data-url="/member/enableMember/' + row.DT_RowId + '"> 启用</a>';}
					else{var enable = ''} return editUrl + ' ' + deleteUrl + ' ' + disable + ' ' + enable;
				}}
			]
		});
		    //-->
	}
};

$(function() {
	tmy_member_list.init();
});
