<?php

use AppBundle\Entity\User;
use Codeception\Util\HttpCode;
use Step\Api\UserStep;
use Tests\_support\Traits\AuthAwareTrait;

/**
 * GetUserCest.
 */
class GetUserCest
{
    use AuthAwareTrait;

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
     * @before login
     *
     * @param ApiTester $I
     * @param UserStep  $userStep
     *
     * @throws Exception
     */
    public function getUserSuccess(\ApiTester $I, UserStep $userStep): void
    {
        $example = ['email' => 'dummy1@test.net', 'phone' => +84120000001, 'status' => User::STATUS_ACTIVE];
        $userId = $userStep->createUser($example['email'], $example['phone'], $example['status']);

        $I->sendGET(sprintf($this->url, $userId));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($example);
    }

    /**
     * @before login
     *
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
