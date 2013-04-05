var LibAdmin = {

	onLoaded: function() {
		if( this.hasUrlPart('/institution') ) {
			this.Institution.init();
		}
		if( this.hasUrlPart('/group') ) {
			this.Group.init();
		}
		if( this.hasUrlPart('/view') ) {
//			this.View.init();
		}
	},

	hasUrlPart: function(part) {
		return document.location.pathname.indexOf(part) !== -1;
	},





	loadInContent: function(url, handler) {
		$('#content').load(url, handler);
	},




	Institution: {
		init: function() {
			this.initSidebar();
			this.initEditor();
		},

		initSidebar: function() {
			LibAdmin.Sidebar.init($.proxy(this.onSearchListUpdated, this), $.proxy(this.onContentUpdated, this));
		},

		initEditor: function() {
			LibAdmin.Editor.init($.proxy(this.onContentUpdated, this));
		},

		onContentUpdated: function() {
			this.initEditor();
		},

		onSearchListUpdated: function() {

		}

	},

	Group: {
		init: function() {
			this.initSidebar();
			this.initEditor();
		},

		initSidebar: function() {
			LibAdmin.Sidebar.init($.proxy(this.onSearchListUpdated, this), $.proxy(this.onContentUpdated, this));
		},

		initEditor: function() {
			LibAdmin.Editor.init($.proxy(this.onContentUpdated, this));
		},

		onContentUpdated: function() {
			this.initEditor();
		},

		onSearchListUpdated: function() {

		}
	},

	View: {

	},

	Editor: {
		init: function(contentLoadedHandler) {
			this.initForm(contentLoadedHandler);
			this.initTabs();
			this.initButtons(contentLoadedHandler);
		},

		initForm: function(handler) {
			$('#content > form').ajaxForm({
				target: '#content',
				success: handler
			});
		},

		initTabs: function() {
			$('.formTabs a').click(function(e) {
				e.preventDefault();
				$(this).tab('show');
			});
		},

		initButtons: function(handler) {
			$('a.ajaxButton').click(function(e){
				e.preventDefault();
				LibAdmin.loadInContent($(this).attr('href'), handler);
			});
		}
	},

	Sidebar: {

		searchDelay: null,

		listUpdate: null,
		contentUpdate: null,


		init: function(listUpdatedHandler, contentLoadedHandler) {
			this.listUpdate		= listUpdatedHandler;
			this.contentUpdate	= contentLoadedHandler;

			this.initSearchBox();
			this.initList();
		},


		initList: function() {
			var that = this;

			$('#search-results-list a').click(function(event) {
				console.log('item clicked');
				event.preventDefault();
				$('#content').load($(this).attr('href'), that.contentUpdate);
			});
		},

		onListUpdated: function() {
			this.initList();

			if( this.listUpdate ) {
				this.listUpdate();
			}
		},



		initSearchBox: function() {
			var that = this,
				form;

			$('#search-form').ajaxForm({
				target: '#search-results-list',
				success: $.proxy(this.onListUpdated, this)
			});

			$('#search-query').keyup(function(event){
				form = $(this).parents('form');

				clearTimeout(that.searchDelay);

				that.searchDelay = setTimeout(function(){
					form.submit();
				}, 500);
			});
		}
	}



};



$(function() {
	LibAdmin.onLoaded();
});