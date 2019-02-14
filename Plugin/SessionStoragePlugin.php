<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Plugin;

use IntegerNet\SessionUnblocker\Test\Util\MethodLog;
use Magento\Framework\Session\Storage;
use Magento\Framework\Session\StorageInterface;

/**
 * This plugin is currently only used for testing and debugging. It can detect, when sessions are started and modified.
 */
class SessionStoragePlugin
{
    /**
     * @var bool
     */
    private $doLogMethods = false;

    /**
     * @var MethodLog
     */
    private $methodLog;

    public function __construct(MethodLog $methodLog, bool $doLogMethods = false)
    {
        $this->doLogMethods = $doLogMethods;
        $this->methodLog = $methodLog;
    }

    public function beforeSetData(Storage $subject, $key, $value = null)
    {
        if ($this->doLogMethods) {
            if (is_array($key)) {
                $this->methodLog->logSessionModify(
                    get_parent_class($subject),
                    'setData',
                    $subject->getNamespace(),

                    '[...]'
                );
            } elseif ($subject->getData($key) !== $value) {
                $this->methodLog->logSessionModify(
                    get_parent_class($subject),
                    'setData',
                    $subject->getNamespace(),
                    $key
                );
            }
        }

        return [$key, $value];
    }

    public function beforeInit(StorageInterface $subject, array $data)
    {
        if ($this->doLogMethods) {
            MethodLog::instance()->logSessionStart(get_parent_class($subject), 'init', $subject->getNamespace());
        }
        return [$data];
    }
}