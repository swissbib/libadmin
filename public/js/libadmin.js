var LibAdmin = {

	onLoaded: function() {
		this.initGeneral();

		if( document.location.pathname.indexOf('institution') ) {
			this.Institution.init();
		}

	},

	initGeneral: function() {
		this.Sidebar.init();

	},



	Institution: {

		init: function() {
			LibAdmin.Sidebar.initList(this.onFormLoaded);
			LibAdmin.Sidebar.initAddButton(this.onFormLoaded);

		},

		onFormLoaded: function() {
			console.log('handler loaded Institution');
			LibAdmin.Editor.init('institution', LibAdmin.Institution.onFormLoaded);
		}

	},

	Group: {

	},

	View: {

	},

	Editor: {
		init: function(name, handler) {

			$('form#' + name).ajaxForm({
	            target: '#content',
				success: handler
	        });

			$('.formTabs a').click(function(e) {
				e.preventDefault();
				$(this).tab('show');
			})
		}
	},

	Sidebar: {
		init: function() {

		},

		initAddButton: function(handler) {
			var addButton	= $('.addButton');

			if( addButton ) {
				addButton.click(function(event) {
					event.preventDefault();
					$('#content').load($(this).attr('href'), handler);
				});
			}
		},




		initList: function(handler) {
			$('#search-results-list a').click(function(event) {
				event.preventDefault();

				$('#content').load($(this).attr('href'), {}, handler);
			});
		}
	}



};



$(function() {
	LibAdmin.onLoaded();
});