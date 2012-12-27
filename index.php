<?php

require 'api/Slim.php';

$app = new Slim();
$app->config(array(
	'templates.path' => './'
));

$app->add(new Slim_Middleware_SessionCookie(array(
	'expires' => '20 Minutes',
	'path' => '/',
	'domain' => null,
	'secure' => false,
	'httponly' => false,
	'name' => 'app',
	'secret' => 'md5secretstuff',
	'cipher' => MCRYPT_RIJNDAEL_256,
	'cipher_mode' => MCRYPT_MODE_CBC
)));

$app->get('/', function () use ($app) {

	if ( isset($_SESSION['is_logged_in']) ) {
		$data['is_logged_in'] = true;
	} else {
		$data['is_logged_in'] = false;
	}

	if ( isset($_SESSION['login_failure']) ) {
		$data['login_failure'] = true;
	} else {
		$data['login_failure'] = false;
	}

	$app->render('main.php', $data);

});

$app->get('/api/todos/', function () use ($app) {

	$connect = mysql_connect("localhost", "root", "root");

    if(!$connect){

        die(mysql_error());

    }


    //Selecting database

    $select_db = mysql_select_db("todoToptal", $connect);

	$user = $_SESSION['name'];

	$query = mysql_query("SELECT todosList.completed, todosList.content, todosList.priority, todosList.remaining, todosList.id FROM todosList INNER JOIN users ON (todosList.userID = users.id) WHERE users.username = '$user' AND todosList.status = 1");

	if(!$query){

        die(mysql_error());

    }

    $rows = array();
	while($r = mysql_fetch_assoc($query)) {
		if($r['completed'] == 0){
			$r['completed'] = false;
		}else{
			$r['completed'] = true;
		}
	    $rows[] = array('completed'=>$r['completed'],'content'=>$r['content'],'priority'=>$r['priority'],'remaining'=>$r['remaining'],'id'=>$r['id']);
	}

	$response = $app->response();
	$response['Content-Type'] = 'application/json';
	$response->status(200);

	$response->body(json_encode($rows));


});

$app->post('/api/todos/', function () use ($app) {
	$requestBody = $app->request()->getBody();  // <- getBody() of http request
    $json_a = json_decode($requestBody, true);

	$connect = mysql_connect("localhost", "root", "root");

    if(!$connect){

        die(mysql_error());

    }
    //Selecting database

    $select_db = mysql_select_db("todoToptal", $connect);
	$user = $_SESSION['name'];
	$query = mysql_query("SELECT id FROM users WHERE username = '$user'");
	$userids = mysql_fetch_assoc($query);
	$userid = $userids['id'];

	$content = $json_a['content'];
	$priority = $json_a['priority'];

	$insert = mysql_query("INSERT INTO todosList (userID, content, priority) VALUES ('$userid', '$content', '$priority')");

	$response = $app->response();
	$response['Content-Type'] = 'application/json';
	$response->status(200);

	$response->body(json_encode(array('id' => mysql_insert_id())));

});

$app->put('/api/todos/:id', function ($id) use ($app) {
	$requestBody = $app->request()->getBody();  // <- getBody() of http request
    $json_a = json_decode($requestBody, true);

	$connect = mysql_connect("localhost", "root", "root");

    if(!$connect){

        die(mysql_error());

    }
    //Selecting database

    $select_db = mysql_select_db("todoToptal", $connect);

	$content = $json_a['content'];
	$priority = $json_a['priority'];
	$completed = $json_a['completed'];
	$remaining = $json_a['remaining'];
	if($completed){
		$completed = 1;
	}else{
		$completed = 0;
	}

	//$ids = mysql_real_escape_string($id);

	$insert = mysql_query("UPDATE todosList SET content='$content',priority='$priority',completed='$completed',remaining='$remaining' WHERE id = '$id'");

	$response = $app->response();
	$response['Content-Type'] = 'application/json';
	$response->status(200);

	$response->body(json_encode($json_a));

});

$app->delete('/api/todos/:id', function ($id) use ($app) {
	$requestBody = $app->request()->getBody();  // <- getBody() of http request
    $json_a = json_decode($requestBody, true);

	$connect = mysql_connect("localhost", "root", "root");

    if(!$connect){

        die(mysql_error());

    }
    //Selecting database

    $select_db = mysql_select_db("todoToptal", $connect);

	$insert = mysql_query("UPDATE todosList SET status=0 WHERE id = '$id'");

	$response = $app->response();
	$response['Content-Type'] = 'application/json';
	$response->status(200);

	$response->body(json_encode($json_a));

});

$app->post('/auth/process', function () use ($app) {
    $connect = mysql_connect("localhost", "root", "root");

    if(!$connect){

        die(mysql_error());

    }


    //Selecting database

    $select_db = mysql_select_db("todoToptal", $connect);

    //FETCHING DATA
    
	if ( isset($_POST['name']) ) {
		$Name = $_POST['name'];

		//Let's check if this username is already in use


		$user_check = mysql_query("SELECT username FROM users WHERE username='$Name'");

		$do_user_check = mysql_num_rows($user_check);

		if($do_user_check > 0){

			die("Username is already in use!<br>");

		}


		if(empty($Name)){

			die("Please enter your username!<br>");

		}
	} else {
		$Name = '';
	}

	$Pass = $_POST['password'];
	$Email = $_POST['email'];



    //Here we will check do we have all inputs filled


	if(empty($Pass)){

	die("Please enter your password!<br>");

	}
	//encrypt pass
	$Pass = md5($Pass);


	if(empty($Email)){

	die("Please enter your email!");

	}

	


	//Now if email is already in use


	$email_check = mysql_query("SELECT email, password, username FROM users WHERE email='$Email'");

	$do_email_check = mysql_num_rows($email_check);


	if($do_email_check > 0){
		if(empty($Name)){
			$checkPassEnmail = mysql_fetch_assoc($email_check);
			if ($Pass == $checkPassEnmail['password']) {
				$_SESSION['is_logged_in'] = true;
				$_SESSION['email'] = $Email;
				$_SESSION['name'] = $checkPassEnmail['username'];
				$_SESSION['login_failure'] = false;
				$app->redirect('/#app');

			} else {

				$_SESSION['login_failure'] = true;
				$app->redirect('/#login');

			}
		}else{
			die("this email is already registered, try to log in instead");
		}

	}

	//If everything is okay let's register this user


	$insert = mysql_query("INSERT INTO users (username, password, email) VALUES ('$Name', '$Pass', '$Email')");

	if(!$insert){

		die("There's little problem: ".mysql_error());

	}

	//Now if everything is correct let's finish his/her/its login


	$_SESSION['is_logged_in'] = true;
	$_SESSION['email'] = $Email;
	$_SESSION['name'] = $Name;

	$app->redirect('/#app');
	
});

$app->get('/logout', function () use ($app) {

	session_unset();
	$app->redirect('/#login');

});

$app->run();