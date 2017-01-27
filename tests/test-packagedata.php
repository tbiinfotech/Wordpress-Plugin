<?php
 
define('PACKAGE_ID','2686048');
 
class PackageData extends PackageGroupTestCase {
function testUpdateQuantity() {	 
	    $conn = $this->getConnection()->getConnection();
		$Quantity=22;
		try{
			$sql="UPDATE ".PACKAGE_TABLE." SET quantity=$Quantity WHERE id=".PACKAGE_ID." and blog_id=".BLOG_ID;
			if ($conn->query($sql) == TRUE) {
				$this->assertTrue(true);
			} else {
				$this->assertTrue(false);
			}
			
		} catch (PDOException $e) {
				echo $e->getMessage();
		 }  
	}

}

?>