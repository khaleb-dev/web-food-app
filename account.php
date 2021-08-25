<?PHP
	session_start();
	if(!isset($_SESSION['cus_id']))
		header('Location: login.php');


    include_once("admin/connection/connection.php");
	
	if(isset($_POST['btnUpdate']))
	{
		$full_name = strip_tags($_POST['txtFullName']);
        $email = strip_tags($_POST['txtEmail']);
        $phone_no = strip_tags($_POST['txtPhoneNo']);
        $password = strip_tags($_POST['txtPassword']);
        $confirm_password = strip_tags($_POST['txtConfirmPassword']);
		
		if($confirm_password == $password)
		{
			$sql = "SELECT email FROM customer WHERE email = :email";
			$stmt = $dbh->prepare($sql);
			$result = $stmt->execute(array(
			  ':email' => $email
			));
	
			if($stmt->rowCount() > 1){
			  echo $email." already exist in our database try logging in";
			}else {
			  $sql = "UPDATE customer SET full_name = :full_name, email = :email, password = :password, phone_no = :phone_no, updated = NOW() WHERE id = :id";
			  $sth = $dbh->prepare($sql);
			  $sth->bindParam(':full_name', $full_name, PDO::PARAM_STR, 50);
			  $sth->bindParam(':email', $email, PDO::PARAM_STR, 50);
			  $sth->bindParam(':password', $password, PDO::PARAM_STR, 20);
			  $sth->bindParam(':phone_no', $phone_no, PDO::PARAM_STR, 20);
			  $sth->bindParam(':id', $_SESSION['cus_id'], PDO::PARAM_INT, 5);
			  if($sth->execute()){
			  }
			}
		}
	}
	
	$sql = "SELECT * FROM customer WHERE id = {$_SESSION['cus_id']}";
	$stmt = $dbh->query($sql);
	$cus = $stmt->fetch(PDO::FETCH_ASSOC);
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login | Jarvites e-Shop</title>
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
								<li><a href="account.php" class="active"><i class="fa fa-user"></i> Account</a></li>
								<li><a href="wishlist.php"><i class="fa fa-star"></i> Wishlist</a></li>
								<li><a href="checkout.php"><i class="fa fa-crosshairs"></i> Checkout</a></li>
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
								<li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="shop.php">Products</a></li>
										<li><a href="checkout.php">Checkout</a></li> 
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
	
	<section id="form"><!--form-->
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<div class="signup-form"><!--sign up form-->
						<h2>Your Account!</h2>
						<form action="#" method="post">
							<input type="text" required value="<?PHP echo $cus['full_name'] ?>" name="txtFullName" placeholder="Full Name"/>
							<input type="email" required value="<?PHP echo $cus['email'] ?>" name="txtEmail" placeholder="Email Address"/>
							<input type="phoneNo" required value="<?PHP echo $cus['phone_no'] ?>" name="txtPhoneNo" placeholder="Phone No"/>
							<input type="password" value="<?PHP echo $cus['password'] ?>" required name="txtPassword" placeholder="Password"/>
							<input type="password" value="<?PHP echo $cus['password'] ?>" required data-rule-equalTo="#txtPassword" name="txtConfirmPassword" placeholder="Confirm Password"/>
							<button type="submit" name="btnUpdate" class="btn btn-default">Update</button>
						</form>
					</div><!--/sign up form-->
				</div>
			</div>
		</div>
	</section><!--/form-->
	
	<section id="cart_items">
		<div class="container">			
			<div class="review-payment">
				<h2>Your Purchase History</h2>
			</div>

			<div class="table-responsive cart_info">
				<table class="table table-condensed">
					<thead>
                        <tr>
                          <th style="width: 5%">S/N</th>
                          <th style="width: 15%">Customer Name</th>
                          <th style="width: 10%">Invoice No</th>
                          <th style="width: 10%">Payment Type</th>
                          <th style="width: 10%">Date</th>
                          <th style="width: 20%">Billing Detail</th>
                          <th style="width: 20%">Items</th>
                        </tr>
                    </thead>
					<tbody>
                  <?PHP
                    $sql = "SELECT * FROM invoice WHERE customer_id = '{$_SESSION['cus_id']}'";
                    $stmt = $dbh->query($sql);
                    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  
                    $counter = 1;
                    foreach($invoices as $invoice) {?>
                        <?PHP
                            $customer_name = '';
                            if($invoice['customer_id'] > 0){
                                $sql = "SELECT full_name FROM customer WHERE id = '{$invoice['customer_id']}'";
                                $stmt = $dbh->query($sql);
                                $customer = $stmt->fetch(PDO::FETCH_ASSOC);
                                
                                if($customer['full_name'])
                                    $customer_name = $customer['full_name'];
                            }
                            
                            $billing = '';
                            
                            $sql = "SELECT * FROM billing_detail WHERE id = '{$invoice['billing_id']}'";
                            $stmt = $dbh->query($sql);
                            $bill = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if($bill['email'])
                            {
                                if(!empty($bill['company_name']))
                                    $billing = 'Company Name: '.$bill['company_name'].'<br/>';
                                $billing .= 'Full Name: '.$bill['title'].' '.$bill['first_name'].' '.$bill['middle_name'].' '.$bill['last_name'].'<br/>';
                                $billing .= 'Email: '.$bill['email'].'<br/>';
                                $billing .= 'Address: '.$bill['address'].'<br/>';
                                $billing .= 'Phone No: '.$bill['phone_no'].'<br/>';
                                if(!empty($bill['fax']))
                                    $billing .= 'Fax: '.$bill['fax'].'<br/>';
                                if(!empty($bill['special_note']))
                                    $billing .= 'Special Note: '.$bill['special_note'].'<br/>';
                                
                            }
                        ?>
                      <tr class="table-flag-blue">
                          <td><?PHP echo $counter++; ?></td>
                          <td><?PHP echo $customer_name; ?></td>
                          <td><?PHP echo $invoice['invoice_no']; ?></td>
                          <td><?PHP echo $invoice['payment_type']; ?></td>
                          <td><?PHP echo date('d/m/Y h:i:s', strtotime($invoice['date'])); ?></td>
                          <td><?PHP echo $billing; ?></td>
                          <td><?PHP 
                            $invoice_detail = '';
                            $sql = "SELECT p.name, id.quantity, id.total_amount, id.shipping_fee FROM invoice_detail id INNER JOIN product p ON p.id = id.product_id WHERE id.invoice_id = '{$invoice['id']}'";
                            $stmt = $dbh->query($sql);
                            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            $total_amount = 0;
							foreach($products as $product)
							{
								$invoice_detail = $product['name'].' [Qty: '.$product['quantity'].', N'.$product['total_amount'].']<br/>';
								$total_amount += $product['total_amount'];
							}
							echo $invoice_detail.'<br/><strong>Total Amount: N'.$total_amount.'</strong>';
                           ?></td>
                      </tr>
                    <?PHP } ?>
            
                </tbody>
				</table>
			</div>
			
		</div>
	</section> <!--/#cart_items-->
    
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
	<script src="js/price-range.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>
</html>