<?php

use Codeception\Util\HttpCode;
use AppBundle\Entity\User;

/**
 * LoginCest.
 */
class LoginCest
{
    /**
     * @before createUserInRepository
     *
     * @param ApiTester $I
     *
     * @throws Exception
     */
    public function testLoginSuccessful(\ApiTester $I)
    {
        $I->sendPOST('/login.json', ['email' => 'login@test.net', 'password' => '123456']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesJsonType(
            [
                'access_token' => 'string',
            ]
        );
    }

    /**
     * @before createUserInRepository
     *
     * @param ApiTester $I
     *
     * @throws Exception
     */
    public function testLoginFailed(\ApiTester $I)
    {
        $I->comment('---Not found User---');
        $I->sendPOST('/login.json', ['email' => 'nouser@test.net', 'password' => 'failed']);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);

        $I->comment('---Unauthorized---');
        $I->sendPOST('/login.json', ['email' => 'login@test.net', 'password' => 'failed']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseContainsJson(
            [
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized',
                ],
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    protected function createUserInRepository(\ApiTester $I)
    {
        $I->haveInRepository(
            User::class,
            [
                'username' => 'login@test.net',
                'email' => 'login@test.net',
                'plainPassword' => '123456',
                'phone' => '+841200000001',
                'status' => User::STATUS_ACTIVE,
                'enabled' => 1,
            ]
        );
    }
}
