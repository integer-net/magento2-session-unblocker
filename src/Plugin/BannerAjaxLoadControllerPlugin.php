<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Plugin;

use Magento\Framework\Session\Generic as GenericSession;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * We are writing this plugin to make sure sessions are all loaded
 * Magento\Banner\Controller\Ajax\Load
 *
 * Right after initiating all the needed Sessions we close the session,
 * since we're only reading from the session when requesting banners.
 */
class BannerAjaxLoadControllerPlugin
{
    /**
     * @param GenericSession $genericSession
     * @param SessionManagerInterface[] $additionalSessions
     *
     * Disabling 3 PHPCS rules because:
     * 1 - We are well aware that we normally shouldn't call Sessions without
     *     proxy, but in this case, we actually want the sessions to be
     *     initiated directly.
     * 2 - Also, we don't actually use the Sessions
     * 3 - Lastly, we normally should not execute operations in a constructor
     *
     * phpcs:disable MEQP2.Classes.MutableObjects.MutableObjects
     * phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.Found
     * phpcs:disable MEQP2.Classes.ConstructorOperations.CustomOperationsFound
     */
    public function __construct(
        GenericSession $genericSession,
        array $additionalSessions = []
    ) {
        /**
         * This is earliest moment where we can close the session,
         * after we initialised all sessions we think will be needed
         *
         * Should there ever be an additional Session-type that's needed,
         * nothing breaks, but the new session-type will open a new session
         * and therefore block other requests
         */
        $genericSession->writeClose();
    }

    //phpcs:ignore MEQP2.Classes.PublicNonInterfaceMethods.PublicMethodFound
    public function beforeExecute()
    {
    }
}
