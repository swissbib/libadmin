var LibAdmin = {

	onLoaded: function() {

		if( document.location.pathname.indexOf('institution') ) {
			this.Institution.init();
		}



	},

	Institution: {

		init: function() {
			LibAdmin.Sidebar.init(this.onFormLoaded);
		},

		onFormLoaded: function() {
			console.log('handler loaded Institution');
		}

	},

	Group: {

	},

	View: {

	},

	Sidebar: {
		init: function(handler) {
			$('#search-results-list a').click(function(event) {
				event.preventDefault();

				console.log('clicked on url' + $(this).attr('href'));

				$('#content').load($(this).attr('href'), {}, handler);
			});
		}
	}

};



$(function() {
	LibAdmin.onLoaded();
});