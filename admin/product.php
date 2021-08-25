<?PHP
	session_start();
	if(!isset($_SESSION['user_id']))
		header('Location: login.php');

  include_once("connection/connection.php");

  $status_message = '';
  if(isset($_POST['btnRegister'])){
      if(isset($_POST['txtName']) && isset($_POST['txtDescription'])){
        $brand_id = strip_tags($_POST['downBrand']);
        $category_id = strip_tags($_POST['downProductCategory']);
        $stock_no = strip_tags($_POST['txtStockNo']);
        $name = strip_tags($_POST['txtName']);
        $description = strip_tags($_POST['txtDescription']);
        $unit_price = strip_tags($_POST['txtUnitPrice']);
        $shipping_fee = strip_tags($_POST['txtShippingFee']);
        $is_recommended = isset($_POST['cbRecommended'])?1:0;
        $is_featured = isset($_POST['cbFeatured'])?1:0;

        $sql = "SELECT name FROM product WHERE stock_no = :stock_no";
        $stmt = $dbh->prepare($sql);
        //$result = $stmt->fetch(PDO::FETCH_ASSOC);

       if($stmt->rowCount() > 0){
          $status_message = 'This stock no '.$stock_no." already exist in the database";
        }else {
			
		$product_image = '';
		if(isset($_FILES))
		{
			if(is_uploaded_file($_FILES['fileProductImage']['tmp_name']))
			{
				$file_type = explode("/", $_FILES['fileProductImage']['type']);
				if(in_array($file_type[1], array('png','gif','jpeg','jpg')))
				{
					$product_image = time().".".$file_type[1];
					move_uploaded_file($_FILES['fileProductImage']['tmp_name'], "../img/uploads/".$product_image) or die("could not upload this file");
				}
			}//upload voter's image to the server
		}
			
          $sql = "INSERT INTO product(category_id, user_id, brand_id, stock_no, name, description, image_url, unit_price, shipping_fee, is_recommended, is_featured, created, updated) VALUES(:category_id, :user_id, :brand_id, :stock_no, :name, :description, :image_url, :unit_price, :shipping_fee, :is_recommended, :is_featured, NOW(), NOW())";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':category_id', $category_id, PDO::PARAM_INT, 5);
          $sth->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT, 5);
          $sth->bindParam(':brand_id', $brand_id, PDO::PARAM_INT, 5);
          $sth->bindParam(':stock_no', $stock_no, PDO::PARAM_STR, 10);
          $sth->bindParam(':name', $name, PDO::PARAM_STR, 100);
          $sth->bindParam(':description', $description, PDO::PARAM_STR, 120);
          $sth->bindParam(':image_url', $product_image, PDO::PARAM_STR, 120);
          $sth->bindParam(':unit_price', $unit_price, PDO::PARAM_STR, 20);
          $sth->bindParam(':shipping_fee', $shipping_fee, PDO::PARAM_STR, 20);
          $sth->bindParam(':is_recommended', $is_recommended, PDO::PARAM_INT, 1);
          $sth->bindParam(':is_featured', $is_featured, PDO::PARAM_INT, 1);
          if($sth->execute()){
            //echo "data has been inserted";
            header("Location: view-products.php");
          }
        }

     }
  }

  if(isset($_GET['eid'])){
    $eid = strip_tags($_GET['eid']);
    $sql = "SELECT * FROM product WHERE id = '{$eid}'";
    $stmt = $dbh->query($sql);

    if($stmt->rowCount() > $eid){
      //header("Location: invalid_query.php");
    }
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

  }

  if(isset($_POST['btnUpdate'])){
    $brand_id = strip_tags($_POST['downBrand']);
	$category_id = strip_tags($_POST['downProductCategory']);
	$stock_no = strip_tags($_POST['txtStockNo']);
	$name = strip_tags($_POST['txtName']);
	$description = strip_tags($_POST['txtDescription']);
	$unit_price = strip_tags($_POST['txtUnitPrice']);
    $shipping_fee = strip_tags($_POST['txtShippingFee']);
	$is_recommended = isset($_POST['cbRecommended'])?1:0;
	$is_featured = isset($_POST['cbFeatured'])?1:0;
	$product_image = '';
	if(isset($_FILES))
	{
		if(is_uploaded_file($_FILES['fileProductImage']['tmp_name']))
		{
			$file_type = explode("/", $_FILES['fileProductImage']['type']);
			if(in_array($file_type[1], array('png','gif','jpeg','jpg')))
			{
				$product_image = time().".".$file_type[1];
				move_uploaded_file($_FILES['fileProductImage']['tmp_name'], "../img/uploads/".$product_image) or die("could not upload this file");
			}
		}//upload voter's image to the server
	}
	
	$sql = "SELECT image_url FROM product WHERE id = '{$_GET['eid']}'";
	$stmt = $dbh->query($sql);
	$result1 = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if(empty($product_image))
	{
		if(isset($result1['image_url']))
			$product_image = $result1['image_url'];
	}
	else
	{
		if(file_exists("../img/uploads/".$result1['image_url']))
			unlink("../img/uploads/".$result1['image_url']);
	}

    $sql = "UPDATE product SET category_id = :category_id, user_id = :user_id, brand_id = :brand_id, stock_no = :stock_no, name = :name, description = :description, image_url = :image_url, unit_price = :unit_price, shipping_fee = :shipping_fee, is_recommended = :is_recommended, is_featured = :is_featured, updated = NOW() WHERE id = :id";
	  $sth = $dbh->prepare($sql);
	  $sth->bindParam(':category_id', $category_id, PDO::PARAM_INT, 5);
	  $sth->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT, 5);
	  $sth->bindParam(':brand_id', $brand_id, PDO::PARAM_INT, 5);
	  $sth->bindParam(':stock_no', $stock_no, PDO::PARAM_STR, 10);
	  $sth->bindParam(':name', $name, PDO::PARAM_STR, 100);
	  $sth->bindParam(':description', $description, PDO::PARAM_STR, 120);
	  $sth->bindParam(':image_url', $product_image, PDO::PARAM_STR, 120);
	  $sth->bindParam(':unit_price', $unit_price, PDO::PARAM_STR, 20);
      $sth->bindParam(':shipping_fee', $shipping_fee, PDO::PARAM_STR, 20);
	  $sth->bindParam(':is_recommended', $is_recommended, PDO::PARAM_INT, 1);
	  $sth->bindParam(':is_featured', $is_featured, PDO::PARAM_INT, 1);
	  $sth->bindParam(':id', $result['id'], PDO::PARAM_INT, 5);
	  if($sth->execute()){
		//echo "data has been inserted";
		header("Location: view-products.php");
	  }
  }
  
  	header('Content-Type: text/html');

	$page_title = 'Register Product';
	include('header.php');
	include('menu.php');
	
	display_menu(1, 2);
