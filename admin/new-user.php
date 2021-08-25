<?PHP
	session_start();
	if(!isset($_SESSION['user_id']))
		header('Location: login.php');

  include_once("connection/connection.php");

  $status_message = '';
  if(isset($_POST['btnRegister'])){
      if(isset($_POST['txtFullName']) && isset($_POST['txtUserName']) && $_POST['txtPassword'] == $_POST['txtConfirmPassword']){
        $full_name = strip_tags($_POST['txtFullName']);
        $user_name = strip_tags($_POST['txtUserName']);
        $password = strip_tags($_POST['txtPassword']);

        $sql = "SELECT user_name FROM user WHERE user_name = :user_name";
        $stmt = $dbh->prepare($sql);
        $result = $stmt->execute(array(
          ':user_name' => $user_name
        ));

        if($stmt->rowCount() > 0){
          $status_message = $name." already exist in the database";
        }else {
          $sql = "INSERT INTO user(user_id, full_name, user_name, password, created, updated) VALUES(:user_id, :full_name, :user_name, :password, NOW(), NOW())";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT, 5);
          $sth->bindParam(':full_name', $full_name, PDO::PARAM_STR, 100);
          $sth->bindParam(':user_name', $user_name, PDO::PARAM_STR, 100);
          $sth->bindParam(':password', $password, PDO::PARAM_STR, 120);
          if($sth->execute()){
            //echo "data has been inserted";
            header("Location: view-users.php");
          }
        }

      }
  }

  if(isset($_GET['eid'])){
    $eid = strip_tags($_GET['eid']);
    $sql = "SELECT * FROM user WHERE id = '{$eid}'";
    $stmt = $dbh->query($sql);

    if($stmt->rowCount() > $eid){
      //header("Location: invalid_query.php");
    }
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

  }

  if(isset($_POST['btnUpdate'])){
    $full_name = $_POST['txtFullName'];
    $user_name = $_POST['txtUserName'];
    $password = strip_tags($_POST['txtPassword']);
    $sql = "UPDATE user SET user_id = :user_id, full_name = :full_name, user_name = :user_name, password = :password, updated = NOW() WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $result_set = $stmt->execute(array(
      ':user_id' => $_SESSION['user_id'],
      ':full_name' => $full_name,
      ':user_name' => $user_name,
      ':password' => $password,
      ':id' => $result['id']
    ));

    header("Location: view-users.php");
  }
  
  	header('Content-Type: text/html');

	$page_title = 'Register User';
	include('header.php');
	include('menu.php');
	
	display_menu(4, 0);
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
                        <h1><i class="icon-file-alt"></i>User Form</h1>
                        <h4>Use this form to register new User</h4>
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
                        <li class="active">New User Form</li>
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
                                <h3><i class="icon-reorder"></i>New User</h3>
                                <div class="box-tool">
                                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                            <form class="form-horizontal"
                            action="new-user.php<?PHP if(isset($_GET['eid'])) echo "?eid=".$result['id']; ?>"
                              method="post">
                            	<div class="row-fluid">
                            	<div class="span12">

                                    <div class="control-group">
                                        <label class="control-label" for="txtFullName">Full Name</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <input type="text" name="txtFullName" id="txtFullName" required
                                                value="<?PHP if(isset($_GET['eid'])) echo $result['full_name']; ?>"
                                                class="input-xlarge"  />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="txtUserName">User Name</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <input type="text" name="txtUserName" id="txtUserName" required
                                                value="<?PHP if(isset($_GET['eid'])) echo $result['user_name']; ?>"
                                                class="input-xlarge"  />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="txtPassword">Password</label>
                                        <div class="controls">
                                            <div class="span12">
                                                <input type="password" name="txtPassword" id="txtPassword" required
                                                value="<?PHP if(isset($_GET['eid'])) echo $result['password']; ?>"
                                                class="input-xlarge"  />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label for="txtConfirmPassword" class="control-label">Confirm Password</label>
                                        <div class="controls">
                                            <input type="password" data-rule-equalTo="#txtPassword" class="input-xlarge" name="txtConfirmPassword" id="txtConfirmPassword" required
                                            value="<?PHP if(isset($_GET['eid'])) echo $result['password']; ?>"
                                            data-rule-maxlength="20" data-rule-required="true">
                                        </div>
                                    </div>
                                 </div>

                                </div>
                          <div class="form-actions">
                            <input type="submit" name="<?PHP echo isset($_GET['eid'])?"btnUpdate": "btnRegister"; ?>"
                              class="btn btn-primary" value="<?PHP echo isset($_GET['eid'])? "Update": "Register"; ?>">
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
