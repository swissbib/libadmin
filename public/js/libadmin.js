var LibAdmin = {

	onLoaded: function() {
		if( this.hasUrlPart('/institution') ) {
			this.Institution.init();
		}
		if( this.hasUrlPart('/group') ) {
			this.Group.init();
		}
		if( this.hasUrlPart('/view') ) {
			this.View.init();
		}
	},

	hasUrlPart: function(part) {
		return document.location.pathname.indexOf(part) !== -1;
	},





	loadInContent: function(url, handler) {
		$('#content').load(url, handler);
		this.Sidebar.updateList();
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

		},



		/**
		 * Initialize relations event handlers
		 *
		 */
		initRelations: function() {
			$('.source select').dblclick(this.onSourceListDoubleClick);
			$('.selection select').dblclick(this.onSelectionListDoubleClick);
			$('.selection select').blur(this.onSelectionListBlur);
			$('#institutions button[value=add]').click(this.onAddClick);
			$('#institutions button[value=remove]').click(this.onRemoveClick);
		},



		/**
		 * Source list double click: add item
		 *
		 * @param event
		 */
		onSourceListDoubleClick: function(event) {
			var option		= event.target,
				selection	= $(this).parents('.row-fluid').find('.selection select')[0];

			selection.options[selection.options.length] = new Option(option.text, option.value, true, true);
			this.remove(this.selectedIndex);
		},



		/**
		 * Selection list double click: remove item
		 *
		 * @param event
		 */
		onSelectionListDoubleClick: function(event) {
			var option	= event.target,
				target	= $(this).parents('.row-fluid').find('.source select')[0];

			target.options[target.options.length] = new Option(option.text, option.value, false, false);
			this.remove(this.selectedIndex);

				// Make sure all items are still selected
			$(this).find('option').map(function(item) {
				this.selected = true;
			});
		},



		/**
		 * Selection list blur
		 * Select all items
		 */
		onSelectionListBlur: function() {
			var options = $(this).find('option');

			setTimeout(function(){
				options.map(function(item) {
					this.selected = true;
				});
			}, 500);
		},



		/**
		 * Add button click
		 *
		 * @param event
		 */
		onAddClick: function(event) {
			var row			= $(this).parents('.row-fluid'),
				selection	= row.find('.selection select')[0],
				source		= row.find('.source select')[0],
				selected	= row.find('.source option:selected');

			selected.each(function(index, option) {
				selection.options[selection.options.length] = new Option(option.text, option.value, true, true);
			});
			selected.each(function(index, option) {
				source.remove(option);
			});
		},



		/**
		 * Handle remove button click
		 * Remove all selected institutions from selectionlist
		 *
		 * @param	{Object}	event
		 */
		onRemoveClick: function(event) {
			var row			= $(this).parents('.row-fluid'),
				selection	= row.find('.selection select')[0],
				source		= row.find('.source select')[0],
				selected	= row.find('.selection option:selected');

			selected.each(function(index, option) {
				source.options[source.options.length] = new Option(option.text, option.value, true, true);
			});
			selected.each(function(index, option) {
				selection.remove(option);
			});
			source.selectedIndex = -1;
		}
	},

	View: {
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

	Editor: {
		init: function(contentLoadedHandler) {
			this.initForm(contentLoadedHandler);
			this.initTabs();
			this.initButtons(contentLoadedHandler);
		},

		initForm: function(handler) {
				// Enable ajax form
			$('#content > form').ajaxForm({
				target: '#content',
				success: function() {
					if( handler ) {
						handler();
					}
					LibAdmin.Sidebar.updateList();
				}
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
				event.preventDefault();
				$('#content').load($(this).attr('href'), that.contentUpdate);
			});
		},

		onListUpdated: function() {
			$("#search-results-list").unmask();
			this.initList();

			if( this.listUpdate ) {
				this.listUpdate();
			}
		},


		updateList: function() {
			$("#search-results-list").mask("Loading...");
			$('#search-form').submit();
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
					that.updateList();
//					form.submit();
				}, 500);
			});
		}
	}



};



$(function() {
	LibAdmin.onLoaded();
});