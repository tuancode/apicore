<?php

use AppBundle\Entity\User;
use Codeception\Util\HttpCode;
use Step\Api\UserStep;
use Tests\_support\Traits\AuthAwareTrait;

/**
 * GetUserCollectionCest.
 */
class GetUserCollectionCest
{
    use AuthAwareTrait;

    /**
     * Dummy data.
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
     * @before login
     * @before createDummyUsers
     *
     * @param \ApiTester $I
     *
     * @throws \Exception
     */
    public function getUsersSuccess(\ApiTester $I): void
    {
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
     * @before login
     * @before createDummyUsers
     *
     * @param \ApiTester $I
     *
     * @throws \Exception
     */
    public function getUsersFilteredByEmail(\ApiTester $I): void
    {
        $emailFilter = 'dummy1@test.net';

        $I->sendGET(sprintf('%s?pagination=0&filters[email]=%s', $this->url, $emailFilter));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(static::$users[0]);
        $I->dontSeeResponseContainsJson(static::$users[1]);
    }

    /**
     * @before login
     * @before createDummyUsers
     *
     * @param \ApiTester $I
     *
     * @throws \Exception
     */
    public function getUsersFilteredByPhone(\ApiTester $I): void
    {
        $phoneFilter = urlencode('+84120000001');

        $I->sendGET(sprintf('%s?pagination=0&filters[phone]=%s', $this->url, $phoneFilter));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(static::$users[0]);
        $I->dontSeeResponseContainsJson(static::$users[1]);
    }

    /**
     * @before login
     * @before createDummyUsers
     *
     * @param \ApiTester $I
     *
     * @throws \Exception
     */
    public function getUsersFilteredByStatus(\ApiTester $I): void
    {
        $statusFilter = 'A';

        $I->sendGET(sprintf('%s?pagination=0&filters[status]=%s', $this->url, $statusFilter));
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(static::$users[0]);
        $I->dontSeeResponseContainsJson(static::$users[1]);
    }

    /**
     * @before login
     * @before createDummyUsers
     *
     * @param \ApiTester $I
     *
     * @throws \Exception
     */
    public function getUsersByCombinedFilters(\ApiTester $I): void
    {
        $emailFilter = 'dummy1@test.net';
        $statusFilter = 'A';

        $I->sendGET(
            sprintf('%s?pagination=0&filters[email]=%s&filters[status]=%s', $this->url, $emailFilter, $statusFilter)
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(static::$users[0]);
        $I->dontSeeResponseContainsJson(static::$users[1]);
    }

    /**
     * @param UserStep $userStep
     */
    protected function createDummyUsers(UserStep $userStep): void
    {
        foreach (static::$users as $index => $user) {
            $userStep->createUser($user['email'], $user['phone'], $user['status']);
        }
    }
}
