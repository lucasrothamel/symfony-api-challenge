<?php

namespace App\Tests;

class HealthcheckCest
{
    public function testPing(ApiTester $I)
    {
        $I->sendGET("/ping");
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseEquals('"pong"');
    }
}
