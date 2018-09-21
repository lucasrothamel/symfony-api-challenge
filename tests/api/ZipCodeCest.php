<?php

namespace App\Tests;

class ZipCodeCest
{
    private $cities = [
        ["id" => 1, "code" => "10115", "city" => "Berlin"],
        ["id" => 2, "code" => "32457", "city" => "Porta Westfalica"],
        ["id" => 3, "code" => "01623", "city" => "Lommatzsch"],
        ["id" => 4, "code" => "21521", "city" => "Hamburg"],
        ["id" => 5, "code" => "06895", "city" => "Bülzig"],
        ["id" => 6, "code" => "01612", "city" => "Diesbar-Seußlitz"],
    ];

    private $berlin = ["id" => 1, "code" => "10115", "city" => "Berlin"];

    public function testAll(ApiTester $api)
    {
        $api->sendGET("/zipcode");

        $api->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $api->seeResponseIsJson();

        foreach ($this->cities as $city) {
            $api->seeResponseContainsJson($city);
        }
    }

    public function testKnownZipcode(ApiTester $api)
    {
        $api->sendGET("/zipcode/" . $this->berlin["code"]);
        $api->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $api->seeResponseIsJson();
        $api->seeResponseContainsJson($this->berlin);
    }

    public function testUnknownZipcode(ApiTester $api)
    {
        $api->sendGET("/zipcode/12345");
        $api->seeResponseCodeIs(\Codeception\Util\HttpCode::NOT_FOUND);
        $api->seeResponseIsJson();
    }
}
