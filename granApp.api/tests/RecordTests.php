<?php

declare(strict_types=1);

require 'lib/database.php';

require 'PersistanceManager.php';

use PHPUnit\Framework\TestCase;

final class RecordTests extends TestCase {

    private $pm;

    public function setUp(): void{
        $this->pm = new PersistanceManager();
    }

    public function testBasicRecordInfo(){
        $records = $this->pm->get_records();
        $example_record = [
            "record_id" => 64,
            "record_string" => "User with id 1 updated his information"
        ];
        $this->assertEquals($example_record, $records[0]);
    }

    public function testDeleteRecord(){
        try {
            $this->pm->delete_record(3);
            $this->assertTrue(true);

        } catch (Exception $e) {
            $this->fail();
        }
    }
}

?>