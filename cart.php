<?PHP 
	session_start();
	
	include_once("admin/connection/connection.php");
	
	$session_id = session_id();
	if(isset($_GET['apid']) && isset($_GET['a']))
	{
		if($_GET['a'] == 'add')
		{
			$sql = "UPDATE shopping_cart SET quantity = quantity+1 WHERE product_id = :product_id AND session_id = :session_id";
			$stmt = $dbh->prepare($sql);
			$result_set = $stmt->execute(array(
			  ':product_id' => $_GET['apid'],
			  ':session_id' => $session_id
			));
		}
		else if($_GET['a'] == 'sub')
		{
			$sql = "SELECT quantity FROM shopping_cart WHERE product_id = {$_GET['apid']} AND session_id = '{$session_id}'";
			$stmt = $dbh->query($sql);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if(isset($result['quantity']) && $result['quantity'] > 1)	
			{
				$sql = "UPDATE shopping_cart SET quantity = quantity-1 WHERE product_id = :product_id AND session_id = :session_id";
				$stmt = $dbh->prepare($sql);
				$result_set = $stmt->execute(array(
				  ':product_id' => $_GET['apid'],
				  ':session_id' => $session_id
				));
			}
		}
		else if($_GET['a'] == 'del')
		{
			$sql = "DELETE FROM shopping_cart WHERE product_id = :product_id AND session_id = :session_id";
			$stmt = $dbh->prepare($sql);
			$result_set = $stmt->execute(array(
			  ':product_id' => $_GET['apid'],
			  ':session_id' => $session_id
			));
		}
		header('Location: cart.php');
	}
	
	if(isset($_GET['pid']))
	{
		$sql = "SELECT * FROM product WHERE id = {$_GET['pid']}";
		$stmt = $dbh->query($sql);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if(isset($result['id']))
		{
			$sql = "SELECT * FROM shopping_cart WHERE product_id = {$result['id']}";
			$stmt = $dbh->query($sql);
			$result1 = $stmt->fetch(PDO::FETCH_ASSOC);
		
			if(!isset($result1['id']))
			{
			  $sql = "INSERT INTO shopping_cart(product_id, session_id, quantity, unit_price, shipping_fee, date) VALUES(:product_id, :session_id, 1, :unit_price, :shipping_fee, NOW())";
			  $sth = $dbh->prepare($sql);
			  $sth->bindParam(':product_id', $result['id'], PDO::PARAM_INT, 5);
			  $sth->bindParam(':session_id', $session_id, PDO::PARAM_STR, 150);
			  $sth->bindParam(':unit_price', $result['unit_price'], PDO::PARAM_STR, 15);
			  $sth->bindParam(':shipping_fee', $result['shipping_fee'], PDO::PARAM_STR, 15);
			  $sth->execute();
			  
			}
		}
		
		header('Location: cart.php');
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
								<li><a href="checkout.php"><i class="fa fa-crosshairs"></i> Checkout</a></li>
								<li><a href="cart.php" class="active"><i class="fa fa-shopping-cart"></i> Cart</a></li>
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
										<li><a href="checkout.php">Checkout</a></li> 
										<li><a href="cart.php" class="active">Cart</a></li> 
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
				  <li class="active">Shopping Cart</li>
				</ol>
			</div>
			<div class="table-responsive cart_info">
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Item</td>
							<td class="description"></td>
							<td class="price">Price</td>
							<td class="quantity">Quantity</td>
							<td class="total">Amount</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?PHP 
							$sql = "SELECT sc.id, sc.product_id, sc.unit_price, sc.shipping_fee, sc.quantity, p.stock_no, p.name, p.image_url FROM shopping_cart sc INNER JOIN product p ON sc.product_id = p.id WHERE session_id = '{$session_id}'";
							$stmt = $dbh->query($sql);
							$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
						
							$total = 0;
							$sf = 0;
							foreach($result as $cart)
							{
						?>
						<tr>
							<td class="cart_product">
								<a href=""><img width="110" height="110" src="img/uploads/<?PHP echo $cart['image_url'] ?>" alt="<?PHP echo $cart['name'] ?>"></a>
							</td>
							<td class="cart_description">
								<h4><a href="product_details.php?pid=<?PHP echo $cart['product_id'] ?>"><?PHP echo $cart['name'] ?></a></h4>
								<p>Stock No: <?PHP echo $cart['stock_no'] ?></p>
							</td>
							<td class="cart_price">
								<p>N<?PHP echo $cart['unit_price'] ?></p>
							</td>
							<td class="cart_quantity">
								<div class="cart_quantity_button">
									<a class="cart_quantity_up" href="cart.php?apid=<?PHP echo $cart['product_id'] ?>&a=add"> + </a>
									<input class="cart_quantity_input" type="text" name="quantity" value="<?PHP echo $cart['quantity'] ?>" autocomplete="off" size="2">
									<a class="cart_quantity_down" href="cart.php?apid=<?PHP echo $cart['product_id'] ?>&a=sub"> - </a>
								</div>
							</td>
							<td class="cart_total">
								<p class="cart_total_price">N<?PHP echo $cart['unit_price']*$cart['quantity'] ?></p>
							</td>
							<td class="cart_delete">
								<a class="cart_quantity_delete" href="cart.php?apid=<?PHP echo $cart['product_id'] ?>&a=del"><i class="fa fa-times"></i></a>
							</td>
						</tr>
                        <?PHP 
	                        	$sf = $cart['shipping_fee'];
								$total += $cart['unit_price']*$cart['quantity'];
						
							}
						?>
                        
                        <tr>
							<td colspan="4">&nbsp;</td>
							<td colspan="2">
								<table class="table table-condensed total-result">
									<tr>
										<td>Cart Sub Total</td>
										<td>N<?PHP echo $total ?></td>
									</tr>
									<tr class="shipping-cost">
										<td>Shipping Cost</td>
										<td>N<?PHP echo $sf ?></td>										
									</tr>
									<tr>
										<td>Total</td>
										<td><span>N<?PHP echo $total+$sf ?></span></td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
                <a class="btn btn-default check_out pull-left" href="checkout.php">Check Out</a>
                <a class="btn btn-default check_out pull-right" href="checkout.php">Check Out</a>
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