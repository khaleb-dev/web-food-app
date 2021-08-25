<?PHP
	session_start();
	if(!isset($_SESSION['user_id']))
		header('Location: login.php');


    include_once("connection/connection.php");
	
  	header('Content-Type: text/html');

	$page_title = 'View Products';
	include('header.php');
	include('menu.php');
	
	display_menu(2, 2);
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
                        <h1><i class="icon-file-alt"></i>All Products</h1>
                        <h4>View Products</h4>
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
                        <li class="active">Products</li>
                    </ul>
                </div>
                <!-- END Breadcrumb -->

                <!-- BEGIN Main Content -->
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box">
                            <div class="box-title">
                                <h3><i class="icon-table"></i> View Product</h3>
                                <div class="box-tool">
                                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                                </div>
                            </div>
                            <div class="box-content">
                                <div class="btn-toolbar pull-right clearfix">
                                    <div class="btn-group">
                                        <a class="btn btn-circle show-tooltip" title="Add new record" href="product.php"><i class="icon-plus"></i></a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-circle show-tooltip" title="Refresh" href="view-products.php"><i class="icon-repeat"></i></a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
<table class="table table-advance" id="table1">
    <thead>
        <tr>
          <th style="width: 5%">S/N</th>
          <th style="width: 10%">Stock No</th>
          <th style="width: 20%">Name</th>
          <th style="width: 25%">Description</th>
          <th style="width: 10%">Brand</th>
          <th style="width: 10%">Category</th>
          <th style="width: 10%">Unit Price</th>
          <th style="width: 10%">Shipping Fee</th>
          <th style="width: 10%">Actions</th>
        </tr>
    </thead>

    <tbody>
      <?PHP include_once("models/functions.php"); ?>
      <?PHP
        $sql = "SELECT p.id, p.name, p.description, p.unit_price, p.shipping_fee, p.stock_no, b.name AS brand, c.name AS category FROM product p INNER JOIN product_category c ON c.id = p.category_id INNER JOIN brand b ON b.id = p.brand_id";
        $stmt = $dbh->query($sql);
      	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	  
        $counter = 1;
        foreach($result as $product) {?>
          <tr class="table-flag-blue">
              <td><?PHP echo $counter; ?></td>
              <td><?PHP echo $product['stock_no']; ?></td>
              <td><?PHP echo $product['name']; ?></td>
              <td><?PHP echo $product['description']; ?></td>
              <td><?PHP echo $product['brand']; ?></td>
              <td><?PHP echo $product['category']; ?></td>
              <td><?PHP echo $product['unit_price']; ?></td>
              <td><?PHP echo $product['shipping_fee']; ?></td>
              <?PHP $counter++; ?>
              <td>
                  <div class="btn-group">
                      <a class="btn btn-small show-tooltip edit-a" title="Edit" href="product.php?eid=<?PHP echo $product['id']; ?>"><i class="icon-edit" ></i></a>
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
