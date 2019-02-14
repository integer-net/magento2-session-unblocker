<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Plugin;

use \Magento\Framework\Session\Generic as GenericSession;
use \Magento\Customer\Model\Session as CustomerSession;
use \Magento\Framework\Message\Session as MessageSession;
use \Magento\Catalog\Model\Session as CatalogSession;

/**
 * We are writing this plugin to make sure sessions are all loaded before
 * Magento\Customer\Controller\Section\Load
 *
 * Right after initiating all the needed Sessions we close the session,
 * since we're only reading from the session when requesting sectionData
 * in the frontend.
 *
 * This means every sectionPool that is being loaded does not need to read
 * from an open session, which means calls to SectionLoad controller are
 * no longer blocking each other because they are waiting for the request to
 * finish and the close the session.
 */
class SectionLoadControllerPlugin
{
    /**
     * @param GenericSession $genericSession
     * @param CustomerSession $customerSession
     * @param MessageSession $messageSession
     * @param CatalogSession $catalogSession
     * @param GenericSession $reviewSession
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
        CustomerSession $customerSession,
        MessageSession $messageSession,
        CatalogSession $catalogSession,
        GenericSession $reviewSession //virtualType
    ) {
        /*
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
