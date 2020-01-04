<?php

declare(strict_types=1);

require_once 'lib/database.php';

require_once 'lib/PersistanceManager.php';

use PHPUnit\Framework\TestCase;

final class ProductTests extends TestCase {
    
    private $pm;

    public function setUp(): void {
        $this->pm = new PersistanceManager();
    }

    public function testBasicProductInfo(){
        $products = $this->pm->get_all_products();

        foreach($products as $product){
            $this->assertArrayHasKey("id", $product);
            $this->assertNotEmpty($product["id"]);
            $this->assertArrayHasKey("name", $product);
            $this->assertNotEmpty($product["name"]);
            $this->assertArrayHasKey("grammage", $product);
            $this->assertNotEmpty($product["grammage"]);
            $this->assertArrayHasKey("unit", $product);
            $this->assertArrayHasKey("price", $product);
            $this->assertNotEmpty($product["price"]);
            $this->assertArrayHasKey("user_id", $product);
            $this->assertNotEmpty($product["user_id"]);
            $this->assertArrayHasKey("date_added", $product);
            $this->assertNotEmpty($product["date_added"]);
        }
    }

    public function testIfReturnsRealProducts(){
        $products = $this->pm->get_all_products();
        $example_product = [
            "id" => 5,
            "name" => "jabuke",
            "grammage" => 1000,
            "unit" => "",
            "price" => 5.45,
            "user_id" => 28,
            "date_added" => "2019-05-29"
        ];
        $this->assertEquals($example_product, $products[0]);
    }

    public function testBasicCheapestProductsInfo(){
        $cheapest_products = $this->pm->get_all_cheapest_products();

        foreach($cheapest_products as $product){
            $this->assertArrayHasKey("name", $product);
            $this->assertNotEmpty($product["name"]);
            $this->assertArrayHasKey("grammage", $product);
            $this->assertNotEmpty($product["grammage"]);
            $this->assertArrayHasKey("unit", $product);
            $this->assertArrayHasKey("lowest_price", $product);
            $this->assertNotEmpty($product["lowest_price"]);
            $this->assertArrayHasKey("company", $product);
            $this->assertNotEmpty($product["company"]);
            $this->assertArrayHasKey("date_added", $product);
            $this->assertNotEmpty($product["date_added"]);
        }
    } 
    
    public function testIfReturnsRealCheapestProducts(){
        $cheapest_products = $this->pm->get_all_cheapest_products();
        $example_product = [
            "name" => "jabuke",
            "grammage" => 1000,
            "unit" => "",
            "lowest_price" => 5.45,
            "company" => "Bingo",
            "date_added" => "2019-05-29"
        ];
        $this->assertEquals($example_product, $cheapest_products[0]);
    }

    public function testBasicProductsFromVendorInfo(){
        $vendor_products = $this->pm->get_all_products_from_vendor(28);
        
        foreach($vendor_products as $product){
            $this->assertArrayHasKey("id", $product);
            $this->assertNotEmpty($product["id"]);
            $this->assertArrayHasKey("name", $product);
            $this->assertNotEmpty($product["name"]);
            $this->assertArrayHasKey("grammage", $product);
            $this->assertNotEmpty($product["grammage"]);
            $this->assertArrayHasKey("unit", $product);
            $this->assertArrayHasKey("price", $product);
            $this->assertNotEmpty($product["price"]);
            $this->assertArrayHasKey("date_added", $product);
            $this->assertNotEmpty($product["date_added"]);
        }
        // print_r($vendor_products);
    }

    public function testIfReturnsRealVendorProducts(){
        $vendor_products = $this->pm->get_all_products_from_vendor(28);
        $example_product = [
            "id" => 5,
            "name" => "jabuke",
            "grammage" => 1000,
            "unit" => "",
            "price" => 5.45,
            "date_added" => "2019-05-29"
        ];
        $this->assertEquals($example_product, $vendor_products[0]);
    }

    public function testIfReturnsRightVendorProduct(){
        $vendor_product = $this->pm->get_product_from_vendor(28, 13);
        $example_product = [
            "id" => 13,
            "name" => "Jagode",
            "grammage" => 200,
            "unit" => "g",
            "price" => 5.45,
            "date_added" => "2019-06-06"
        ];
        $this->assertEquals($example_product, $vendor_product[0]);
    }

     public function testInsertProduct(){
         try {
             $product = [
                 "name" => "Krem juha od gljiva",
                 "grammage" => 150,
                 "unit" => "g",
                 "date_added" => "2019-12-31",
                 "price" => 1.50
             ];
             $this->pm->insert_product($product, 28);
             $this->assertTrue(true);

         } catch (Exception $e) {
             $this->fail();
         }
     }

     public function testUpdateProduct(){
         try {
             $product = [
                 "name" => "Krem juha od gljiva",
                 "grammage" => 150,
                 "unit" => "g",
                 "date_added" => "2019-12-31",
                 "price" => 2
             ];

             $this->pm->update_product($product, 28, 29);
             $this->assertTrue(true);
         } catch (Exception $e) {
             $this->fail();
         }
     }

    public function testDeleteProduct(){
        try {
            $this->pm->delete_product(27, 28);
            $this->assertTrue(true);
            $product = $this->pm->get_product_from_vendor(28, 33);
            if($product) {
                $this->fail();
            }
        } catch (Exception $e) {
            $this->fail();
        }
    }
}

?>