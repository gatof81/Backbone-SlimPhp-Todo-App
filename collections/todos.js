define([
	'underscore',
	'backbone',
	'models/todo'
], function( _, Backbone, Todo ) {

	var TodosCollection = Backbone.Collection.extend({
		
		model: Todo,

		url: 'api/todos/',

		completed: function() {
			return this.filter(function( todo ) {
				return todo.get('completed');
			});
		},

		remaining: function() {
			return this.without.apply( this, this.completed() );
		},

		comparator: function( todo ) {
			return todo.get('priority');
		}
	});

	return new TodosCollection();
});