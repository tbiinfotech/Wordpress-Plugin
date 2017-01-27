<div class="package_container"><div style="display: none;" class="package_loading">
			<img src="<?php echo PROF_URL?>\images\loader.gif" title="loader" alt="loader">
				 </div>
    <h2>Packages</h2>	
    <div class="syn_div"><a href="javascript:void(0)" class="sync button button-primary">Sync Packages</a></div>	
<?php
    /*
    * GET PACKAGES FROM DATABASE AND DISPLAY ON PACKAGE PAGE
    */
	$marimba = new Marimba();
	$result= $marimba->get_packages();
	if(!empty($result)){
		?>
			
		<table class="package_table">
		<tr><th>ID</th><th>Label</th><th>Package Type</th><th>Quantity</th><th>Product Name</th></tr>
		<?php foreach($result as $pk){  ?>
		<tr><td><?php echo $pk->id;?></td>
		<td><?php echo $pk->label;?></td>
		<td><?php echo $pk->packagetype;?></td>
		<td><?php echo $pk->quantity;?></td>
		<td><?php echo $pk->product_name;?></td></tr>
		<?php } ?>
		</table>		
		<?php		
	}
 
?>
 </div>