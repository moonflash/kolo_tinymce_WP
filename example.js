tinymce.PluginManager.add('kolo_mce', function(editor, url) {
	var scriptLoader = new tinymce.dom.ScriptLoader();

	scriptLoader.load('http://beta.kolo.it/widget.js');
	editor.addCommand('start_kolo', function() {
		
		tinymce.activeEditor.windowManager.close()
		openKoloWindow()
	})

	var publish = document.getElementById("publish");
	publish.addEventListener("click",askQuestion);

	function askQuestion(event){
		var content = jQuery(tinyMCE.activeEditor.getContent());
		var total;
		for (i=0;i<content.length;i++){
			total = total + content[i].innerHTML;
		}
		if (total.indexOf("<!--kolo-link-id=") == -1 && localStorage.getItem("kolo-wp") != "forever"){
			event.preventDefault();
			editor.windowManager.open({
				title: 'Do you want to add your article to Kolo?',
				file: "../wp-content/plugins/"+current_dir+"/reminder.html",
				width:"500px",
				height:"70px",
    		popup_css:true,
    		close_previous:true
			},{
				original:event
			})
		}
	}

	function openKoloWindow(){
		editor.windowManager.open({
				title: 'Kolo plugin',
				width: document.body.offsetWidth-20,
    		height: document.body.offsetHeight-60,
    		file: "../wp-content/plugins/"+current_dir+"/kolo.html",
    		popup_css:true,
    		close_previous:true

			},{
				url: post_url,
				title:document.getElementById("title").value,
				content:jQuery(tinyMCE.activeEditor.getContent()).text().substring(0,200),
				images: Array.prototype.slice.call(tinymce.activeEditor.dom.select('img')).map(function(element){return {url:element.src, link_id:element.dataset.koloLink};})
			});
	}
	editor.addButton('kolo_mce_button_key', {
		text: ' Kolo',
		icon: 'icon gavickpro-kolo-icon',
		onclick: openKoloWindow
	});
});
