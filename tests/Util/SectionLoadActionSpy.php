<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Test\Util;

use Magento\Customer\Controller\Section\Load;

class SectionLoadActionSpy extends Load
{
    public function execute()
    {
        MethodLog::instance()->logControllerAction(parent::class, __FUNCTION__);
        return parent::execute();
    }

}