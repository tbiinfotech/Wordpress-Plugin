<?php 
if (!defined('WP_ENV')){
define('WP_ENV', "staging");
}
// Class for GM group packaging
// Commented by RJM for Ashish 12/13/2016
class Marimba {
 
  
 // read the json stream
 // depending on environment, read from a test file or from the API call
function readJsonData($environment,$jsonfile = 'packages_all.json') {
 

 
  if ($environment == 'staging' || $environment == 'development') {
   // in staging and development, read a local file
 
    try {
	    $jsonfile	= str_replace("classes",'',dirname(__FILE__)."/data/$jsonfile");
		$package_data = file_get_contents($jsonfile); 
		 
    } catch (Exception $e) {
		$error = 'readJsonData file_get_contents exception: '.  $e->getMessage(). "\n";
		echo $error . "\n";
    }
  } elseif ($environment == 'production') {
   // RJM 12/13/2016 pending
	 $api_key  = '';
	 $package_data = file_get_contents($api_key); 	
 //  $package_data = this->getMetrcApiPackages($api_key);
  } else {
		$error = "<h2 class='message'>Unsupported environment: $environment</h2>";
		echo $error . "\n";
  }
 
  // we have data - now parse it
  try {
    $data = json_decode($package_data, true); 
	if($environment=='production' && empty($data)){
		echo "<b>There are no packages in the current facility</b>";
	}
    } catch (Exception $e) {
     $error = 'readJsonData json_decode exception: '.  $e->getMessage(). "\n";
     echo $error . "\n";
  }
  // return
  
  return $data;
}
	
	
/*
 *GET PACKAGES FROM DATABASE
 */
 function get_packages()
{
	global $wpdb;
	try{  
	   // GET PACKAGES FROM DATABASE
		$packages= $wpdb->prefix . PACKAGE_TABLE;
		$package_sql = "SELECT * FROM $packages where  blog_id=".get_current_blog_id()." ORDER BY product_name"; 
		$result=$wpdb->get_results($package_sql);
		//check result is empty
		if(empty($result)){
			echo "<p class='error'>No packages have been created. Click 'Sync Packages' to import packages</p>";
		}
		return $result;
	}
    catch (Exception $e) {
		echo "Exception in getting packages from database: ". $e->getMessage(). "\n";
    }
}	


/*
 * GET ASSIGNED PACKAGES TO GROUP BY GROUP ID 
 */
function get_package_group_by_group_id($group_id)
{
	global $wpdb;
	try{  
		$packages= $wpdb->prefix . PACKAGE_GROUP_TABLE;
		$package_sql = "SELECT * FROM $packages where group_id=$group_id and blog_id=".get_current_blog_id(); 
		$result= $wpdb->get_results($package_sql);
		//check result is empty
		if(empty($result)){
			return "<p class='error'>No package assigned to this group.</p>";
		}
		
	}
    catch (Exception $e) {
		echo "Exception in get package from group: ". $e->getMessage(). "\n";
    }
	return $result;
}	


/*
 * GET ASSIGNED PACKAGES TO GROUP EXCEPT PERTICULAR GROUP ID 
 */
function get_package_group_except($group_id)
{   
    global $wpdb;
    try{    
		$package_group= $wpdb->prefix . PACKAGE_GROUP_TABLE;
		$package_sql = "SELECT * FROM $package_group where  blog_id=".get_current_blog_id()." and group_id NOT IN ($group_id)"; 
		$result= $wpdb->get_results($package_sql);
		//check result is empty
		if(empty($result)){
			return "<p class='error'>No package assigned to any group.</p>";
		}
	}
    catch (Exception $e) {
		echo "<p class='error'>Exception in getting groups from database.". $e->getMessage()."\n";
    }
	return $result;
}	

/*
 * GET GROUP FROM DATABASE 
 */
function get_groups($group_name='')
{   
    global $wpdb;
    
	   $table_group= $wpdb->prefix . GROUP_TABLE;
		if($group_name!=''){
			// GET GROUPS BY GROUP NAME
		   $results=$wpdb->get_results("SELECT * FROM $table_group WHERE blog_id=".get_current_blog_id()." and name = '".$group_name."'  ORDER BY name");
		    try{
				//check result is empty
				if(!empty($results)){
					echo "<p class='error'>$group_name already exist. Please use another name</p>";
					
				}
			}
		   	catch (Exception $e) {
				echo "<p class='error'>Exception in getting group by name : ". $e->getMessage(). "</p>";
			}

		}
		else{
			// GET ALL GROUP FROM DATABASE
			$results=$wpdb->get_results("SELECT * FROM $table_group WHERE  blog_id=".get_current_blog_id()." ORDER BY name"); 
			try{
				//check result is empty
				if(empty($results)){
					?>
					<p class='error'><?php _e( 'No package groups have been created. <a href="admin.php?page=groups&add_group">Click here</a> to make your first Package Group.' ); ?></p><?php
				}
			}
		   	catch (Exception $e) {
				echo "<p class='error'>Exception in getting group: ". $e->getMessage(). "</p>";
			}
		}		
	return $results;
	
}	
	 
}