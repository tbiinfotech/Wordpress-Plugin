<?php 
use phpmock\phpunit\PHPMock;
 
require_once (dirname(__DIR__) . '\classes\marimba-packages.php');
 

class Greenmarimba extends \PHPUnit_Framework_TestCase
{
    use PHPMock;
    
	/*
    * CALL JSON DATA BY MARIMBA::READJSONDATA
    */
    protected function setUp()
    {
		$this->package_data =Marimba::readJsonData(WP_ENV);
    }
	
	/*
    * CHECK JSON DATA STRING OR NOT
    */
    public function testReadJsonDataString()
    {		 
	    
         $this->assertTrue(is_array($this->package_data));
    }	
	
	/*
    * CHECK JSON DATA COUNT
    */
	public function testReadJsonDataIsValidCount()
    {
        
        $this->assertGreaterThan(0,count($this->package_data));
    }
	
	/*
    * CHECK JSON DATA EMPTY OR NOT
    */
    public function testReadJsonDataEmpty()
    { 
       $this->assertNotEmpty($this->package_data);
    }
	
	/*
    * FETCH DATA FROM JSON FILE
    */
    public function testReadJsonDatafetch()
    {    
        $package_data =$this->package_data;
		$this->assertGreaterThan(0, count($package_data));
        
    } 
}