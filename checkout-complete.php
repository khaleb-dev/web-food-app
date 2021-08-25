<?PHP 
	session_start();
	
	include_once("admin/connection/connection.php");
	
	$session_id = session_id();
	
	$status_message = '';
	
	$sql = "SELECT sc.id, sc.product_id, sc.unit_price, sc.quantity, sc.shipping_fee, p.stock_no, p.name, p.image_url FROM shopping_cart sc INNER JOIN product p ON sc.product_id = p.id WHERE session_id = '{$session_id}'";
	$stmt = $dbh->query($sql);
	$shopping_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($shopping_cart) > 0)
	{		
		if(isset($_SESSION['cus_id']))
		{
			if(isset($_SESSION['post']))
			{
				$_POST = $_SESSION['post'];
				unset($_SESSION['post']);
				
			}
			record_order($_SESSION['cus_id']);
			
			$sql = "DELETE FROM shopping_cart WHERE session_id = :session_id";
			$stmt = $dbh->prepare($sql);
			$result_set = $stmt->execute(array(
			  ':session_id' => $session_id
			));
		}
		else
		{
			// if(isset($_POST['rdCustomerType']) && $_POST['rdCustomerType'] == 'Register')
			// {
				$_SESSION['post'] = $_POST;
				header('Location: login.php?checkout');
			// }
			// else if(isset($_POST['rdCustomerType']) && $_POST['rdCustomerType'] == 'Guest')
			// {
			// 	record_order(0);
				
			// 	$sql = "DELETE FROM shopping_cart WHERE session_id = :session_id";
			// 	$stmt = $dbh->prepare($sql);
			// 	$result_set = $stmt->execute(array(
			// 	  ':session_id' => $session_id
			// 	));
			// }
		}
	}
	else
	{
		$status_message = 'Dear Customer, your shopping cart is empty, there is nothing to checkout. Please add items to your shopping cart.';	
	}
	
	function record_order($customer_id)
	{
		global $session_id;
		global $dbh;
		global $shopping_cart;
		
		if(isset($_POST['txtCompanyName']))
		{
			$state_id = $_POST['downState'];
			$company_name = $_POST['txtCompanyName'];
			$email = $_POST['txtEmail'];
			$title = $_POST['txtTitle'];
			$first_name = $_POST['txtFirstName'];
			$middle_name = $_POST['txtMiddleName'];
			$last_name = $_POST['txtLastName'];
			$address = $_POST['txtAddress'];
			$phone_no = $_POST['txtPhoneNo'];
			$fax = $_POST['txtFax'];
			$special_note = $_POST['txtSpecialNote'];
			
			$sql = "INSERT INTO billing_detail(state_id, company_name, email, title, first_name, middle_name, last_name, address, phone_no, fax, special_note, date) VALUES(:state_id, :company_name, :email, :title, :first_name, :middle_name, :last_name, :address, :phone_no, :fax, :special_note, NOW())";
			$sth = $dbh->prepare($sql);
			$sth->bindParam(':state_id', $state_id, PDO::PARAM_INT, 5);
			$sth->bindParam(':company_name', $company_name, PDO::PARAM_STR, 100);
			$sth->bindParam(':email', $email, PDO::PARAM_STR, 50);
			$sth->bindParam(':title', $title, PDO::PARAM_STR, 50);
			$sth->bindParam(':first_name', $first_name, PDO::PARAM_STR, 50);
			$sth->bindParam(':middle_name', $middle_name, PDO::PARAM_STR, 50);
			$sth->bindParam(':last_name', $last_name, PDO::PARAM_STR, 50);
			$sth->bindParam(':address', $address, PDO::PARAM_STR, 100);
			$sth->bindParam(':phone_no', $phone_no, PDO::PARAM_STR, 20);
			$sth->bindParam(':fax', $fax, PDO::PARAM_STR, 20);
			$sth->bindParam(':special_note', $special_note, PDO::PARAM_STR, 200);
			$sth->execute();
			
			$billing_id = $dbh->lastInsertId();
			
			$total_items = 0;
			$total_amount = 0;
			$shipping_fee = 0;
			
			foreach($shopping_cart as $cart)
			{
				$total_items += $cart['quantity'];
				$total_amount += $cart['quantity']*$cart['unit_price'];
				$shipping_fee += $cart['shipping_fee'];
			}
			$invoice_no = get_invoice_no();
			$payment_type = $_POST['rbPayment'];
			$sql = "INSERT INTO invoice(billing_id, customer_id, invoice_no, total_items, total_amount, shipping_fee, payment_type, date) VALUES(:billing_id, :customer_id, :invoice_no, :total_items, :total_amount, :shipping_fee, :payment_type, NOW())";
			$sth = $dbh->prepare($sql);
			$sth->bindParam(':billing_id', $billing_id, PDO::PARAM_INT, 5);
			$sth->bindParam(':customer_id', $customer_id, PDO::PARAM_INT, 5);
			$sth->bindParam(':invoice_no', $invoice_no, PDO::PARAM_STR, 10);
			$sth->bindParam(':total_items', $total_items, PDO::PARAM_STR, 15);
			$sth->bindParam(':total_amount', $total_amount, PDO::PARAM_STR, 15);
			$sth->bindParam(':shipping_fee', $shipping_fee, PDO::PARAM_STR, 15);
			$sth->bindParam(':payment_type', $payment_type, PDO::PARAM_STR, 30);
			$sth->execute();
			
			$invoice_id = $dbh->lastInsertId();
			foreach($shopping_cart as $cart)
			{
				$total_items += $cart['quantity'];
				$total_amount += $cart['quantity']*$cart['unit_price'];
				
				$sql = "INSERT INTO invoice_detail(invoice_id, product_id, quantity, total_amount, shipping_fee) VALUES(:invoice_id, :product_id, :quantity, :total_amount, :shipping_fee)";
				$sth = $dbh->prepare($sql);
				$sth->bindParam(':invoice_id', $invoice_id, PDO::PARAM_INT, 5);
				$sth->bindParam(':product_id', $cart['product_id'], PDO::PARAM_INT, 5);
				$sth->bindParam(':quantity', $cart['quantity'], PDO::PARAM_STR, 15);
				$sth->bindParam(':total_amount', $cart['unit_price'], PDO::PARAM_STR, 15);
				$sth->bindParam(':shipping_fee', $cart['shipping_fee'], PDO::PARAM_STR, 15);
				$sth->execute();
			}
		}
	}
	
	function get_invoice_no()
	{
		global $dbh;
        $sql = "SELECT invoice_no FROM invoice ORDER BY date DESC LIMIT 0, 1";
        $stmt = $dbh->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$invoice_no = 1;
		if(isset($result['invoice_no']))
			$invoice_no = intval($result['invoice_no'])+1;
			
		return str_pad($invoice_no, 5, '0', STR_PAD_LEFT);
	}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Cart | Jarvites e-Shop</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
