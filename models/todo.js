define([
	'underscore',
	'backbone'
], function( _, Backbone ) {

	var TodoModel = Backbone.Model.extend({

		defaults: {
			content:'',
			priority: '',
			completed: '',
			status:'',
			remaining:''
		},

		toggle: function() {
			this.save({
				completed: !this.get('completed')
			});
		}
	});

	return TodoModel;
});