define([
	'marionette',
	'backbone',
	'jquery',
	'lodash',
	'routing',
	'backgrid',
	'template',

	'backbone.paginator',
	'grid/backgrid-select-all',
	'grid/backgrid-paginator',
	'grid/backgrid-filter'
    ],
    function (
	Mn,
	Backbone,
	$,
	_,
	Routing,
	Backgrid,
	Template
    ) {
    return Mn.Object.extend({
	initialize: function (options, element) {

	    var GridCollection = Backbone.PageableCollection.extend({
		model: Backbone.Model,
		url  : Routing.generate('_grid_data', {'name' : options.name}),

		// Initial pagination states
		state: {
		    pageSize: 15,
		    sortKey: "created",
		    order: 1
		},

		// You can remap the query parameters from `state` keys from
		// the default to those your server supports
		queryParams: {
		    totalPages: null,
		    totalRecords: null,
		    sortKey: "sort"
		},

		parseState: function (resp, queryParams, state, options) {
		    return {totalRecords: resp.count};
		},

		parseRecords: function (resp, options) {
		    return resp.items;
		}
	    });

	    var collection = new GridCollection();

	    collection.fetch();

	    var gridOptions = {
		collection: collection,
		className: 'backgrid table'
	    };

	    options.columns.unshift({
		// name is a required parameter, but you don't really want one on a select all column
		name: "",
		// Backgrid.Extension.SelectRowCell lets you select individual rows
		cell: "select-row",
		// Backgrid.Extension.SelectAllHeaderCell lets you select all the row on a page
		headerCell: "select-all"
	    });
	    var grid = new Backgrid.Grid(_.extend(options, gridOptions));

	    $(element).html(grid.render().el);

	    var paginator = new Backgrid.Extension.Paginator({

		// If you anticipate a large number of pages, you can adjust
		// the number of page handles to show. The sliding window
		// will automatically show the next set of page handles when
		// you click next at the end of a window.
		windowSize: 20, // Default is 10

		// Used to multiple windowSize to yield a number of pages to slide,
		// in the case the number is 5
		slideScale: 0.25, // Default is 0.5

		// Whether sorting should go back to the first page
		goBackFirstOnSort: false, // Default is true

		collection: collection
	    });

	    $(element).append(paginator.render().el);


	    var filter = Backgrid.Extension.ServerSideFilter.extend({
		template: Template['grid/search']
	    });

	    var serverSideFilter = new filter({
		collection: collection,
		// the name of the URL query parameter
		name: "q"
	    });

	    $(element).before(serverSideFilter.render().el);
	}
    });
});