<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Test\Util;

use IntegerNet\SessionUnblocker\MethodLog;
use Magento\Framework\Session\Generic;

class SessionSpy extends Generic
{

    public function writeClose()
    {
        MethodLog::instance()->logWriteClose(parent::class, __FUNCTION__);
        parent::writeClose();
    }

}
