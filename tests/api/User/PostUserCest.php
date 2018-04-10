<?php

use AppBundle\Entity\User;
use Codeception\Util\HttpCode;
use Step\Api\UserStep;
use Tests\_support\Traits\AuthAwareTrait;

/**
 * Class PostUserCest.
 */
class PostUserCest
{
    use AuthAwareTrait;

    /**
     * Base url of cest.
     *
     * @var string
     */
    private $url = '/user.json';

    /**
     * @param ApiTester $I
     */
    public function unauthorized(\ApiTester $I): void
    {
        $I->sendPOST($this->url);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }

    /**
     * @before login
     *
     * @param ApiTester $I
     */
    public function postUserSuccess(\ApiTester $I): void
    {
        $param = [
            'email' => 'dummy1@test.net',
            'phone' => '+84120000001',
            'password' => 123456,
            'status' => User::STATUS_ACTIVE,
            'enabled' => User::ENABLED,
        ];
        $expected = [
            'email' => 'dummy1@test.net',
            'phone' => '+84120000001',
            'status' => User::STATUS_ACTIVE,
            'enabled' => User::ENABLED,
        ];

        $I->sendPOST($this->url, $param);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesJsonType(
            [
                'id' => 'integer',
                'email' => 'string',
                'status' => 'string',
                'enabled' => 'boolean',
                'created_date' => 'string',
                'updated_date' => 'string',
            ]
        );
        $I->seeResponseContainsJson($expected);
    }

    /**
     * @before login
     *
     * @param ApiTester $I
     */
    public function postUserByInvalidEmail(\ApiTester $I): void
    {
        $I->comment('---Blank Email---');
        $I->sendPOST(
            $this->url,
            ['email' => '', 'password' => '123456', 'phone' => '+8412000001', 'status' => 'A', 'enabled' => true]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'email' => ['This value should not be blank.'],
            ]
        );

        $I->comment('---Invalid Email---');
        $I->sendPOST(
            $this->url,
            ['email' => 'email', 'password' => '123456', 'phone' => '+8412000001', 'status' => 'A', 'enabled' => true]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'email' => ['This value is not a valid email address.'],
            ]
        );

        $I->comment('---Duplicate Email---');
        $I->sendPOST(
            $this->url,
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
     *
     * @param ApiTester $I
     */
    public function postUserByInvalidPassword(\ApiTester $I): void
    {
        $I->comment('---Blank Password---');
        $I->sendPOST(
            $this->url,
            [
                'email' => 'dummy@example.net',
                'password' => '',
                'phone' => '+8412000001',
                'status' => 'A',
                'enabled' => 1,
            ]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'password' => ['This value should not be blank.'],
            ]
        );
    }

    /**
     * @before login
     *
     * @param ApiTester $I
     */
    public function postUserByInvalidPhone(\ApiTester $I): void
    {
        $I->comment('---Invalid Phone---');
        $I->sendPOST(
            $this->url,
            ['email' => 'email', 'password' => '123456', 'phone' => '12000001', 'status' => 'A', 'enabled' => true]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'phone' => ['This value is not valid.'],
            ]
        );

        $I->comment('---Duplicate Phone---');
        $I->sendPOST(
            $this->url,
            [
                'email' => 'test@test.net',
                'password' => '123456',
                'phone' => UserStep::ADMIN_PHONE,
                'status' => 'A',
                'enabled' => true,
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
    public function postUserByInvalidStatus(\ApiTester $I): void
    {
        $I->comment('---Invalid Status---');
        $I->sendPOST(
            $this->url,
            [
                'email' => 'test@test.net',
                'password' => '123456',
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
    public function postUserByInvalidEnabled(\ApiTester $I): void
    {
        $I->comment('---Invalid Enabled---');
        $I->sendPOST(
            $this->url,
            [
                'email' => 'test@test.net',
                'password' => '123456',
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
}
