<?php

declare(strict_types=1);

require_once 'lib/PersistanceManager.php';

use PHPUnit\Framework\TestCase;

final class UserTests extends TestCase
{
    private $pm;

    public function setUp(): void
    {
        $this->pm = new PersistanceManager();

    }

    public function testUserValidation(){
        $login_data = [
            "username"=>"aldin_berisa15@outlook.com",
            "password"=>"campo"
        ];

        $response = $this->pm->validate_user($login_data);

        $user = [
            "user_id" => 30,
            "name" => "Aldin",
            "lastname" => "Beriša",
            "email" => "aldin_berisa15@outlook.com",
            "user_type_id" => 1
        ];

        $this->assertEquals($response, $user);

    }

    public function testBasicCustomerInfo(){
        $customers = $this->pm->get_customers();
        $this->assertNotEmpty($customers);

        foreach ($customers as $customer){
            $this->assertArrayHasKey("id", $customer);
            $this->assertNotEmpty($customer["id"]);
            $this->assertArrayHasKey("name", $customer);
            $this->assertNotEmpty($customer["name"]);
            $this->assertArrayHasKey("lastname", $customer);
            $this->assertNotEmpty($customer["lastname"]);
            $this->assertArrayHasKey("address", $customer);
            $this->assertNotEmpty($customer["address"]);
            $this->assertArrayHasKey("email", $customer);
            $this->assertNotEmpty($customer["email"]);
        } 
        
    }

    public function testIfReturnsRealCustomers(){
        $customers = $this->pm->get_customers();
        $example_customer = [
            "id" => 15,
            "name" => "alem",
            "lastname" => "berisa",
            "address" => "Berlin",
            "email" => "alem@berisa.com"
        ];
        $this->assertEquals($example_customer, $customers[0]);
    }

    public function testIfReturnsRigtCustomer(){
        $customer = $this->pm->get_customer(15);
        $example_customer = [
            "id" => 15,
            "name" => "alem",
            "lastname" => "berisa",
            "address" => "Berlin",
            "email" => "alem@berisa.com",
            "type" => 2
        ];
        $this->assertEquals($example_customer, $customer[0]);
    }

    public function testInsertCustomer(){
        try{
            $example_customer = [
                "name" => "Amer",
                "lastname" => "Resa",
                "address" => "Sibenik bb",
                "email" => "amerresa@gmail.com",
                "password" => "saba",
            ];
            
            $this->pm->insert_customer($example_customer);
            $this->assertTrue(true);

        }catch(Exception $e){
            $this->fail();
        }

    }

    public function testUpdateCustomer(){
        try{
            $example_customer = [
                "name" => "Amir",
                "lastname" => "Resa",
                "address" => "Sibenik bb",
                "email" => "amerresa@gmail.com",
                "password" => "saba",
            ];
            
            $this->pm->update_customer($example_customer, 26, 1);
            $this->assertTrue(true);

        }catch(Exception $e){
            $this->fail();
        }

    }

    public function testBasicVendorInfo(){
        $vendors = $this->pm->get_vendors();
        $this->assertNotEmpty($vendors);

        foreach ($vendors as $vendor){
            $this->assertArrayHasKey("id", $vendor);
            $this->assertNotEmpty($vendor["id"]);
            $this->assertArrayHasKey("name", $vendor);
            $this->assertNotEmpty($vendor["name"]);
            $this->assertArrayHasKey("lastname", $vendor);
            $this->assertNotEmpty($vendor["lastname"]);
            $this->assertArrayHasKey("address", $vendor);
            $this->assertNotEmpty($vendor["address"]);
            $this->assertArrayHasKey("email", $vendor);
            $this->assertNotEmpty($vendor["email"]);
            $this->assertArrayHasKey("company", $vendor);
            $this->assertNotEmpty($vendor["company"]);
        } 
        
    }

    public function testIfReturnsRealVendors(){
        $vendors = $this->pm->get_vendors();
        $example_vendor = [
            "id" => 28,
            "name" => "campo",
            "lastname" => "berisa",
            "address" => "grivici",
            "email" => "campo@thedog.com",
            "company" => "Bingo"
        ];
        $this->assertEquals($example_vendor, $vendors[1]);
    }

    public function testIfReturnsRigtVendor(){
        $vendor = $this->pm->get_vendor(28);
        $example_vendor = [
            "name" => "campo",
            "lastname" => "berisa",
            "address" => "grivici",
            "email" => "campo@thedog.com",
            "company" => "Bingo",
            "type" => 3
        ];
        $this->assertEquals($example_vendor, $vendor[0]);
    }

    public function testInsertVendor(){
        try{
            $example_vendor = [
                "name" => "Hankoi",
                "lastname" => "Paldum",
                "address" => "Mese Selimovica bb",
                "email" => "hanka@paldum.com",
                "password" => "hanka",
                "company" => "Hanka Inc",
            ];
            
            $this->pm->insert_vendor($example_vendor);
            $this->assertTrue(true);

        }catch(Exception $e){
            $this->fail();
        }

    }

    public function testUpdateVendor(){
        try{
            $example_vendor = [
                "name" => "Anka",
                "lastname" => "Paldum",
                "address" => "Mese Selimovica bb",
                "email" => "hanka@paldum.com",
                "password" => "hanka",
                "company" => "Hanka Inc",
            ];
            
            $this->pm->update_vendor($example_vendor, 29);
            $this->assertTrue(true);
            

        }catch(Exception $e){
            $this->fail();
        }
    }

    public function testDeleteUser(){
        try{            
            $this->pm->delete_user(16);
            $this->assertTrue(true);
            $vendor = $this->pm->get_vendor(37);
            if(!$vendor){
                $this->fail();
            }
        }catch(Exception $e){
            $this->fail();
        }
    }

 }

?>