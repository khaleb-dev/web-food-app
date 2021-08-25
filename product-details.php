<?PHP
	session_start();

    include_once("admin/connection/connection.php");
	
	if(!isset($_GET['pid']))
	{
		header('Location: index.php');
	}
	
	$sql = "SELECT p.id, b.name AS brand, c.name AS category, p.unit_price, p.shipping_fee, p.stock_no, p.name, p.image_url, p.description FROM product p INNER JOIN product_category c ON c.id = p.category_id INNER JOIN brand b ON b.id = p.brand_id WHERE p.id = {$_GET['pid']}";
	$stmt = $dbh->query($sql);
	$product = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if(!isset($product['id']))
	{
		header('Location: index.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Product Details | Jarvites e-Shop</title>
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
										<li><a href="product-details.php" class="active">Product Details</a></li> 
										<li><a href="checkout.php">Checkout</a></li> 
										<li><a href="cart.php">Cart</a></li> </ul>
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
	
	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="left-sidebar">
						<h2>Category</h2>
						<div class="panel-group category-products" id="accordian"><!--category-productsr-->
                        
                            <?PHP include_once("admin/models/functions.php"); ?>
								  <?PHP
                                    $sql = "SELECT id, name, description FROM product_category WHERE parent_id = 0";
                                    $stmt = $dbh->query($sql);
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                  
                                    foreach($result as $category) {
										
                                    $sql = "SELECT id, name, description FROM product_category WHERE parent_id = {$category['id']}";
                                    $stmt = $dbh->query($sql);
                                    $result1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
										if(!count($result1))
									{
										?>
                                          
                                          
                                          <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title"><a href="shop.php?cid=<?PHP echo $category['id']; ?>"><?PHP echo $category['name']; ?></a></h4>
                                            </div>
                                         <?PHP }else{ ?>
                                            <div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordian" href="#design<?PHP echo $category['id']; ?>">
											<span class="badge pull-right"><i class="fa fa-plus"></i></span>
											<?PHP echo $category['name']; ?>
										</a>
									</h4>
								</div>
                                            
                                            <?PHP
										 }
								  	if(count($result1))
									{
										?>
                                        <div id="design<?PHP echo $category['id']; ?>" class="panel-collapse collapse">
									<div class="panel-body">
										<ul>
                                        <?PHP
										
										foreach($result1 as $sub) { ?>
											  
											<li><a href="index.php?cid=<?PHP echo $sub['id']; ?>"><?PHP echo $sub['name']; ?></a></li>												
												
										<?PHP } ?>
										</ul>
									</div>
								</div>
									<?PHP } ?>
                                            
                                        </div>
                                    <?PHP } ?>
							
							
						</div><!--/category-products-->
					
						<div class="brands_products"><!--brands_products-->
							<h2>Brands</h2>
							<div class="brands-name">
								<ul class="nav nav-pills nav-stacked">
								  <?PHP
                                    $sql = "SELECT id, name, description FROM brand";
                                    $stmt = $dbh->query($sql);
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                  
                                    foreach($result as $brand) {?>
                                          <li><a href="shop.php?bid=<?PHP echo $brand['id']; ?>"> <span class="pull-right">(<?PHP echo getTotalBrandProducts($brand['id']); ?>)</span><?PHP echo $brand['name']; ?></a></li>
                                    <?PHP } ?>
								</ul>
							</div>
						</div><!--/brands_products-->
						
						<div class="price-range"><!--price-range-->
							<h2>Price Range</h2>
							<div class="well">
								 <input type="text" class="span2" value="" data-slider-min="0" data-slider-max="600" data-slider-step="5" data-slider-value="[250,450]" id="sl2" ><br />
								 <b>N 0</b> <b class="pull-right">N 600</b>
							</div>
						</div><!--/price-range-->
						
						<div class="shipping text-center"><!--shipping-->
							<img src="images/home/shipping.jpg" alt="" />
						</div><!--/shipping-->
						
					</div>
				</div>
				<?PHP 
							
						
						 ?>
				<div class="col-sm-9 padding-right">
					<div class="product-details"><!--product-details-->
						<div class="col-sm-5">
							<div class="view-product">
								<img width="266" height="381" src="img/uploads/<?PHP echo $product['image_url']; ?>" alt="<?PHP echo $product['name']; ?>" />
								<h3>ZOOM</h3>
							</div>
							

						</div>
						<div class="col-sm-7">
							<div class="product-information"><!--/product-information-->
								<h2><?PHP echo $product['name']; ?></h2>
								<p>Stock No: <?PHP echo $product['stock_no']; ?></p>
								<span>
									<span>N<?PHP echo $product['unit_price']; ?></span>
									<a href="cart.php?pid=<?PHP echo $product['id']; ?>" class="btn btn-fefault cart">
										<i class="fa fa-shopping-cart"></i>
										Add to cart
									</a>
								</span>
								<p><b>Description:</b> <?PHP echo $product['description']; ?></p>
								<p><b>Category:</b> <?PHP echo $product['category']; ?></p>
								<p><b>Brand:</b> <?PHP echo $product['brand']; ?></p>
								<p><b>Shipping Fee:</b> <?PHP echo $product['shipping_fee']; ?></p>
								<a href=""><img src="images/product-details/share.png" class="share img-responsive"  alt="" /></a>
							</div><!--/product-information-->
						</div>
					</div><!--/product-details-->
					
					
					<div class="recommended_items"><!--recommended_items-->
						<h2 class="title text-center">recommended items</h2>
						
						<div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
							<div class="carousel-inner">
                            <div class="item active">
							 <?PHP 
								$sql = "SELECT * FROM product WHERE is_recommended = 1";
								$stmt = $dbh->query($sql);
								$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
								$counter = 0;
								foreach($result as $product)
								{
									if($counter++ % 3 == 0 && $counter > 3){
									 ?>
                                     	</div>
										<div class="item">	
									<?PHP }?>
                                    <div class="col-sm-4">
										<div class="product-image-wrapper">
											<div class="single-products">
												<div class="productinfo text-center">
													<img width="268" height="134" src="img/uploads/<?PHP echo $product['image_url'] ?>" alt="<?PHP echo $product['name'] ?>" />
													<h2>N<?PHP echo $product['unit_price'] ?></h2>
													<p><?PHP echo $product['name'] ?></p>
													<a href="cart.php?pid=<?PHP echo $product['id'] ?>" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
												</div>
												
											</div>
										</div>
									</div>
									
							
                            <?PHP }?></div>
							</div>
							 <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
								<i class="fa fa-angle-left"></i>
							  </a>
							  <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
								<i class="fa fa-angle-right"></i>
							  </a>			
						</div>
					</div><!--/recommended_items-->
					
				</div>
			</div>
		</div>
	</section>
	
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