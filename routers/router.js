define([
	'jquery',
	'backbone',
	'views/app',
	'views/auth',
	'collections/todos',
	'commonjs'
], function( $, Backbone, AppView, AuthView,Todos,Common ) {

	var Workspace = Backbone.Router.extend({

		initialize: function() {
			console.log('workspace');
		},

		routes: {
			"": "signup",
			"app": "app",
			"login": "login",
			"signup": "signup",
			'*filter': 'setFilter'
		},

		app: function() {
			new AppView();	
		},

		login: function() {
			new AuthView({
				attributes: { type : 'login' }
			});
		},

		signup: function() {
			new AuthView({
				attributes: { type : 'signup' }
			});
		},
		setFilter: function( param ) {
			// Set the current filter to be used
			Common.TodoFilter = param.trim() || '';

			// Trigger a collection filter event, causing hiding/unhiding
			// of the Todo view items
			Todos.trigger('filter');
		}


	});

	return Workspace;
});
