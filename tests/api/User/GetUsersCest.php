<?php

namespace User;

use AppBundle\Entity\User;
use Codeception\Util\HttpCode;
use Step\Api\UserStep;

/**
 * GetUserCest.
 */
class GetUsersCest
{
    /**
     * Users data.
     *
     * @var array
     */
    private static $users = [
        ['email' => 'dummy1@test.net', 'phone' => '+84120000001', 'status' => User::STATUS_ACTIVE],
        ['email' => 'dummy2@test.net', 'phone' => '+84120000002', 'status' => User::STATUS_SUSPENDED],
    ];

    /**
     * Base url of cest.
     *
     * @var string
     */
    private $url = '/user.json';

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
    public function getUsersSuccess(\ApiTester $I, UserStep $u): void
    {
        foreach (static::$users as $index => $user) {
            $u->createUser($user['email'], $user['phone'], $user['status']);
        }

        $u->login();

        $I->comment('---With Pagination---');
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

        $I->comment('---Without Pagination---');
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

    /**
     * @param \ApiTester $I
     * @param UserStep   $u
     *
     * @throws \Exception
     */
    public function getUsersFilteredByEmail(\ApiTester $I, UserStep $u): void
    {
        foreach (static::$users as $index => $user) {
            $u->createUser($user['email'], $user['phone'], $user['status']);
        }

        $emailFilter = 'dummy1@test.net';

        $u->login();
        $I->sendGET(sprintf('%s?pagination=0&filters[email]=%s', $this->url, $emailFilter));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(static::$users[0]);
        $I->dontSeeResponseContainsJson(static::$users[1]);
    }

    /**
     * @param \ApiTester $I
     * @param UserStep   $u
     *
     * @throws \Exception
     */
    public function getUsersFilteredByPhone(\ApiTester $I, UserStep $u): void
    {
        foreach (static::$users as $index => $user) {
            $u->createUser($user['email'], $user['phone'], $user['status']);
        }

        $phoneFilter = urlencode('+84120000001');

        $u->login();
        $I->sendGET(sprintf('%s?pagination=0&filters[phone]=%s', $this->url, $phoneFilter));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(static::$users[0]);
        $I->dontSeeResponseContainsJson(static::$users[1]);
    }

    /**
     * @param \ApiTester $I
     * @param UserStep   $u
     *
     * @throws \Exception
     */
    public function getUsersFilteredByStatus(\ApiTester $I, UserStep $u): void
    {
        foreach (static::$users as $index => $user) {
            $u->createUser($user['email'], $user['phone'], $user['status']);
        }

        $statusFilter = 'A';

        $u->login();
        $I->sendGET(sprintf('%s?pagination=0&filters[status]=%s', $this->url, $statusFilter));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(static::$users[0]);
        $I->dontSeeResponseContainsJson(static::$users[1]);
    }

    /**
     * @param \ApiTester $I
     * @param UserStep   $u
     *
     * @throws \Exception
     */
    public function getUsersByCombinedFilters(\ApiTester $I, UserStep $u): void
    {
        foreach (static::$users as $index => $user) {
            $u->createUser($user['email'], $user['phone'], $user['status']);
        }

        $emailFilter = 'dummy1@test.net';
        $statusFilter = 'A';

        $u->login();
        $I->sendGET(
            sprintf('%s?pagination=0&filters[email]=%s&filters[status]=%s', $this->url, $emailFilter, $statusFilter)
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(static::$users[0]);
        $I->dontSeeResponseContainsJson(static::$users[1]);
    }
}
