<?php
/*
 * Plugin Name: Greenmarimba Package Grouping
 * Plugin URI: 
 * Description:Greenmarimba Package Grouping
 * Version: 1.0.0
 * Author: 
 * Author URI: 
*/ 


define('PROF_URL', plugin_dir_url( __FILE__ ));
define('PROF_PATH', plugin_dir_path( __FILE__ ));
define('GROUP_TABLE', 'groups');
define('PACKAGE_TABLE', 'packages');
define('PACKAGE_GROUP_TABLE','package_group');
 
require_once PROF_PATH.'/classes/marimba-packages.php';
 
   
/*
 * CREATE TABLE ON PLUGIN ACTIVATION
 */ 
function plugin_activate() {
    global $wpdb;
    $comp_table_prefix=$wpdb->prefix;
    $table_package = $comp_table_prefix.PACKAGE_TABLE;
    $p_structure = "CREATE TABLE IF NOT EXISTS $table_package (
        id INT(9) NOT NULL AUTO_INCREMENT,
        label varchar(255) NOT NULL,
		packagetype varchar(255) NOT NULL,
		quantity INT(9) NOT NULL,
		product_name varchar(255) NOT NULL,
		blog_id varchar(255) NOT NULL,
        UNIQUE KEY id (id)
    );";
	$wpdb->query($p_structure);
	$groups = $comp_table_prefix.GROUP_TABLE;
        $g_structure = "CREATE TABLE IF NOT EXISTS $groups (
        id INT(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
		blog_id varchar(255) NOT NULL,
        UNIQUE KEY id (id)
    );";
	$wpdb->query($g_structure);
	$package_group = $comp_table_prefix.PACKAGE_GROUP_TABLE;
        $pg_structure = "CREATE TABLE IF NOT EXISTS $package_group (
        id INT(9) NOT NULL AUTO_INCREMENT,
        package_id INT(9) NOT NULL,
        group_id INT(9) NOT NULL,
  		blog_id varchar(255) NOT NULL,
        UNIQUE KEY id (id)
    );";
	$wpdb->query($pg_structure);
}
register_activation_hook( __FILE__, 'plugin_activate' );


/*
 * REGISTER MENUS IN ADMIN
 */ 
function form_admin_actions() {
    add_menu_page("Packages", "Packages", 'manage_options',__FILE__ , "package_section");
    add_submenu_page(__FILE__,'Packages','Packages','manage_options','packages','admin_list_packages');
    add_submenu_page(__FILE__,'Groups','Groups','manage_options','groups', 'admin_list_groups');
    add_submenu_page(__FILE__,'Group Packages','Group Packages','manage_options','group_packages', 'admin_group_packages');
}
add_action('admin_menu',  'form_admin_actions');

/*
 * REMOVE ADDITIONAL DEFAULT MENU 
 */ 
function custom_menu_page_removing() {
    remove_submenu_page( 'greenmarimba-package-grouping/greenmarimba-package-grouping.php','greenmarimba-package-grouping/greenmarimba-package-grouping.php' );
	remove_submenu_page( 'greenmarimba-package-grouping-disabled/greenmarimba-package-grouping.php','greenmarimba-package-grouping-disabled/greenmarimba-package-grouping.php' );
}

add_action( 'admin_menu', 'custom_menu_page_removing' ,999 );
 
/*
 * ENQUEUE CSS AND JS FILES
 */
function int_header_js_var(){
	wp_enqueue_style('custom-styles', PROF_URL . 'assets/css/custom.css' );
	wp_enqueue_style('confirm-styles', PROF_URL . 'assets/css/confirm.css' );
    wp_enqueue_script('custom-scripts', PROF_URL . 'assets/js/custom.js' );
    wp_enqueue_script('confirmation',  PROF_URL . 'assets/js/confirmation.js' );
    wp_enqueue_script('confirmation_message',  PROF_URL . 'assets/js/confirmation_message.js' );
    wp_enqueue_script('dialog',  PROF_URL . 'assets/js/dialog.js' );
	echo '<script type="text/javascript">var int_script_data={ajax_url:"'. admin_url( 'admin-ajax.php' ) .'"}</script>';

}
add_action('admin_enqueue_scripts','int_header_js_var');
 
/*
 * CREATE/LIST PACKAGES HERE
 */ 
function admin_list_packages() {
  
	include(PROF_PATH.'/templates/package.php');
}

/*
 * CREATE/LIST GROUPS HERE
 */ 
function admin_list_groups() {
	
	include(PROF_PATH.'/templates/group.php');  
}
function admin_group_packages() {
	
	include(PROF_PATH.'/templates/group-packages.php');  
}
    
/*
 * IMPORT PACKAGES FROM JSON FILE OR API KEY
 */
