<!doctype html>
<html lang="en">
	<script type="text/javascript">

		var auth = function() {};
		auth.is_logged_in = <?=($is_logged_in ? 'true' : 'false')?>;
		auth.login_failure = <?=($login_failure ? 'true' : 'false')?>;
	</script>
	<?php 
	if ( isset($user)) {
		echo($user);
	}
	?>
	<script data-main="main" src="/lib/require/require.js"></script>

	<title>Todo List Toptal</title>
	</head>

	<body id="app">
		<section id="todoapp">
			<header id="header">
				<h1>You've successfully logged in!</h1>
				<a class="btn" href="/logout">Log out</a>
				<h1>todos</h1>
				<input id="new-todo" placeholder="What needs to be done?" autofocus>
			</header>
			<section id="main">
				<input id="toggle-all" type="checkbox">
				<label for="toggle-all">Mark all as complete</label>
				<ul id="todo-list"></ul>
			</section>
			<footer id="footer"></footer>
		</section>
	</body>
</html>