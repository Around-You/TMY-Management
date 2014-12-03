var wds_admin_product = {
	elmDropzone: null,
	init: function(){
		this.initDropZone();
		this.initUploadedFiles();
		this.initEvents();
	},
	initEvents: function(){
		var form = $('#product_form');
		var self = this;
		$(form).on('submit', function(e){
			$.each(self.elmDropzone.files, function( index, file){
				var input = '<input type="hidden" name="product_images[]" value="' + file.serverId + '">';
				$(form).append(input);
			} )
		});
		
		$('.btn-modal-form-submit').on('click', function(e){
		    var form = $('#' + $(this).data('submit'));
		    var url = $(form).attr('action');
		    var data = $(form).serialize();
		    $.post(url, data, function(){
		        window.location.reload();
		    })
		});
	},
	
	initDropZone: function(){
		Dropzone.autoDiscover = false;
		
		this.elmDropzone = new Dropzone("div.dropzone", { 
			url: "/admin/product/product/uploadimage",
		    paramName: "file", // The name that will be used to transfer the file
		    maxFilesize: 5, // MB
		    params: {
		    	product_id: $("input[name='id']").val()
		    },
		    addRemoveLinks : false,
			dictDefaultMessage : $('#dropzone_dictDefaultMessage').html(),
			dictResponseError: 'Error while uploading file!',
			
			//change the previewTemplate to use Bootstrap progress bars
			previewTemplate: $('#dropzone_previewTemplate').html(),
		  
		});
		this.elmDropzone.on("success", function(file, response){
			file.serverId = response.id;
		});
		var elmDropzone = this.elmDropzone;
	
		this.elmDropzone.on("addedfile", function(file) {
	        // Create the remove button
	        var removeButton = Dropzone.createElement($('#dropzone_removeButtonTemplate').html().trim());
	        var premierButton = Dropzone.createElement($('#dropzone_premierButtonTemplate').html().trim());

	        // Capture the Dropzone instance as closure.
	        var elmDropzone = this;

	        // Listen to the click event
	        removeButton.addEventListener("click", function(e) {
	          // Make sure the button click doesn't submit the form:
	          e.preventDefault();
	          e.stopPropagation();
	          var button = this;
	          // If you want to the delete the file on the server as well,
	          // you can do the AJAX request here.
	          $.post('/admin/product/product/removeImage', { imageId: file.serverId}, function(){
	        	  // Remove the file preview.
	        	  elmDropzone.removeFile(file);
	          });
	        });
	        
	        premierButton.addEventListener("click", function(e) {
		          // Make sure the button click doesn't submit the form:
		          e.preventDefault();
		          e.stopPropagation();
		          var button = this;
		          // If you want to the delete the file on the server as well,
		          // you can do the AJAX request here.
		          $.post('/admin/product/product/setDefaultImage', { imageId: file.serverId, id: elmDropzone.options.params.product_id}, function(){
		        	  // Remove the file preview.
			          $(".dz-button-premier").show();
			          $(button).hide();
		          });
		        });

	        // Add the button to the file preview element.
	        file.previewElement.appendChild(removeButton);
	        file.previewElement.appendChild(premierButton);
		});
		
	},
	
	initUploadedFiles: function(){
		var files = $("#dropzone_uploadedFiles").html();
		files = $.parseJSON(files);
		if(files){
    		var elmDropzone = this.elmDropzone;
    		$.each(files, function( index, value ){
    			var file = { 
    				serverId: value.id,
                    name: value.name,
                    size: 1024,
                    status: "success"
                }
    			elmDropzone.files.push(file);
    			elmDropzone.emit("addedfile", file);
    			elmDropzone.emit("thumbnail", file, value.thumbnail_uri);
    		});
    	}
	}
};

$(function(){
	wds_admin_product.init();
});
