<?php

namespace App\Tests;

use Codeception\Util\HttpCode;

class DocumentationCest
{
    public function testApiDocCompiles(ApiTester $api)
    {
        $api->sendGET("/api/doc.json");
        $api->seeResponseCodeIs(HttpCode::OK);
    }
}
