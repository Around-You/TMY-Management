var tmy_member_list = {
    init : function() {
        $('.chosen-select').chosen({
            allow_single_deselect : true
        });

        $('#modal-form').on('shown.bs.modal', function() {
            $(this).find('.chosen-container').css('width', '100%');
            $(this).find('.chosen-container').each(function() {
                $(this).find('a:first-child').css('width', '100%');
                $(this).find('.chosen-drop').css('width', '100%');
                $(this).find('.chosen-search input').css('width', '100%');
            });
        });
        this.bindEvent();
    },
    bindEvent : function() {
        $(".goods-chosen-select").on('change', function() {
            var type = '';
            $(".goods-chosen-select option:selected").each(function() {
                type = $(this).data('goods-type');
            });
            console.log(type);
            if(type == '时间卡'){
                $(".time-card-only").show();
                $(".count-card-only").hide();
            }else{
                $(".time-card-only").hide();
                $(".count-card-only").show();
            }
        });
        $("#btn-submit-buy-modal").on("click", function (){
            var data = $("#buy_goods_form").serialize();
            $.getJSON( "ajax/test.json", function( data ) {
              
              });
        });
    },
};

$(function() {
    tmy_member_list.init();
});
