<?php

declare(strict_types=1);

require 'PersistanceManager.php';

use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{

    public function testPersistanceManagetCreated(): void
    {
      $pm = new PersistanceManager();
      $this->assertInstanceOf(PersistanceManager::class, $pm);
    }

// public function testCanBeUsedAsString(): void
//      {
//         $this->assertEquals(
//             'user@example.com',
//             Email::fromString('user@example.com')
//         );
//     }
 }

?>