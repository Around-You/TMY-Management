var core = {
    init : function() {
        this.bindLink();
    },
    ajaxWithFlashMessager : function(url, data) {
        $.post(url, data, function(html) {
            $('.flash-messager-container').html('');
            $('.flash-messager-container').prepend(html);
        });
    },
    bindLink : function() {
        var self = this;
        $(document).on("click", 'a', function(e) {
            var dataTarget = $(this).data("target");
            if (dataTarget) {
                switch($(this).data("method"))
                {
                    case 'ajax':
                        e.preventDefault();
                        e.stopPropagation();
                        self.ajaxWithFlashMessager(dataTarget, $(this).data());
                        var table = $(this).closest('table');
                        if(table){
                            $(table).DataTable().ajax.reload();
                        }
                        break;
                    case 'modal-qrcode':
                        $(dataTarget + ' .qrcode-in-model').html('');
                        $(dataTarget + ' .qrcode-in-model').qrcode({width: 128,height: 128,text: $(this).data("text")});
                        break;
                }
            }
        });
    }
}
$(function() {
    core.init();
});