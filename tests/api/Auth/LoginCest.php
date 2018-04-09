<?php

use Codeception\Util\HttpCode;
use Step\Api\UserStep;

/**
 * LoginCest.
 */
class LoginCest
{
    /**
     * @var string
     */
    private $url = '/login.json';

    /**
     * @param ApiTester $I
     */
    public function loginSuccess(\ApiTester $I): void
    {
        $I->sendPOST($this->url, ['email' => UserStep::ADMIN_EMAIL, 'password' => UserStep::ADMIN_PASSWORD]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesJsonType(
            [
                'access_token' => 'string',
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    public function loginFailed(\ApiTester $I): void
    {
        $I->comment('---Not found User---');
        $I->sendPOST($this->url, ['email' => 'nouser@test.net', 'password' => 'failed']);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);

        $I->comment('---Unauthorized---');
        $I->sendPOST($this->url, ['email' => UserStep::ADMIN_EMAIL, 'password' => 'failed']);
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
