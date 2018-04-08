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
     * @param ApiTester          $I
     * @param \Step\Api\UserStep $u
     */
    public function loginSuccess(\ApiTester $I, UserStep $u)
    {
        $user = $u->createDummyUser();
        $I->sendPOST($this->url, ['email' => $user->getEmail(), 'password' => $user->getPassword()]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesJsonType(
            [
                'access_token' => 'string',
            ]
        );
    }

    /**
     * @param ApiTester          $I
     * @param \Step\Api\UserStep $u
     */
    public function loginFailed(\ApiTester $I, UserStep $u)
    {
        $I->comment('---Not found User---');
        $I->sendPOST($this->url, ['email' => 'nouser@test.net', 'password' => 'failed']);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);

        $I->comment('---Unauthorized---');
        $user = $u->createDummyUser();
        $I->sendPOST($this->url, ['email' => $user->getEmail(), 'password' => 'failed']);
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
