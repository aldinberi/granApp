<?php

declare(strict_types=1);

require_once 'lib/PersistanceManager.php';

require_once 'lib/database.php';

use PHPUnit\Framework\TestCase;

final class ClassTests extends TestCase
{

    public function testPersistanceManagetCreated(): void
    {
      $pm = new PersistanceManager();
      $this->assertInstanceOf(PersistanceManager::class, $pm);
    }

    public function testDataBaseCreated(){
      $db = new Database();
      $this->assertInstanceOf(Database::class, $db);
    }

    
 }

?>