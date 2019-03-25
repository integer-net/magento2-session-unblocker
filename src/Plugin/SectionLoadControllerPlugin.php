<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Plugin;

use Magento\Framework\Session\Generic as GenericSession;
use Magento\Framework\Session\SessionManagerInterface;

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
        $hasMessages = 0;
        foreach ($additionalSessions as $session) {
            if ($session instanceOf \Magento\Framework\Message\Session){
                // @param \Magento\Framework\Message\Collection $messageCollection
                foreach ($session->getData() as $messageCollection){
                    $hasMessages += count($messageCollection->getItems());
                }
            }
        }
        /**
         * We've checked if there were no messages in the current session
         * because the session then needs to stay open to allow the messages
         * to be removed after loading them
        */
        if ($hasMessages === 0 ) {
            $genericSession->writeClose();
        }
    }

    //phpcs:ignore MEQP2.Classes.PublicNonInterfaceMethods.PublicMethodFound
    public function beforeExecute()
    {
    }
}
