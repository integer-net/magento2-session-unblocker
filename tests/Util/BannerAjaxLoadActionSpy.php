<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Test\Util;

use IntegerNet\SessionUnblocker\MethodLog;
use Magento\Banner\Controller\Ajax\Load;

class BannerAjaxLoadActionSpy extends Load
{
    public function execute()
    {
        MethodLog::instance()->logControllerAction(parent::class, __FUNCTION__);
        return parent::execute();
    }

}