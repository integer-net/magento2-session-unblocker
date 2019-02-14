<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Test\Util;

use Magento\TestFramework\Helper\Bootstrap;

class MethodLog
{
    /**
     * @var string[]
     */
    private $calls = [];
    /**
     * @var string[]
     */
    private $callDetails = [];

    private const SESSION_START = 'session_start';

    private const SESSION_MODIFY = 'session_modify';

    private const CONTROLLER_ACTION = 'controller_action';

    private const SESSION_WRITE_CLOSE = 'session_write_close';

    public static function instance(): self
    {
        // should only be used in integration test context
        if (class_exists(Bootstrap::class)) {
            return Bootstrap::getObjectManager()->get(MethodLog::class);
        }

        return new self;
    }

    public function logSessionStart(string $class, string $method, string $namespace): void
    {
        $this->log(self::SESSION_START, $class, $method, $namespace);
    }

    public function logSessionModify(string $class, string $method, string $namespace, string $key)
    {
        $this->log(self::SESSION_MODIFY, $class, $method, $namespace, $key);
    }

    public function logControllerAction(string $class, string $method)
    {
        $this->log(self::CONTROLLER_ACTION, $class, $method);
    }

    public function logWriteClose(string $class, string $method)
    {
        $this->log(self::SESSION_WRITE_CLOSE, $class, $method);
    }

    private function log(string $category, string $class, string $method, ...$args): void
    {
        $this->calls[] = $category;
        $this->callDetails[] = $class . '::' . $method . '(' . implode(',', $args) . ')';
    }

    public function asString(): string
    {
        return implode("\n", $this->callDetails);
    }

    public function hasSessionStartAfterAction(): bool
    {
        $actionPosition = array_search(self::CONTROLLER_ACTION, $this->calls);
        $sessionStartPositions = array_keys($this->calls, self::SESSION_START);
        if (empty($sessionStartPositions)) {
            return false;
        }
        return max($sessionStartPositions) > $actionPosition;
    }

    public function hasSessionStartBeforeAction(): bool
    {
        $actionPosition = array_search(self::CONTROLLER_ACTION, $this->calls);
        $sessionStartPositions = array_keys($this->calls, self::SESSION_START);
        if (empty($sessionStartPositions)) {
            return false;
        }
        return min($sessionStartPositions) < $actionPosition;
    }

    public function hasSessionWriteCloseBeforeAction(): bool
    {
        $actionPosition = array_search(self::CONTROLLER_ACTION, $this->calls);
        $writeClosePositions = array_keys($this->calls, self::SESSION_WRITE_CLOSE);
        if (empty($writeClosePositions)) {
            return false;
        }
        return max($writeClosePositions) < $actionPosition;
    }

    public function hasModifyAfterSessionWriteClose()
    {
        $writeClosePosition = array_search(self::SESSION_WRITE_CLOSE, $this->calls);
        $modifyPositions = array_keys($this->calls, self::SESSION_MODIFY);
        if (empty($modifyPositions)) {
            return false;
        }
        return max($modifyPositions) > $writeClosePosition;
    }

    public function reset(): void
    {
        $this->calls = [];
        $this->callDetails = [];
    }
}
