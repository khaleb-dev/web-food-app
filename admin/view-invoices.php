<?PHP
	session_start();
	if(!isset($_SESSION['user_id']))
		header('Location: login.php');


    include_once("connection/connection.php");
	
  	header('Content-Type: text/html');

	$page_title = 'View Invoices';
	include('header.php');
	include('menu.php');
	
	display_menu(3, 0);
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
                        <h1><i class="icon-file-alt"></i>Invoices</h1>
                        <h4>View Invoices</h4>
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
                        <li class="active">Invoices</li>
                    </ul>
                </div>
                <!-- END Breadcrumb -->

                <!-- BEGIN Main Content -->
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box">
                            <div class="box-title">
                                <h3><i class="icon-table"></i> Invoices</h3>
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
      <?PHP include_once("models/functions.php"); ?>
      <?PHP
        $sql = "SELECT * FROM invoice";
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
                $shipping_fee = 0;
				foreach($products as $product)
				{
					$invoice_detail = $product['name'].' [Qty: '.$product['quantity'].', N'.$product['total_amount'].']<br/>';
					$total_amount += $product['total_amount'];
                    $shipping_fee = $product['shipping_fee'];
				}
				echo $invoice_detail.'Total Amount: N'.$total_amount;
                echo $invoice_detail.'Shipping Fee: N'.$shipping_fee;
			   ?></td>
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
