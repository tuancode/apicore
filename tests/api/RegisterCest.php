<?php

use Codeception\Util\HttpCode;
use AppBundle\Entity\User;

/**
 * RegisterCest.
 */
class RegisterCest
{
    /**
     * @param ApiTester $I
     *
     * @throws Exception
     */
    public function testRegisterSuccessful(\ApiTester $I)
    {
        $email = 'test@test.net';
        $password = '123456';
        $phone = '+841208777245';

        $I->sendPOST('/register.json', ['email' => $email, 'password' => $password, 'phone' => $phone]);
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
     * @before createUserInRepository
     *
     * @param ApiTester $I
     *
     * @throws Exception
     */
    public function testRegisterByInvalidEmail(\ApiTester $I)
    {
        $I->comment('---Blank Email---');
        $I->sendPOST('/register.json', ['email' => '', 'password' => '123456', 'phone' => '+841208777245']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'email' => ['This value should not be blank.'],
            ]
        );

        $I->comment('---Invalid Email---');
        $I->sendPOST('/register.json', ['email' => 'email', 'password' => '123456', 'phone' => '+841208777245']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'email' => ['This value is not a valid email address.'],
            ]
        );

        $I->comment('---Duplicate Email---');
        $I->sendPOST('/register.json', ['email' => 'user@test.net', 'password' => '123456', 'phone' => '+8412087215']);
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
    public function testRegisterByInvalidPassword(\ApiTester $I)
    {
        $I->comment('---Blank Password---');
        $I->sendPOST('/register.json', ['email' => 'test@example.net', 'password' => '', 'phone' => '+841208777245']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'password' => ['This value should not be blank.'],
            ]
        );
    }

    /**
     * @before createUserInRepository
     *
     * @param ApiTester $I
     *
     * @throws Exception
     */
    public function testRegisterByInvalidPhone(\ApiTester $I)
    {
        $I->comment('---Invalid Phone---');
        $I->sendPOST('/register.json', ['email' => 'test@example.net', 'password' => '123456', 'phone' => '08777245']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'phone' => ['This value is not valid.'],
            ]
        );

        $I->comment('---Duplicate Phone---');
        $I->sendPOST('/register.json', ['email' => 'test@test.net', 'password' => '123456', 'phone' => '+8412000001']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'phone' => ['This value is already used.'],
            ]
        );
    }

    /**
     * @param ApiTester $I
     */
    protected function createUserInRepository(\ApiTester $I)
    {
        $I->haveInRepository(
            User::class,
            [
                'username' => 'user@test.net',
                'email' => 'user@test.net',
                'plainPassword' => '123456',
                'phone' => '+8412000001',
                'status' => User::STATUS_ACTIVE,
                'enabled' => 1,
            ]
        );
    }
}