?>

                </ul>
                <!-- END Navlist -->

                <!-- BEGIN Sidebar Collapse Button -->
                <div id="sidebar-collapse" class="visible-desktop">
                    <i class="icon-double-angle-left"></i>
                </div>
                <!-- END Sidebar Collapse Button -->
            </div>
            <!-- END Sidebar -->

            <!-- BEGIN Content -->
            <div id="main-content">
                <!-- BEGIN Page Title -->
                <div class="page-title">
                    <div>
                        <h1><i class="icon-file-alt"></i>Product Information</h1>
                        <h4>Register or edit production information</h4>
                    </div>
                </div>
                <!-- END Page Title -->

                <!-- BEGIN Breadcrumb -->
                <div id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="icon-home"></i>
                            <a href="#">Home</a>
                            <span class="divider"><i class="icon-angle-right"></i></span>
                        </li>
                        <li class="active">Product Form</li>
                    </ul>
                </div>
                <!-- END Breadcrumb -->
				<?php if(!empty($status_message)){	
				?>
				<div class="row-fluid">
                    <div class="span12">
                    	<div class="alert alert-error">
                        <button class="close" data-dismiss="alert">×</button>
                        <h4>Alert!</h4>
                        <p><?PHP echo $status_message; ?></p>
                        </div>
                    </div>
                 </div>
                 <?php }?>
                 
                <!-- BEGIN Main Content -->
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box">
                            <div class="box-title">
                                <h3><i class="icon-reorder"></i>Product Information</h3>
                                <div class="box-tool">
                                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <form class="form-horizontal" action="product.php<?PHP if(isset($_GET['eid'])) echo '?eid='.$_GET['eid']; ?>"  method="post" enctype="multipart/form-data">
                                	<div class="control-group">
                                        <label class="control-label" for="downBrand">Brand</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <select name="downBrand" class="input-xxlarge">
                                                <?PHP include_once("models/functions.php"); ?>
											  <?PHP
                                                $sql = "SELECT id, name FROM brand";
                                                $stmt = $dbh->query($sql);
                                                $result1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                              
                                                $selected_id = isset($_GET['eid']) ? $result['brand_id'] : 0;
                                                foreach($result1 as $brand) {?>
                                                  <option value="<?PHP echo $brand['id']?>" 
                                                  <?PHP echo $selected_id == $brand['id']? 'selected' : ''?>
                                                  >
                                                  <?PHP echo $brand['name']?>
                                                  </option>
                                              <?PHP } ?>
                                                </select>
                                          </div>
                                      </div>
                                  </div>
                                  
                                    <div class="control-group">
                                        <label class="control-label" for="downProductCategory">Product Category</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <select name="downProductCategory" class="input-xxlarge">
                                                <?PHP
                                                $sql = "SELECT id, name FROM product_category";
                                                $stmt = $dbh->query($sql);
                                                $result1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                              
                                                $selected_id = isset($_GET['eid']) ? $result['category_id'] : 0;
                                                foreach($result1 as $category) {?>
                                                  <option value="<?PHP echo $category['id']?>" 
                                                  <?PHP echo $selected_id == $category['id']? 'selected' : ''?>
                                                  >
                                                  <?PHP echo $category['name']?>
                                                  </option>
                                              <?PHP } ?>
                                                </select>
                                          </div>
                                      </div>
                                  </div>
                                  
                                  <div class="control-group">
                                        <label class="control-label" for="txtStockNo">Stock No</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <input type="text" placeholder="Enter Stock No" required
                                                  value="<?PHP if(isset($_GET['eid'])) echo $result['stock_no']; ?>"
                                                  name="txtStockNo" id="txtStockNo" class="input-xxlarge" data-rule-required="true" data-rule-minlength="320" />
                                          </div>
                                      </div>
                                  </div>
                                  
                                  <div class="control-group">
                                        <label class="control-label" for="txtName">Product</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <input type="text" placeholder="Enter Product" required
                                                  value="<?PHP if(isset($_GET['eid'])) echo $result['name']; ?>"
                                                  name="txtName" id="txtName" class="input-xxlarge" data-rule-required="true" data-rule-minlength="320" />
                                          </div>
                                      </div>
                                  </div>

                                    <div class="control-group">
                                        <label for="txtDescription" class="control-label">Description</label>
                                        <div class="controls">
                        				              <textarea
                                                name="txtDescription" class="input-xxlarge" id="txtDescription" placeholder="Enter Description" value="<?PHP if(isset($_GET['eid'])) echo $result['description']; else echo "Enter Description"; ?>"><?PHP if(isset($_GET['eid'])) echo $result['description'];  ?></textarea>
                                        </div>
                                    </div>
									
                                    <div class="control-group">
                                        <label class="control-label" for="fileProductImage">Image</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <input type="file"
                                                  name="fileProductImage" <?PHP if(!isset($_GET['eid'])) echo 'required'; ?> id="fileProductImage" class="input-xxlarge" />
                                          </div>
                                      </div>
                                  </div>
									
                                    <div class="control-group">
                                        <label class="control-label" for="txtUnitPrice">Unit Price</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <input type="text" placeholder="Enter Unit Price" required
                                                  value="<?PHP if(isset($_GET['eid'])) echo $result['unit_price']; ?>"
                                                  name="txtUnitPrice" id="txtUnitPrice" class="input-xxlarge" data-rule-required="true" data-rule-minlength="320" />
                                          </div>
                                      </div>
                                  </div>
                                    
                                    <div class="control-group">
                                        <label class="control-label" for="txtShippingFee">Shipping Fee</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <input type="text" placeholder="Enter Shipping Fee" required
                                                  value="<?PHP if(isset($_GET['eid'])) echo $result['shipping_fee']; ?>"
                                                  name="txtShippingFee" id="txtShippingFee" class="input-xxlarge" data-rule-required="true" data-rule-minlength="320" />
                                          </div>
                                      </div>
                                  </div>
                                  
                                  <div class="control-group">
                                        <label class="control-label" for="cbRecommended"></label>
                                        <div class="controls">
                                            <div class="span12">
                                            <label>
                                                <input type="checkbox" class="input-large"
                                                  value="Yes" <?PHP if(isset($_GET['eid']) && $result['is_recommended']) echo 'checked'; ?>
                                                  name="cbRecommended" id="cbRecommended"  /> Mark as recommended?</label>
                                          </div>
                                      </div>
                                  </div>
                                  
                                  <div class="control-group">
                                        <label class="control-label" for="cbFeatured"></label>
                                        <div class="controls">
                                            <div class="span12">
                                            <label>
                                                <input type="checkbox" class="input-large"
                                                  value="Yes" <?PHP if(isset($_GET['eid']) && $result['is_featured']) echo 'checked'; ?>
                                                  name="cbFeatured" id="cbFeatured"  /> Mark as featured?</label>
                                          </div>
                                      </div>
                                  </div>
									
                                    <div class="form-actions">
                                        <input type="submit" name="<?PHP if(isset($_GET['eid'])) echo 'btnUpdate'; else echo 'btnRegister'; ?>" class="btn btn-primary" value="<?PHP if(isset($_GET['eid'])) echo 'Update'; else echo 'Register'; ?>">
                                        <button type="reset" class="btn">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Main Content -->

                <footer>
                    <p>Project <?= date('Y') ?> &copy;copyright</p>
                </footer>

                <a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
            </div>
            <!-- END Content -->
        </div>
        <!-- END Container -->


        <!--basic scripts-->
        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->
        <script>window.jQuery || document.write('<script src="assets/jquery/jquery-1.10.1.min.js"><\/script>')</script>
        <script src="assets/bootstrap/bootstrap.min.js"></script>
        <script src="assets/nicescroll/jquery.nicescroll.min.js"></script>

        <!--page specific plugin scripts-->
        <script type="text/javascript" src="assets/jquery-validation/dist/jquery.validate.min.js"></script>
        <script type="text/javascript" src="assets/jquery-validation/dist/additional-methods.min.js"></script>

         <!--page specific plugin scripts-->
        <script type="text/javascript" src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="assets/bootstrap-daterangepicker/date.js"></script>
        <script type="text/javascript" src="assets/bootstrap-daterangepicker/daterangepicker.js"></script>

        <!--page specific plugin scripts-->
        <script type="text/javascript" src="assets/chosen-bootstrap/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
        <script type="text/javascript" src="assets/jquery-tags-input/jquery.tagsinput.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
        <script type="text/javascript" src="assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
        <script type="text/javascript" src="assets/clockface/js/clockface.js"></script>
        <script type="text/javascript" src="assets/bootstrap-switch/static/js/bootstrap-switch.js"></script>
        <script type="text/javascript" src="assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
        <script type="text/javascript" src="assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
        <script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>

        <!--flaty scripts-->
        <script src="js/flaty.js"></script>

    </body>
</html>
