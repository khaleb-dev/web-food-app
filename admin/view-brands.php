<?PHP
	session_start();
	if(!isset($_SESSION['user_id']))
		header('Location: login.php');


    include_once("connection/connection.php");
	
  	header('Content-Type: text/html');

	$page_title = 'View Brands';
	include('header.php');
	include('menu.php');
	
	display_menu(2, 0);
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
                        <h1><i class="icon-file-alt"></i>Brands</h1>
                        <h4>View Brands</h4>
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
                        <li class="active">Brands</li>
                    </ul>
                </div>
                <!-- END Breadcrumb -->

                <!-- BEGIN Main Content -->
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box">
                            <div class="box-title">
                                <h3><i class="icon-table"></i> Brands</h3>
                                <div class="box-tool">
                                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <div class="btn-toolbar pull-right clearfix">
                                    <div class="btn-group">
                                        <a class="btn btn-circle show-tooltip" title="Add new record" href="brand.php"><i class="icon-plus"></i></a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-circle show-tooltip" title="Refresh" href="view-brands.php"><i class="icon-repeat"></i></a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
<table class="table table-advance" id="table1">
    <thead>
        <tr>
          <th style="width: 10%">S/N</th>
          <th style="width: 30%">Name</th>
          <th style="width: 40%">Description</th>
          <th style="width: 10%">Products</th>
          <th style="width: 10%">Actions</th>
        </tr>
    </thead>

    <tbody>
      <?PHP include_once("models/functions.php"); ?>
      <?PHP
        $sql = "SELECT id, name, description FROM brand";
        $stmt = $dbh->query($sql);
      	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	  
        $counter = 1;
        foreach($result as $brand) {?>
          <tr class="table-flag-blue">
              <td><?PHP echo $counter; ?></td>
              <td><?PHP echo $brand['name']; ?></td>
              <td><?PHP echo $brand['description']; ?></td>
              <td><?PHP echo getTotalBrandProducts($brand['id']); ?></td>
              <?PHP $counter++; ?>
              <td>
                  <div class="btn-group">
                      <a class="btn btn-small show-tooltip edit-a" title="Edit" href="brand.php?eid=<?PHP echo $brand['id']; ?>"><i class="icon-edit" ></i></a>
                  </div>
              </td>
          </tr>
        <?PHP } ?>

    </tbody>
</table>
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
        <script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
        <script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>

        <!--flaty scripts-->
        <script src="js/flaty.js"></script>


        <script type="text/javascript">
          $(function()
            {
              $('.delete-a').click(function(event){
                event.preventDefault();
                if(confirm('Sure you want to delete this election?'))
                  window.location = $(this).attr('href');
              })
            });
        </script>


    </body>
</html>
