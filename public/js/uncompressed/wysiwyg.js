jQuery(function($) {
	$('.wysiwyg-editor').ace_wysiwyg({
		toolbar:
		[
			{
				name:'font',
				title:'Custom tooltip',
				values:['Some Font!','Arial','Verdana','Comic Sans MS','Custom Font!']
			},
			null,
			{
				name:'fontSize',
				title:'Custom tooltip',
				values:{1 : 'Size#1 Text' , 2 : 'Size#1 Text' , 3 : 'Size#3 Text' , 4 : 'Size#4 Text' , 5 : 'Size#5 Text'} 
			},
			null,
			{name:'bold', title:'Custom tooltip'},
			{name:'italic', title:'Custom tooltip'},
			{name:'strikethrough', title:'Custom tooltip'},
			{name:'underline', title:'Custom tooltip'},
			null,
			'insertunorderedlist',
			'insertorderedlist',
			'outdent',
			'indent',
			null,
			{name:'justifyleft'},
			{name:'justifycenter'},
			{name:'justifyright'},
			{name:'justifyfull'},
			null,
			{
				name:'insertImage',
				placeholder:'Custom PlaceHolder Text',
				button_class:'btn-inverse',
				//choose_file:false,//hide choose file button
				button_text:'Set choose_file:false to hide this',
				button_insert_class:'btn-pink',
				button_insert:'Insert Image'
			},
			null,
			{
				name:'foreColor',
				title:'Custom Colors',
				values:['red','green','blue','navy','orange'],
				/**
					You change colors as well
				*/
			},
			/**null,
			{
				name:'backColor'
			},*/
			null,
			{name:'undo'},
			{name:'redo'},
			null,
			'viewSource'
		],
		//speech_button:false,//hide speech button on chrome
		
		'wysiwyg': {
			hotKeys : {} //disable hotkeys
		}
		
	}).prev().addClass('wysiwyg-style1');
	
	
});