add_action( 'wp_ajax_insert_package', 'insert_package' );
add_action('wp_ajax_nopriv_insert_package','insert_package');
function insert_package()
{   
    global $wpdb;
	$group=array();
	$count=$counts='';
    $table_package = $wpdb->prefix . PACKAGE_TABLE;
    $table_group = $wpdb->prefix . GROUP_TABLE;
    $package_group = $wpdb->prefix . PACKAGE_GROUP_TABLE;
	$marimba = new Marimba();
	try {
		  $json = $marimba->readJsonData(WP_ENV);
		
    } catch (Exception $e) {
		  $error = 'Marimba::readJsonData: '. $e->getMessage(). "\n";
		  echo "<h2>$error</h2>";
		  die;
    }
	if(!empty($json)){
		try {
		   $delete = $wpdb->query("TRUNCATE TABLE $table_package");
		}
		catch (Exception $e) {
		    $error = 'Exception in deleting package'. $e->getMessage(). "\n";
            echo "<h2>$error</h2>";
	    }
		foreach($json as $p){
		   // GET DATA FROM JSON FILE
			$pid=$p['Id'];
			$label=$p['Label'];
			$packagetype=$p['PackageType'];
			$quantity=$p['Quantity'];
			$productname=$p['ProductName'];
			$blog_id=get_current_blog_id();
			$sql = "SELECT * FROM $table_package where id=$pid and blog_id=".$blog_id." ORDER BY product_name"; 
			$result = $wpdb->get_results($sql);
		if(empty($result)){
			// ASSIGN PACKAGE INTO DATABASE
			try {
				if($wpdb->insert($table_package, array('Id'=>$pid,'label'=>$label,'packagetype' =>$packagetype, 'quantity' => $quantity,'product_name'=>$productname,'blog_id'=>$blog_id ) )==true){
				    $counts=$counts+1;
				}
				else{
					return  "<p class='error'>Error in inserting packages.</p>";
				}
				
		    } 
			catch (Exception $e) {
				$error = 'Exception in inserting package'. $e->getMessage(). "\n";
				return "<h2>$error</h2>";
	     	}
			$results_group=$wpdb->get_results("SELECT * FROM $table_group WHERE  blog_id=".get_current_blog_id()." ORDER BY name");
		    if($count=='' && empty($results_group)){
			    $wpdb->insert($table_group, array('name'=>'Default','blog_id'=>$blog_id ) );
			}
			$group=$wpdb->get_results("SELECT id FROM $table_group WHERE blog_id=".get_current_blog_id()." and name='Default'");
			$sql_group = "SELECT * FROM $package_group where package_id=$pid and blog_id=".get_current_blog_id();
			$result_group = $wpdb->get_results($sql_group);
			if(empty($result_group)){
			   $wpdb->insert($package_group, array('package_id'=>$pid,'group_id'=>$group[0]->id ,'blog_id'=>get_current_blog_id())); 	  
			} 
		}
	  }
	}
	if($counts!=''){
	 echo $counts." success";
	}	
	die;
}
 
/*
 * ASSIGN PACKAGE TO GROUP WHEN CHECK THE CHECKBOX 
 */
add_action( 'wp_ajax_insert_package_group','insert_package_group' );
add_action('wp_ajax_nopriv_insert_package_group','insert_package_group');
function insert_package_group()
{
	global $wpdb;
	$pakgs='';
    $table_group= $wpdb->prefix . GROUP_TABLE;
	$package_group = $wpdb->prefix . PACKAGE_GROUP_TABLE;
	$group_id=$_REQUEST['gid'];
	$package_id=$_REQUEST['pid'];
	$group=$wpdb->get_results("SELECT id FROM $table_group WHERE blog_id=".get_current_blog_id()." and name='Default'");
	try{
		$wpdb->delete( $package_group, array( 'package_id' => $package_id ) );
		if($_REQUEST['c']=="1"){
			// ASSIGN PACKAGE TO GROUP
			if($wpdb->insert($package_group, array('package_id'=>$package_id,'group_id'=>$group_id ,'blog_id'=>get_current_blog_id()) )==false){
				echo "<p class='error'>Error in inserting package in group.</p>";
			}
			die;
		} 
		if($_REQUEST['c']=="0"){
			   // UPDATE PACKAGE TO DEFAULT GROUP
			$wpdb->insert($package_group, array('package_id'=>$package_id,'group_id'=>$group[0]->id ,'blog_id'=>get_current_blog_id()));
            die;
		}
    } 
    catch (Exception $e) {
		echo "Exception in inserting package based on group: ". $e->getMessage(). "\n";
    }	
	die();
}
/*
 *  GET ASSIGNED PACKAGES TO GROUP WHEN CLICK ON GROUP TAB
 */
add_action( 'wp_ajax_get_group_packages','get_group_packages' );
add_action('wp_ajax_nopriv_get_group_packages','get_group_packages');
function get_group_packages()
{
	global $wpdb;
	?>
    <h2>Items</h2><div style="display: none;" class="loader_image">
	<img src="<?php echo PROF_URL?>\images\loader.gif" title="loader" alt="loader">
    </div>
	<?php
	$marimba = new Marimba();
	try{
		$group_id=$_REQUEST['gid'];
		$package_ids=array();
		$group_package_ids=array();
		$package_data =$marimba->get_packages();
		$package_group = $marimba->get_package_group_by_group_id($group_id);
		if(is_array($package_group) && !empty($package_group)){
			foreach($package_group as $packag_data){
				$group_package_ids[]=$packag_data->package_id;
			}
		}			
		if(!empty($package_data) && is_array($package_data)){
			foreach($package_data as $product){
				// SHOW PACKAGES WHICH ARE ASSIGNED TO GROUP ?>				
				<div class="package_count">
				<input type="checkbox" name="package_assign" id="<?php echo $product->id;?>" class="package_assign" 
				<?php if(in_array($product->id,$group_package_ids)){ echo "checked=checked"; }?>>
				<?php echo $product->product_name;?>
				</div>
				<?php  
			}
		}
		else{
			?>
			<p class='error'><?php _e("There are no packages in system. <a href='admin.php?page=packages'>Click here</a> to import packages")?></p>
			<?php
		}
	}
    catch (Exception $e) {
		echo "Exception in getting package based on group: ". $e->getMessage(). "\n";
    }	
	die();
}
 
