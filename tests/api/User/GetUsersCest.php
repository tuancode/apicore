<?php

namespace User;

use Codeception\Util\HttpCode;
use Helper\Api;
use Step\Api\UserStep;

/**
 * GetUserCest.
 */
class GetUsersCest
{
    /**
     * Base url of cest.
     *
     * @var string
     */
    private $url = '/user.json';

    /**
     * Users data.
     *
     * @var array
     */
    private static $users = [
        ['email' => 'dummy1@test.net', 'phone' => '+84120000001'],
        ['email' => 'dummy2@test.net', 'phone' => '+84120000002'],
        ['email' => 'dummy3@test.net', 'phone' => '+84120000003'],
    ];

    /**
     * @param \ApiTester $I
     */
    public function unauthorized(\ApiTester $I): void
    {
        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    /**
     * @param \ApiTester $I
     * @param UserStep   $u
     *
     * @throws \Exception
     */
    public function getUsersSuccessWithPagination(\ApiTester $I, UserStep $u): void
    {
        $u->login();
        foreach (static::$users as $index => $user) {
            $u->createUser($user['email'], 123456, $user['phone']);
        }

        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesJsonType(
            [
                'items' => 'array',
                'total' => 'integer',
                'count' => 'integer',
                'links' => 'array',
            ]
        );
        $I->seeResponseContainsJson(['items' => static::$users]);
    }

    /**
     * @param \ApiTester $I
     * @param UserStep   $u
     *
     * @throws \Exception
     */
    public function getUsersSuccessWithoutPagination(\ApiTester $I, UserStep $u, Api $api): void
    {
        $u->login();
        foreach (static::$users as $index => $user) {
            $u->createUser($user['email'], 123456, $user['phone']);
        }

        $I->sendGET($this->url.'?pagination=0');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesJsonType(
            [
                'id' => 'integer',
                'email' => 'string',
                'phone' => 'string',
                'created_date' => 'string',
                'updated_date' => 'string',
            ]
        );
        $I->seeResponseContainsJson(static::$users);
    }
}
