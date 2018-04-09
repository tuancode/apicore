<?php

use AppBundle\Entity\User;
use Codeception\Util\HttpCode;
use Step\Api\UserStep;

/**
 * GetUserCest.
 */
class GetUserCest
{
    /**
     * Dummy data.
     *
     * @var array
     */
    private static $user = ['email' => 'dummy1@test.net', 'phone' => '+84120000001', 'status' => User::STATUS_ACTIVE];

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
        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     * @param UserStep  $user
     *
     * @throws Exception
     */
    public function getUserSuccess(\ApiTester $I, UserStep $user): void
    {
        $userId = $user->createUser(self::$user['email'], self::$user['phone'], self::$user['status']);

        $user->login();
        $I->sendGET(sprintf($this->url, $userId));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(self::$user);
    }

    /**
     * @param ApiTester $I
     * @param UserStep  $user
     *
     * @throws Exception
     */
    public function getUserNotFound(\ApiTester $I, UserStep $user): void
    {
        $user->login();
        $I->sendGET(sprintf($this->url, 29));
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
