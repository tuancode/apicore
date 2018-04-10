<?php

namespace Tests\_support\Traits;

use Step\Api\UserStep;

/**
 * AuthAwareTrait.
 */
trait AuthAwareTrait
{
    /**
     * Do login.
     *
     * @param UserStep $userStep
     *
     * @throws \Exception
     */
    protected function login(UserStep $userStep): void
    {
        $userStep->login();
    }
}
