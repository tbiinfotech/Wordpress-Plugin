<?php
// we're loading the Database TestCase here
define('GROUP_TABLE', 'wp_groups');
define('PACKAGE_TABLE', 'wp_packages');
define('PACKAGE_GROUP_TABLE','wp_package_group');
define('BLOG_ID','1');
require_once 'classes/marimba-packages.php';
class PackageGroupTestCase extends PHPUnit_Extensions_Database_TestCase {

    public $marimba = array(
		GROUP_TABLE,
		PACKAGE_TABLE,
		PACKAGE_GROUP_TABLE,
	);
	private $conn = null;
	public function setUp() {
		$conn = $this->getConnection();
		$pdo = $conn->getConnection();
	 
		// set up tables
		parent::setUp();
	}
	public function tearDown() {
		 
		parent::tearDown();
	}
	public function getConnection() {
		if ($this->conn === null) {
			try {
				$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
				$this->conn = $this->createDefaultDBConnection($pdo, $GLOBALS['DB_DBNAME']);
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
		}
		return $this->conn;
	}
	public function getDataSet($marimba = array()) {
		return $this->createMySQLXMLDataSet('post.xml');
	}
	public function loadDataSet($dataSet) {
		// set the new dataset
		$this->getDatabaseTester()->setDataSet($dataSet);
		// call setUp whateverhich adds the rows
		$this->getDatabaseTester()->onSetUp();
	}
	 
    public function testTruncatePackages() {	 
	    $conn = $this->getConnection()->getConnection();
		try {
		    $sql = "TRUNCATE TABLE ".PACKAGE_TABLE;
			if ($conn->query($sql)== TRUE) {
				$this->assertTrue(true);
			} else {
				$this->assertTrue(false);
			}
			 
		} catch (PDOException $e) {
			echo $e->getMessage();
		 }
	}
	
	public function testInsertPackages() {	 
	    $conn = $this->getConnection()->getConnection();
		$package_datas =Marimba::readJsonData(WP_ENV);
		$this->testTruncatePackages();
		try{
			$i=0;
			foreach($package_datas as $package_data){
			$n=addslashes($package_data['ProductName']);
			$sql = "INSERT INTO ".PACKAGE_TABLE." (id, label, packagetype,quantity,product_name,blog_id)
			VALUES ($package_data[Id], '$package_data[Label]', '$package_data[PackageType]',$package_data[Quantity],'$n',".BLOG_ID.")";
			$conn->query($sql);
			$i=$i+1;
			} 
			$this->assertTrue(true);
			 
		} catch (PDOException $e) {
			echo $e->getMessage();
	    }  
	}
	
	public function testTablePackageExist(){
		   $conn = $this->getConnection()->getConnection();
		   $sql = "SHOW TABLES LIKE '".PACKAGE_TABLE."'";
			$r=$conn->query($sql);
			if($r->rowCount()>0){
				$this->assertTrue(true);
			}else{
				$this->assertTrue(false);
			}
	}
  
}
