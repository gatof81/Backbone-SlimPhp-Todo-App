define([
	'jquery',
	'underscore',
	'backbone',
	'text!templates/todos.html',
	'commonjs',
	'collections/todos'
], function( $, _, Backbone, todosTemplate, Common, Todos ) {

	var TodoView = Backbone.View.extend({

		tagName:  'li',

		template: _.template( todosTemplate ),

		// The DOM events specific to an item.
		events: {
			'click .toggle':	'togglecompleted',
			'click .destroy':	'clear',
			'keypress .edit':	'updateOnEnter',
			'blur .edit':		'close',
			'keypress .prior':	'updateOnEnter',
			'blur .prior':		'close'
		},

		initialize: function() {
			//this.model.on( 'change', this.render, this );
			this.model.on( 'destroy', this.remove, this );
		},

		render: function() {
			this.$el.html( this.template( this.model.toJSON() ) );

			this.input = this.$('.edit');
			this.priority = this.$('.prior');
			return this;
		},

		togglecompleted: function() {
			this.model.toggle();
		},

		close: function() {
			var value = this.input.val().trim(),
			value2 = this.priority.val().trim();
			if (value2.toString() !== parseInt(value2,10).toString()){
				value2 = 1;
			} 

			if ( value || value2){
				this.model.set({id:this.model.get('id').toString()},[{silent:true}]);
				this.model.save({ content: value, priority: value2 });
			} else {
				this.clear();
			}
			Todos.sort();
		},

		updateOnEnter: function( e ) {
			if ( e.keyCode === Common.ENTER_KEY ) {
				this.close();
			}
		},

		clear: function() {
			this.model.destroy();
		}
	});

	return TodoView;
});

