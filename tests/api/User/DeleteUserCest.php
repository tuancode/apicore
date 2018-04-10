<?php

use AppBundle\Entity\User;
use Codeception\Util\HttpCode;
use Step\Api\UserStep;

/**
 * Class DeleteUserCest.
 */
class DeleteUserCest
{
    /**
     * Base url of cest.
     *
     * @var string
     */
    private $url = '/user/%s.json';

    /**
     * @param ApiTester $I
     */
    public function unauthorized(\ApiTester $I): void
    {
        $I->sendDELETE($this->url);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    /**
     * @before login
     *
     * @param ApiTester $I
     */
    public function deleteUserSuccess(\ApiTester $I): void
    {
        $user = $I->grabEntityFromRepository(User::class, ['email' => UserStep::ADMIN_EMAIL]);

        $I->sendDELETE(sprintf($this->url, $user->getId()));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContains('true');
    }

    /**
     * @before login
     *
     * @param ApiTester $I
     */
    public function deleteUserNotFound(\ApiTester $I): void
    {
        $I->sendDELETE(sprintf($this->url, 159753));
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    /**
     * Do login.
     *
     * @param UserStep $userStep
     *
     * @throws Exception
     */
    private function login(UserStep $userStep): void
    {
        $userStep->login();
    }
}
