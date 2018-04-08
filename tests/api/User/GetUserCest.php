<?php

namespace User;

use Codeception\Util\HttpCode;

/**
 * GetUserCest.
 */
class GetUserCest
{
    private $url = '/user.json';

    // tests
    public function unauthorized(\ApiTester $I)
    {
        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
    }
}