</head><!--/head-->

<body>
	<header id="header"><!--header-->
		<div class="header_top"><!--header_top-->
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="contactinfo">
							<ul class="nav nav-pills">
								<li><a href=""><i class="fa fa-phone"></i> +2 95 01 88 821</a></li>
								<li><a href=""><i class="fa fa-envelope"></i> info@domain.com</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="social-icons pull-right">
							<ul class="nav navbar-nav">
								<li><a href=""><i class="fa fa-facebook"></i></a></li>
								<li><a href=""><i class="fa fa-twitter"></i></a></li>
								<li><a href=""><i class="fa fa-linkedin"></i></a></li>
								<li><a href=""><i class="fa fa-dribbble"></i></a></li>
								<li><a href=""><i class="fa fa-google-plus"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header_top-->
		
		<div class="header-middle"><!--header-middle-->
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<div class="logo pull-left">
							<a href="index.php"><img src="images/home/logo.png" alt="" /></a>
						</div>
					</div>
					<div class="col-sm-8">
						<div class="shop-menu pull-right">
							<ul class="nav navbar-nav">
								<li><a href="account.php"><i class="fa fa-user"></i> Account</a></li>
								<li><a href="wishlist.php"><i class="fa fa-star"></i> Wishlist</a></li>
								<li><a href="checkout.php" class="active"><i class="fa fa-crosshairs"></i> Checkout</a></li>
								<li><a href="cart.php"><i class="fa fa-shopping-cart"></i> Cart</a></li>
								<li><a href="login.php<?PHP echo isset($_SESSION['cus_id'])?'?logout':''?>"><i class="fa fa-lock"></i> <?PHP echo isset($_SESSION['cus_id'])?'Logout':'Login'?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-middle-->
	
		<div class="header-bottom"><!--header-bottom-->
			<div class="container">
				<div class="row">
					<div class="col-sm-9">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="mainmenu pull-left">
							<ul class="nav navbar-nav collapse navbar-collapse">
								<li><a href="index.php">Home</a></li>
								<li class="dropdown"><a href="#" class="active">Shop<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="shop.php">Products</a></li>
										<li><a href="checkout.php" class="active">Checkout</a></li> 
										<li><a href="cart.php">Cart</a></li> 
						</ul>
                                </li> 
								
								<li><a href="contact-us.php">Contact</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="search_box pull-right">
							<input type="text" placeholder="Search"/>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-bottom-->
	</header><!--/header-->

	<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="index.php">Home</a></li>
				  <li class="active">Checkout Complete</li>
				</ol>
			</div>
            <?PHP 
				if(!empty($status_message))
				{
			?>
            <div class="step-one">
				<h2 class="heading"><?PHP echo $status_message; ?></h2>
			</div>
            <?PHP 
				}
				else{
			?>
			<div class="step-one">
				<h2 class="heading">Your Order has been received and processing have started. We will get back to you as soon as possible. Thank you.</h2>
			</div>
            <?PHP 
				}
			?>
		</div>
	</section> <!--/#cart_items-->

	<section id="do_action">
		
	</section><!--/#do_action-->

	<footer id="footer"><!--Footer-->
		<div class="footer-top">
			<div class="container">
				<div class="row">
					<div class="col-sm-2">
						<div class="companyinfo">
							<h2><span>e</span>-shopper</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,sed do eiusmod tempor</p>
						</div>
					</div>
					<div class="col-sm-7">
						
					</div>
					<div class="col-sm-3">
						<div class="address">
							<img src="images/home/map.png" alt="" />
							<p>505 S Atlantic Ave Virginia Beach, VA(Virginia)</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="footer-widget">
			<div class="container">
				<div class="row">
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Service</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="">Online Help</a></li>
								<li><a href="">Contact Us</a></li>
								<li><a href="">Order Status</a></li>
								<li><a href="">Change Location</a></li>
								<li><a href="">FAQ’s</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Policies</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="">Terms of Use</a></li>
								<li><a href="">Privecy Policy</a></li>
								<li><a href="">Refund Policy</a></li>
								<li><a href="">Billing System</a></li>
								<li><a href="">Ticket System</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>About Jarvix</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="">Company Information</a></li>
								<li><a href="">Careers</a></li>
								<li><a href="">Store Location</a></li>
								<li><a href="">Affillate Program</a></li>
								<li><a href="">Copyright</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-3 col-sm-offset-1">
						<div class="single-widget"><br/><br/>
							<form action="#" class="searchform">
								<input type="text" placeholder="Your email address" />
								<button type="submit" class="btn btn-default"><i class="fa fa-arrow-circle-o-right"></i></button>
								<p>Eat your best food anytime.<br />We'll deliver to your doorstep.</p>
							</form>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="footer-bottom">
			<div class="container">
				<div class="row">
					<p class="pull-left">Copyright © <?= date('Y') ?>. All rights reserved.</p>
					<p class="pull-right">Designed by <span><a  href="#">GodsPower</a></span></p>
				</div>
			</div>
		</div>
		
	</footer><!--/Footer-->
	


    <script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>
</html>