<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Test\Integration;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Module\ModuleList;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    private const MODULE_NAME = 'IntegerNet_SessionUnblocker';

    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = ObjectManager::getInstance();
    }

    public function testModuleIsActive()
    {
        /** @var ModuleList $moduleList */
        $moduleList = $this->objectManager->create(ModuleList::class);
        $this->assertTrue(
            $moduleList->has(self::MODULE_NAME),
            sprintf('The module %s should be enabled', self::MODULE_NAME)
        );
    }
}
