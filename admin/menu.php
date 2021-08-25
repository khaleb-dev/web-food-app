<?PHP
//include_once(dirname(__FILE__) .'/../helper/utility.php');
//$utility = new Utility();
	
	$side_menu = array(array('name' => 'Dashboard', 'url' => 'index.php', 'icon' => 'icon-dashboard', 'sub_menu' => array()),
		array('name' => 'Register', 'icon' => 'icon-spinner', 'url' => '#', 
				'sub_menu' => array(array('name' => 'Brand', 'url' => 'brand.php'),
									array('name' => 'Product Category', 'url' => 'product-category.php'),
									array('name' => 'Product', 'url' => 'product.php'))),
		array('name' => 'Views Store', 'icon' => 'icon-table', 'url' => '#', 
				'sub_menu' => array(array('name' => 'Brands', 'url' => 'view-brands.php'),
									array('name' => 'Product Categories', 'url' => 'view-product-categories.php'),
									array('name' => 'Products', 'url' => 'view-products.php'))),
		array('name' => 'Sells', 'icon' => 'icon-globe', 'url' => '#', 
				'sub_menu' => array(array('name' => 'Invoices (Orders)', 'url' => 'view-invoices.php'),
									array('name' => 'Customers', 'url' => 'view-customers.php'))),
		array('name' => 'Administration', 'icon' => 'icon-globe', 'url' => '#', 
				'sub_menu' => array(array('name' => 'New User', 'url' => 'new-user.php'),
									array('name' => 'All Users', 'url' => 'view-users.php')))
									
	);
	
	function display_menu($active_menu = 0, $active_submenu = 0)
	{
		global $side_menu;
		//global $utility;
		$str_menu = '';
		for($x = 0; $x < count($side_menu); $x++)
		{
			//if($side_menu[$x]['name'] == 'Affiliate' && isset($_SESSION['is_affiliate']) && !$_SESSION['is_affiliate'])
				//continue;
				
			//if($side_menu[$x]['name'] == 'Admin Panel' && !in_array($_SESSION['user_name'], $utility->get_admins2()))
				//continue;
				
			$str_menu .= '<li '.(($active_menu == $x) ? 'class="active"':'').'>
                        <a href="'.$side_menu[$x]['url'].'" class="dropdown-toggle">
                            <i class="'.$side_menu[$x]['icon'].'"></i>
                            <span>'.$side_menu[$x]['name'].'</span>';
							
							if(count($side_menu[$x]['sub_menu']))
               					$str_menu .= '<b class="arrow icon-angle-right"></b>';
               				$str_menu .= '</a>';
						
					if(count($side_menu[$x]['sub_menu']))
					{
                       $str_menu .= '<ul class="submenu">';
						for($y = 0; $y < count($side_menu[$x]['sub_menu']); $y++)
						{
                            $str_menu .= '<li '.(($active_menu == $x && $active_submenu == $y) ? 'class="active"':'').'>
							<a href="'.$side_menu[$x]['sub_menu'][$y]['url'].'">'.$side_menu[$x]['sub_menu'][$y]['name'].'</a>
							</li>';
						}	
                       $str_menu .= '</ul>';
					}
						
          	$str_menu .= '</li>';
		}
		echo $str_menu;
	}