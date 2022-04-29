<?php
declare(strict_types=1);

namespace IntegerNet\SessionUnblocker\Test\Integration;

class BannerAjaxLoadControllerTest extends AbstractSessionTest
{
    public function testBannerAjaxLoad()
    {
        $this->when_dispatched('banner/ajax/load');
        $this->then_sessions_have_been_started_and_closed_before_action();
    }

    public function testBannerAjaxLoadNoWrites()
    {
        $this->given_session_already_exists();
        $this->when_dispatched('banner/ajax/load');
        $this->then_sessions_have_not_been_modified_after_write();
    }
}
