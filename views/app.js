define([
	'jquery',
	'underscore',
	'backbone',
	'views/todo',
	'collections/todos',
	'text!templates/app.html',
	'text!templates/userName.html',
	'commonjs'
], function( $, _, Backbone, TodoView, Todos, appTemplate, todoTemplate, Common ) {

	var	AppView = Backbone.View.extend({

		el: '#app',

		template: _.template( appTemplate ),

		events: {
			'keypress #new-todo':		'createOnEnter',
			'click #clear-completed':	'clearCompleted',
			'click #toggle-all':		'toggleAllComplete'
		},

		initialize: function() {
			this.input = this.$('#new-todo');
			this.allCheckbox = this.$('#toggle-all')[0];
			this.$footer = this.$('#footer');
			this.$main = this.$('#main');

			if (auth.is_logged_in) {
				Todos.on( 'add', this.addOne, this );
				Todos.on( 'reset', this.addAll, this );
				Todos.on( 'all', this.render, this );

				Todos.fetch();
			} else {
				location.hash = '#login';
			}
		},

		render: function() {
			var completed = Todos.completed().length;
			var remaining = Todos.remaining().length;

			if ( Todos.length ) {
				this.$main.show();
				this.$footer.show();
				

				this.$footer.html(this.template({
					completed: completed,
					remaining: remaining
				}));

			} else {
				this.$main.hide();
				this.$footer.hide();
			}
			
			this.allCheckbox.checked = !remaining;
		},

		addOne: function( todo ) {
			var view = new TodoView({ model: todo });
			if(todo.get('priority') !== 1){
				$('#todo-list').append( view.render().el );
			}else{
				$('#todo-list').prepend( view.render().el );
			}
		},

		addAll: function() {
			this.$('#todo-list').html('');
			Todos.each(this.addOne, this);
		},

		newAttributes: function() {
			return {
				content: this.input.val().trim(),
				priority: 1,
				completed: false,
				status:1
			};
		},

		createOnEnter: function( e ) {
			if ( e.which !== Common.ENTER_KEY || !this.input.val().trim() ) {
				return;
			}

			Todos.create(this.newAttributes());
			this.input.val('');
		},

		clearCompleted: function() {
			_.each( Todos.completed(), function( todo ) {
				todo.destroy();
			});

			return false;
		},

		toggleAllComplete: function() {
			var completed = this.allCheckbox.checked;

			Todos.each(function( todo ) {
				todo.save({
					'completed': completed
				});
			});
		}

	});

	return AppView;

});
