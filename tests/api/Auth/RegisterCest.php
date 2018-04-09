<?php

use Codeception\Util\HttpCode;
use AppBundle\Entity\User;
use Step\Api\UserStep;

/**
 * RegisterCest.
 */
class RegisterCest
{
    /**
     * @var string
     */
    private $url = '/register.json';

    /**
     * @param ApiTester $I
     *
     * @throws Exception
     */
    public function registerSuccess(\ApiTester $I): void
    {
        $email = 'test@test.net';
        $password = '123456';
        $phone = '+841208777245';

        $I->sendPOST($this->url, ['email' => $email, 'password' => $password, 'phone' => $phone]);
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
        $I->seeResponseContainsJson(
            [
                'email' => $email,
                'phone' => $phone,
                'status' => User::STATUS_ACTIVE,
                'enabled' => true,
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    public function registerByInvalidEmail(\ApiTester $I): void
    {
        $I->comment('---Blank Email---');
        $I->sendPOST($this->url, ['email' => '', 'password' => '123456', 'phone' => '+841208777245']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'email' => ['This value should not be blank.'],
            ]
        );

        $I->comment('---Invalid Email---');
        $I->sendPOST($this->url, ['email' => 'email', 'password' => '123456', 'phone' => '+841208777245']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'email' => ['This value is not a valid email address.'],
            ]
        );

        $I->comment('---Duplicate Email---');
        $I->sendPOST($this->url, ['email' => UserStep::ADMIN_EMAIL, 'password' => '123456', 'phone' => '+8412087215']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'email' => ['This value is already used.'],
            ]
        );
    }

    /**
     * @param ApiTester $I
     *
     * @throws Exception
     */
    public function registerByInvalidPassword(\ApiTester $I): void
    {
        $I->comment('---Blank Password---');
        $I->sendPOST($this->url, ['email' => 'test@example.net', 'password' => '', 'phone' => '+841208777245']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'password' => ['This value should not be blank.'],
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    public function registerByInvalidPhone(\ApiTester $I): void
    {
        $I->comment('---Invalid Phone---');
        $I->sendPOST($this->url, ['email' => 'test@example.net', 'password' => '123456', 'phone' => '08777245']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'phone' => ['This value is not valid.'],
            ]
        );

        $I->comment('---Duplicate Phone---');
        $I->sendPOST(
            $this->url,
            ['email' => 'test@test.net', 'password' => '123456', 'phone' => UserStep::ADMIN_PHONE]
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'phone' => ['This value is already used.'],
            ]
        );
    }
}
