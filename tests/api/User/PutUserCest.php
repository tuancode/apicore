<?php

use AppBundle\Entity\User;
use Codeception\Util\HttpCode;
use Step\Api\UserStep;
use Tests\_support\Traits\AuthAwareTrait;

/**
 * Class PutUserCest.
 */
class PutUserCest
{
    use AuthAwareTrait;

    /**
     * @var array
     */
    private static $responseStructure = [
        'id' => 'integer',
        'email' => 'string',
        'status' => 'string',
        'enabled' => 'boolean',
        'created_date' => 'string',
        'updated_date' => 'string',
    ];

    /**
     * Dummy data.
     *
     * @var array
     */
    private static $user = [
        'email' => 'dummy@example.net',
        'phone' => '+841200000003',
        'status' => User::STATUS_ACTIVE,
    ];

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
        $I->sendPUT($this->url);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    /**
     * @before login
     *
     * @param ApiTester $I
     */
    public function putUserSuccess(\ApiTester $I): void
    {
        $user = $I->grabEntityFromRepository(User::class, ['email' => UserStep::ADMIN_EMAIL]);

        $param = [
            'email' => $user->getEmail(),
            'phone' => '+84120000011',
            'status' => User::STATUS_SUSPENDED,
            'enabled' => User::ENABLED,
        ];

        $I->sendPUT(sprintf($this->url, $user->getId()), $param);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesJsonType(self::$responseStructure);
        $I->seeResponseContainsJson($param);
    }

    /**
     * @before login
     * @before createDummyUser
     *
     * @param ApiTester $I
     *
     * @throws Exception
     */
    public function putUserByInvalidEmail(\ApiTester $I): void
    {
        $user = $I->grabEntityFromRepository(User::class, ['email' => self::$user['email']]);

        $I->comment('---Blank Email---');
        $I->sendPUT(
            sprintf($this->url, $user->getId()),
            ['email' => '', 'phone' => '+8412000001', 'status' => 'A', 'enabled' => true]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'email' => ['This value should not be blank.'],
            ]
        );

        $I->comment('---Invalid Email---');
        $I->sendPUT(
            sprintf($this->url, $user->getId()),
            ['email' => 'email', 'phone' => '+8412000001', 'status' => 'A', 'enabled' => true]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'email' => ['This value is not a valid email address.'],
            ]
        );

        $I->comment('---Duplicate Email---');
        $I->sendPUT(
            sprintf($this->url, $user->getId()),
            ['email' => UserStep::ADMIN_EMAIL, 'phone' => '+8412000001', 'status' => 'A', 'enabled' => true]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'email' => ['This value is already used.'],
            ]
        );
    }

    /**
     * @before login
     * @before createDummyUser
     *
     * @param ApiTester $I
     */
    public function putUserByInvalidPhone(\ApiTester $I): void
    {
        $user = $I->grabEntityFromRepository(User::class, ['email' => self::$user['email']]);

        $I->comment('---Invalid Phone---');
        $I->sendPUT(
            sprintf($this->url, $user->getId()),
            ['email' => 'email', 'phone' => '12000001', 'status' => 'A', 'enabled' => true]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'phone' => ['This value is not valid.'],
            ]
        );

        $I->comment('---Duplicate Phone---');
        $I->sendPUT(
            sprintf($this->url, $user->getId()),
            [
                'email' => 'test@example.net',
                'phone' => UserStep::ADMIN_PHONE,
                'status' => User::STATUS_ACTIVE,
                'enabled' => User::ENABLED,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'phone' => ['This value is already used.'],
            ]
        );
    }

    /**
     * @before login
     *
     * @param ApiTester $I
     */
    public function putUserByInvalidStatus(\ApiTester $I): void
    {
        $user = $I->grabEntityFromRepository(User::class, ['email' => UserStep::ADMIN_EMAIL]);

        $I->comment('---Invalid Status---');
        $I->sendPUT(
            sprintf($this->url, $user->getId()),
            [
                'email' => 'test@test.net',
                'phone' => '+8412000001',
                'status' => 'T',
                'enabled' => true,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'status' => ['This value is not valid.'],
            ]
        );
    }

    /**
     * @before  login
     *
     * @param ApiTester $I
     */
    public function putUserByInvalidEnabled(\ApiTester $I): void
    {
        $user = $I->grabEntityFromRepository(User::class, ['email' => UserStep::ADMIN_EMAIL]);

        $I->comment('---Invalid Enabled---');
        $I->sendPUT(
            sprintf($this->url, $user->getId()),
            [
                'email' => 'test@test.net',
                'phone' => '+8412000001',
                'status' => 'A',
                'enabled' => 3,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'enabled' => ['This value is not valid.'],
            ]
        );
    }

    /**
     * @param UserStep $userStep
     */
    protected function createDummyUser(UserStep $userStep): void
    {
        $userStep->createUser(self::$user['email'], self::$user['phone'], self::$user['status']);
    }
}
