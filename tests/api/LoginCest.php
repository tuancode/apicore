<?php

use Codeception\Util\HttpCode;

/**
 * LoginCest.
 */
class LoginCest
{
    /**
     * @var string
     */
    private $email = 'login@test.net';

    /**
     * @var string
     */
    private $password = '123456';

    public function _before(\ApiTester $I)
    {
        $email = 'login@test.net';
        $password = '123456';
        $phone = '+841200000001';

        $I->sendPOST('/register.json', ['email' => $email, 'password' => $password, 'phone' => $phone]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @param ApiTester $I
     *
     * @throws Exception
     */
    public function testLoginSuccessful(\ApiTester $I)
    {
        $I->sendPOST('/login.json', ['email' => $this->email, 'password' => $this->password]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesJsonType(
            [
                'access_token' => 'string',
            ]
        );
    }

    /**
     * @param ApiTester $I
     *
     * @throws Exception
     */
    public function testLoginFailed(\ApiTester $I)
    {
        $I->sendPOST('/login.json', ['email' => $this->email, 'password' => 'failed']);
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
}
