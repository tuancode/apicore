<?php

use Codeception\Util\HttpCode;
use AppBundle\Entity\User;

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
    public function registerSuccess(\ApiTester $I)
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
     * @param ApiTester      $I
     * @param \Step\Api\User $u
     */
    public function registerByInvalidEmail(\ApiTester $I, \Step\Api\User $u)
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
        $user = $u->createDummyUser();
        $I->sendPOST($this->url, ['email' => $user->getEmail(), 'password' => '123456', 'phone' => '+8412087215']);
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
    public function registerByInvalidPassword(\ApiTester $I)
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
     * @param ApiTester      $I
     * @param \Step\Api\User $u
     */
    public function registerByInvalidPhone(\ApiTester $I, \Step\Api\User $u)
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
        $user = $u->createDummyUser();
        $I->sendPOST($this->url, ['email' => 'test@test.net', 'password' => '123456', 'phone' => $user->getPhone()]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseContainsJson(
            [
                'phone' => ['This value is already used.'],
            ]
        );
    }
}
