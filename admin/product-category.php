<?PHP
	session_start();
	if(!isset($_SESSION['user_id']))
		header('Location: login.php');


  include_once("connection/connection.php");

  $status_message = '';
  if(isset($_POST['btnSubmit'])){
      if(isset($_POST['txtName']) && isset($_POST['txtDescription'])){
        $name = strip_tags($_POST['txtName']);
        $parent_id = strip_tags($_POST['downParent']);
        $description = strip_tags($_POST['txtDescription']);

        $sql = "SELECT name FROM product_category WHERE name = :name";
        $stmt = $dbh->prepare($sql);
        $result = $stmt->execute(array(
          ':name' => $name
        ));

        if(!intval($parent_id) && $stmt->rowCount() > 0){
          $status_message = $name." already exist in the database";
        }else {
          $sql = "INSERT INTO product_category(user_id, parent_id, name, description, created, updated) VALUES(:user_id, :parent_id, :name, :description, NOW(), NOW())";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT, 5);
          $sth->bindParam(':parent_id', $parent_id, PDO::PARAM_INT, 5);
          $sth->bindParam(':name', $name, PDO::PARAM_STR, 150);
          $sth->bindParam(':description', $description, PDO::PARAM_STR, 500);
          if($sth->execute()){
            //echo "data has been inserted";
            header("Location: view-product-categories.php");
          }
        }

      }
  }

  if(isset($_GET['eid'])){
    $eid = strip_tags($_GET['eid']);
    $sql = "SELECT * FROM product_category WHERE id = '{$eid}'";
    $stmt = $dbh->query($sql);

    if($stmt->rowCount() > $eid){
      //header("Location: invalid_query.php");
    }
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

  }

  if(isset($_POST['btnUpdate'])){
    $name = $_POST['txtName'];
    $parent_id = strip_tags($_POST['downParent']);
    $description = $_POST['txtDescription'];
    $sql = "UPDATE product_category SET user_id = :user_id, parent_id = :parent_id, name = :name, description = :description, updated = NOW() WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $result_set = $stmt->execute(array(
      ':user_id' => $_SESSION['user_id'],
      ':parent_id' => $parent_id,
      ':name' => $name,
      ':description' => $description,
      ':id' => $result['id']
    ));

    header("Location: view-product-categories.php");
  }

	header('Content-Type: text/html');

	$page_title = 'Register Product Category';
	include('header.php');
	include('menu.php');
	
	display_menu(1, 1);
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
                        <h1><i class="icon-file-alt"></i>Product Category Information</h1>
                        <h4>Product Category Information</h4>
                    </div>
                </div>
                <!-- END Page Title -->

                <!-- BEGIN Breadcrumb -->
                <div id="breadcrumbs">
                    <ul class="breadcrumb">

                        <li>
                            <i class="icon-home"></i>
                            <a href="index.html">Home</a>
                            <span class="divider"><i class="icon-angle-right"></i></span>
                        </li>
                        <li class="active">Product Category Information</li>
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
                                <h3><i class="icon-reorder"></i>Product Category Information</h3>
                                <div class="box-tool">
                                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <form class="form-horizontal" action="product-category.php<?PHP if(isset($_GET['eid'])) echo '?eid='.$_GET['eid']; ?>"  method="post">
                                	<div class="control-group">
                                        <label class="control-label" for="downParent">Parent Category</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <select name="downParent" class="input-xxlarge">
                                                <option value="0">Select Parent Category</option>
                                                <?PHP
                                                $sql = "SELECT id, name FROM product_category WHERE parent_id = 0";
                                                $stmt = $dbh->query($sql);
                                                $result1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                              
                                                $selected_id = isset($_GET['eid']) ? $result['parent_id'] : 0;
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
                                        <label class="control-label" for="txtName">Product Category</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <input type="text" placeholder="Enter Product Category"
                                                  value="<?PHP if(isset($_GET['eid'])) echo $result['name']; ?>"
                                                  name="txtName" id="txtName" class="input-xxlarge" data-rule-required="true" data-rule-minlength="320" />
                                          </div>
                                      </div>
                                  </div>

                                    <div class="control-group">
                                        <label for="txtDescription" class="control-label">Description</label>
                                        <div class="controls">
                        				              <textarea
                                                name="txtDescription" class="input-xxlarge" id="txtDescription" placeholder="Enter Description"><?PHP if(isset($_GET['eid'])) echo $result['description'];  ?></textarea>
                                        </div>
                                    </div>


                                    <div class="form-actions">
                                        <input type="submit" name="<?PHP if(isset($_GET['eid'])) echo 'btnUpdate'; else echo 'btnSubmit'; ?>" class="btn btn-primary" value="<?PHP if(isset($_GET['eid'])) echo 'Update'; else echo 'Register'; ?>">
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

        <!--flaty scripts-->
        <script src="js/flaty.js"></script>

    </body>
</html>
