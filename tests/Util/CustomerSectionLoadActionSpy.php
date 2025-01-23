<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Test\Util;

use IntegerNet\SessionUnblocker\MethodLog;
use Magento\Customer\Controller\Section\Load;

class CustomerSectionLoadActionSpy extends Load
{
    public function execute()
    {
        MethodLog::instance()->logControllerAction(parent::class, __FUNCTION__);
        return parent::execute();
    }

}