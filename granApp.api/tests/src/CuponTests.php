<?php

declare(strict_types=1);

require_once 'lib/database.php';

require_once 'lib/PersistanceManager.php';

use PHPUnit\Framework\TestCase;

final class CuponTests extends TestCase {
    
    private $pm;

    public function setUp(): void {
        $this->pm = new PersistanceManager();
    }

    public function testBasicCuponInfo(){
        $cupons = $this->pm->get_all_cupons();
        $this->assertNotEmpty($cupons);

        foreach ($cupons as $cupon){
            $this->assertArrayHasKey("id", $cupon);
            $this->assertNotEmpty($cupon["id"]);
            $this->assertArrayHasKey("name", $cupon);
            $this->assertNotEmpty($cupon["name"]);
            $this->assertArrayHasKey("price", $cupon);
            $this->assertNotEmpty($cupon["price"]);
            $this->assertArrayHasKey("new_price", $cupon);
            $this->assertNotEmpty($cupon["new_price"]);
            $this->assertArrayHasKey("cupon_code", $cupon);
            $this->assertNotEmpty($cupon["cupon_code"]);
            $this->assertArrayHasKey("company", $cupon);
            $this->assertNotEmpty($cupon["company"]);
            $this->assertArrayHasKey("user_id", $cupon);
            $this->assertNotEmpty($cupon["user_id"]);
        }
    }

    public function testIfReturnsRealCupons(){
        $cupons = $this->pm->get_all_cupons();
        $example_cupon = [
            "id" => 4,
            "name" => "Jagode",
            "price" => 5.45,
            "new_price" => 0.6,
            "cupon_code" => "jag",
            "company" => "Bingo",
            "user_id" => 28
        ];
        $this->assertEquals($example_cupon, $cupons[0]);
    }

    public function testIfReturnsRightCupon(){
        $cupon = $this->pm->get_vendor_cupon(28, 4);
        $example_cupon = [
            "id" => 4,
            "product_id" => 13,
            "pv_id" => 5,
            "name" => "Jagode",
            "price" => 5.45,
            "new_price" => 0.6,
            "cupon_code" => "jag"
        ];
        $this->assertEquals($example_cupon, $cupon[0]);
    }

    public function testIfReturnsCuponsByUser(){
        $cupons = $this->pm->get_vendor_cupons(28);
        $example_cupon = [
            "id" => 4,
            "pv_id" => 5,
            "name" => "Jagode",
            "price" => 5.45,
            "new_price" => 0.6,
            "cupon_code" => "jag"
        ];
        // print_r($cupons);
        $this->assertEquals($example_cupon, $cupons[0]);
    }

     public function testInsertCupon(){
         try {
             $example_cupon = [
                 "product_id" => 13,
                 "cupon_code" => "jag",
                 "new_price" => 1
             ];

             $this->pm->insert_cupon($example_cupon, 28);
             $this->assertTrue(true);

         } catch (Exception $e){
             $this->fail();
         }
     }

    public function testUpdateCupon(){
        try{
            $example_cupon = [
                "cupon_code" => "muha",
                "new_price" => 5
            ];

            $this->pm->update_cupon($example_cupon, 11, 28);
            $this->assertTrue(true);
        } catch (Exception $e){
            $this->fail();
        }
    }

    public function testDeleteCupon(){
        try{
            $this->pm->delete_cupon(6, 28);
            $this->assertTrue(true);
            $cupon = $this->pm->get_vendor_cupon(28, 19);
            if (!$cupon) {
                $this->fail();
            }
        } catch (Exception $e){
            $this->fail();
        }
    }
}

?>