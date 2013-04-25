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






	Group: {

		lockList: {},

		init: function() {
			this.initSidebar();
			this.initEditor();
		},

		initSidebar: function() {
			LibAdmin.Sidebar.init($.proxy(this.onSearchListUpdated, this), $.proxy(this.onContentUpdated, this));
		},

		initButtons: function() {
			$('#submitbutton').mouseover($.proxy(this.beforeSaving, this));
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
		 */
		initRelations: function(lockList) {
			this.lockList = lockList;

			this.removeLockedInstitutions(lockList);

			$('.source select').dblclick(this.onSourceListDoubleClick);
			$('.selection select').dblclick(this.onSelectionListDoubleClick);

			var institutions	= $('#institutions');
			institutions.find('button[value=add]').click(this.onAddClick);
			institutions.find('button[value=remove]').click(this.onRemoveClick);

			this.initButtons();
		},

		/**
		 * Actions before saving
		 * Make sure all selection institutions are selected
		 */
		beforeSaving: function() {
			this.selectAllSelectedInstitutions();
		},

		/**
		 * Remove institutions which are already related to the view with another group
		 *
		 * @param	{Array}		lockList
		 */
		removeLockedInstitutions: function(lockList) {
			var list,
				option;

			$.each(lockList, function(idView, lockedInstitutionIDs) {
				list = $('#view-' + idView + '-source select')[0];

				$.each(lockedInstitutionIDs, function(index, item){
					option = $(list).find('option[value=' + item + ']');
					if( option[0] ) {
						list.remove(option[0].index);
					}
				});
			});
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
		},

		/**
		 * Select all selection items (to be submitted in form save request)
		 */
		selectAllSelectedInstitutions: function() {
			$('.selection.listElement select option').map(function(index, option) {
				option.selected = true;
			});
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
				source.remove(option.index);
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
				selection.remove(option.index);
			});
			source.selectedIndex = -1;
		}
	},



	/**
	 * Views Administration: CRUD for Views, activation, sorting of groups and institutions, relations reviewing
	 */
	View: {
		/**
		 * Init View area
		 */
		init: function() {
			this.initSidebar();
			this.initEditor();
		},

		/**
		 * Init view records search list
		 */
		initSidebar: function() {
			LibAdmin.Sidebar.init($.proxy(this.onSearchListUpdated, this), $.proxy(this.onContentUpdated, this));
		},

		/**
		 * Init editor part of view area
		 */
		initEditor: function() {
			LibAdmin.Editor.init($.proxy(this.onContentUpdated, this));
			this.initSortables();
		},

		/**
		 * Init drag and drop sorting of items of: groups, institutions
		 */
		initSortables: function() {
			this.initSortable( $('#groupsortable') );
			this.initSortable( $('#institutionsortable') );
		},

		/**
		 * Init sortability of given UL list
		 *
		 * @param	{element}	el
		 */
		initSortable: function(el) {
			el.sortable({
				stop: function(event, ui) {
					LibAdmin.View.storeSortingPositions(event.target);
				}
			});
			el.disableSelection();

//			$('a.make-topmost-group').each(function(id, el) {
//				el.click(function() {
//					alert('click maketop');
//				});
//			});

		},

		/**
		 * Read sortable items positions of given element
		 * and put it as a comma separated list into the resp. positions-list field
		 *
		 * @param	{Element}	el
		 */
		storeSortingPositions: function(el) {
			var itemIDsCSV	= $('#' + el.id).find('li').map(function() {
				return this.id;
			}).get().join(",");

			var fieldStore	= $('#' + el.id + 'ids');
			fieldStore.attr('value', itemIDsCSV);
		},

		/**
		 * Event handler after content update: re-init edit
		 */
		onContentUpdated: function() {
			this.initEditor();
		},

		/**
		 * Event handler after records search list update
		 */
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