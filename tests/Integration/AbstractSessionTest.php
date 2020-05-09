<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Test\Integration;

use IntegerNet\SessionUnblocker\Plugin\SessionStoragePlugin;
use IntegerNet\SessionUnblocker\MethodLog;
use IntegerNet\SessionUnblocker\Test\Util\BannerAjaxLoadActionSpy;
use IntegerNet\SessionUnblocker\Test\Util\CustomerSectionLoadActionSpy;
use IntegerNet\SessionUnblocker\Test\Util\SessionSpy;
use Magento\Banner\Controller\Ajax\Load as BannerAjaxLoadAction;
use Magento\Customer\Controller\Section\Load as CustomerSectionLoadAction;
use Magento\Framework\Session\Generic as GenericSession;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use Magento\TestFramework\TestCase\AbstractController;

/**
 * @magentoAppIsolation enabled
 * @magentoAppArea frontend
 */
abstract class AbstractSessionTest extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        parent::setUp();
        $this->objectManager = Bootstrap::getObjectManager();
        $this->setUpSpies();
    }

    protected function given_session_already_exists(): void
    {
        $this->dispatch('customer/account/login');
        MethodLog::instance()->reset();
    }

    protected function when_dispatched($uri): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET'; // to make \Magento\Framework\App\Request\Http::isSafeMethod() return true
        $this->dispatch($uri);
    }

    protected function then_sessions_have_been_started_and_closed_before_action()
    {
        $methodLog = MethodLog::instance();
        $this->assertTrue(
            $methodLog->hasSessionWriteCloseBeforeAction(),
            "Session should be closed before controller action. Method log: \n" .
            $methodLog->asString()
        );
        $this->assertFalse(
            $methodLog->hasSessionStartAfterAction(),
            "No session should be initialized during or after controller action. Method log: \n" .
            $methodLog->asString()
        );
        $this->assertTrue(
            $methodLog->hasSessionStartBeforeAction(),
            "Session should be initialized before controller action. Method log: \n" .
            $methodLog->asString()
        );
    }

    protected function then_sessions_have_not_been_modified_after_write()
    {
        $methodLog = MethodLog::instance();
        $this->assertFalse(
            $methodLog->hasModifyAfterSessionWriteClose(),
            "Session should not be modified after close. Method log: \n" .
            $methodLog->asString()
        );
    }

    private function setUpSpies(): void
    {
        $this->objectManager->configure(
            [
                'preferences'               => [
                    BannerAjaxLoadAction::class      => BannerAjaxLoadActionSpy::class,
                    CustomerSectionLoadAction::class => CustomerSectionLoadActionSpy::class,
                    GenericSession::class            => SessionSpy::class,
                ],
                SessionStoragePlugin::class => [
                    'arguments' => ['doLogMethods' => true]
                ]
            ]
        );
    }
}
