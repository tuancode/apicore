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
    private $url = '/login.json';

    /**
     * @param ApiTester      $I
     * @param \Step\Api\User $u
     */
    public function loginSuccess(\ApiTester $I, \Step\Api\User $u)
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
     * @param ApiTester      $I
     * @param \Step\Api\User $u
     */
    public function loginFailed(\ApiTester $I, \Step\Api\User $u)
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
