<div class="package_container"> 
   
	<?php
	/*
    * DELETE GROUP WHEN CLICK ON DELETE CLICK
    */
	$marimba = new Marimba();
    global $wpdb;
    $table_group = $wpdb->prefix . GROUP_TABLE;
    $package_group = $wpdb->prefix . PACKAGE_GROUP_TABLE;
	if(isset($_REQUEST['delete_group'])){
		try{
			//DELETE GROUP BY GROUP ID
		   $grp_id=$_REQUEST['delete_group'];
		   $group=$wpdb->get_results("SELECT id FROM $table_group WHERE blog_id=".get_current_blog_id()." and name='Default'");
		   $package=$wpdb->get_results("SELECT * FROM $package_group WHERE blog_id=".get_current_blog_id()." and group_id=$grp_id");
		   if(!empty($package)){
			   foreach($package as $p){
				 $wpdb->delete( $package_group, array( 'package_id' => $p->package_id ) );
				    if(!empty($group)){
					  $wpdb->insert($package_group, array('package_id'=>$p->package_id,'group_id'=>$group[0]->id ,'blog_id'=>get_current_blog_id()));
				    }
			   }
		   }
		   $wpdb->delete( $table_group, array( 'id' => $_REQUEST['delete_group'] ) );
		}
		catch (Exception $e) {
			echo "<p class='error'>Error:". $e->getMessage(). "</p>";
			
		}
	}
	/*
    * DISPLAY GROUPS ON PAGE
    */
    if(!isset($_REQUEST['add_group']) && !isset($_REQUEST['edit_group'])){
        
		$result= $marimba->get_groups();
			try{
				//SHOW GROUPS LISTING
			if(!empty($result)){?>
			<h2>Groups</h2>	
			<div class="add_gr"> <a href="admin.php?page=groups&add_group"  class="button button-primary">Add Group</a></div>
			<table class="package_table group_table">
			<tr><th>S.NO.</th><th>Name</th><th>Edit</th><th>Delete</th> </tr>
			<?php }
				$r=1;
				foreach($result as $group){  ?>
				<tr><td><?php echo $r;?></td>
				<td><?php echo $group->name;?></td>
				<?php if($group->name!="Default"){ ?>
				<td><a href="admin.php?page=groups&edit_group=<?php echo $group->id;?>">Edit</a></td>  
				<td><a href="admin.php?page=groups&delete_group=<?php echo $group->id;?>" class="del_group">Delete</a></td> </tr>
				<?php } else{ ?>
					<td>Edit</td>  
				<td>Delete</td> </tr>
			    <?php 	}
				  $r=$r+1;
				} ?>
				</table>		
				<?php
		    }
			catch (Exception $e) {
				echo "Exception: ". $e->getMessage(). "\n";
			}
		
    }
	/*
    * INSERT GROUP IN DATABASE
    */
    if(isset($_REQUEST['add_group'])){
	   if(isset($_REQUEST['create']) && $_REQUEST['cr_gr']!=''){
		   $datum = $marimba->get_groups($_REQUEST['cr_gr']);
		    if(empty($datum )){
				
				try{
					$wpdb->insert($table_group, array('name' =>$_REQUEST['cr_gr'],'blog_id'=>get_current_blog_id()));
					echo'<div class="add_gr">Group created successfully</div>';
					echo'<script>jQuery(document).ready(function(){
					window.location.href = "admin.php?page=groups";
					});</script>';
			    }
				catch (Exception $e) {
					$error = "Error in inserting group: ".$e->getMessagee();
	             	echo $error . "\n";
			    }		
		    } 
	    }
	  ?>
	  <form action="" method="post"> <table>
	  <tr>
	  <td><input type="text" name="cr_gr" placeholder="Group Name">
	  </td>
	  <td><input type="submit" name="create" value="Create Group" class="button button-primary"></td>
	  </tr>
	  <tr>
	  <td><a href="admin.php?page=groups" >&lsaquo;&lsaquo; Back to groups</a>
	  </td>
	  </tr>
	  </table>
	  </form>
	  <?php
    }
	/*
    * UPDATE GROUPS IN DATABASE
    */
    if(isset($_REQUEST['edit_group'])){
		if(isset($_REQUEST['gr_name']) && isset($_REQUEST['update'])){
	        $gid=$_REQUEST['edit_group'];
	     
		    $datum =$marimba->get_groups($_REQUEST['gr_name']);
			   if(empty($datum )){
				try{   
				   $wpdb->update($table_group, array('name'=>$_REQUEST['gr_name'] ) , array('id'=>$gid));
				   
				   echo'<div class="add_gr">Group Updated successfully</div>';
				   echo'<script>jQuery(document).ready(function(){
				   window.location.href = "admin.php?page=groups";
				   });</script>';
			    }
				catch (Exception $e) {
					$error = "Error in inserting group:".$e->getMessagee();
	             	echo $error . "\n";
			    }	
		   }
		    
	    }
		$sql = "SELECT name FROM $table_group where id=".$_REQUEST['edit_group']. " and blog_id=".get_current_blog_id(); 
	    $result = $wpdb->get_results($sql);
		foreach($result as $r){
			$gn=$r->name;	
		}
	  ?>
	  <form action="" method="post"> <table>
	  <tr>
	  <td>Group Name: </td>
	  <td><input type="text" name="gr_name" value="<?php echo  $gn;?>" placeholder="Group Name">
	  </td>
	  <td><input type="submit" name="update" value="Update Group"></td>
	  </tr>
	  </table>
	  </form>
	  <?php	 
    }
?>  
 </div>
