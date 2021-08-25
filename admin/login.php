<?PHP
	session_start();
	
	include_once('models/functions.php');
	
	$error_message = '';
	if(isset($_SESSION['user_id']))
	{
		unset($_SESSION['user_id']);
	}
	if(isset($_POST['btnSubmit']))
	{
		$login = login($_POST['txtUserName'], $_POST['txtPassword']);
		if($login)
		{
			$_SESSION['user_id'] = $login['id'];
			$_SESSION['user_name'] = $login['user_name'];
			$_SESSION['is_admin'] = $login['is_admin'];
			$_SESSION['login_time'] = date('h:i:s');
			
			header('Location: index.php');
		}
		else
		{
			$error_message = 'Invalid username or password';	
		}
	}

?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Login - Secondary Education Board</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!--base css styles-->
        <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="assets/bootstrap/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/normalize/normalize.css">

        <!--page specific css styles-->

        <!--flaty css styles-->
        <link rel="stylesheet" href="css/flaty.css">
        <link rel="stylesheet" href="css/flaty-responsive.css">

        <link rel="shortcut icon" href="img/favicon.html">

        <script src="assets/modernizr/modernizr-2.6.2.min.js"></script>
    </head>
    <body class="login-page">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- BEGIN Main Content -->
        <div class="login-wrapper">
            <!-- BEGIN Login Form -->
            <form id="form-login" method="POST">
                <h3>Login to your account</h3>
                <hr/>
                <?PHP if(!empty($error_message)){?>
                	<div class="alert alert-error">
                    <button class="close" data-dismiss="alert">×</button><?PHP echo $error_message;?></div>
                <?PHP }?>
                <div class="control-group">
                    <div class="controls">
                        <input type="text" autofocus placeholder="Username" value="<?PHP echo isset($_POST['txtUserName'])?$_POST['txtUserName']:''?>" required name="txtUserName" id="txtUserName" class="input-block-level" />
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <input type="password" placeholder="Password" value="<?PHP echo isset($_POST['txtPassword'])?$_POST['txtPassword']:''?>" required name="txtPassword" id="txtPassword" class="input-block-level" />
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox">
                            <input type="checkbox" name="cbRemember" id="cbRemember" value="remember" /> Remember me
                        </label>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary input-block-level">Sign In</button>
                    </div>
                </div>
            </form>
            <!-- END Login Form -->
        </div>
        <!-- END Main Content -->

        <!--basic scripts-->
        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->
        <script>window.jQuery || document.write('<script src="assets/jquery/jquery-1.10.1.min.js"><\/script>')</script>
        <script src="assets/bootstrap/bootstrap.min.js"></script>
    </body>
</html>
