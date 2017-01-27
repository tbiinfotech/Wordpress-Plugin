<div class="group_packages">
  <h2 class="assign">Group Packages</h2>
  <div class="groups_container">
  <h2>Groups</h2>
<?php 
 
    /*
    * GET GROUPS FROM DATABASE
    */
	$marimba = new Marimba();
	$group_id = '';
	$result = $marimba->get_groups();
	

	if(!empty($result)){
		   $i=1;
			foreach($result as $group){
				//print all groups ?>
			    <div class="group_count  <?php if($i==1){ $group_id=$group->id;?>active <?php } ?>" id="<?php echo $group->id;?>">
				<?php  
				 echo "<span>".$group->name."</span>";?>
				</div>
				<?php      $i=$i+1;
			}
		}
		 
	?>
	</div>
	<div class="packages_container">
		<h2>Items</h2>
		<div style="display: none;" class="loader_image">
			<img src="<?php echo PROF_URL?>\images\loader.gif" title="loader" alt="loader">
		</div>
		<?php 
	 
		$group_package_ids=array();
		
		if($group_id!=''){
		   $group_data = $marimba->get_package_group_except($group_id); 
		 			
		$package_group = $marimba->get_package_group_by_group_id($group_id);
		if(is_array($package_group)){
				foreach($package_group as $packag_data){
				$group_package_ids[]=$packag_data->package_id;
				}
			}
		}	

		/*
		* GET PACKAGES FROM DATABASE BY GROUP ID
		*/
		$result_package = $marimba->get_packages();
		if(!empty($result_package)){
				foreach($result_package as $product){
						?>				
							<div class="package_count">
							  <input type="checkbox" name="package_assign" id="<?php echo $product->id;?>" class="package_assign" 
							  <?php if(in_array($product->id, $group_package_ids)){ echo"checked=checked"; }?>>
							  <?php echo $product->product_name;?>
							</div>
					<?php
				}
			}
			 
		?>
	</div>			
</div>