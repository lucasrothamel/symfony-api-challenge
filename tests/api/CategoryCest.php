<?php

namespace App\Tests;

class CategoryCest
{
    private $categories = [
        0 => [
            "id" => 1,
            "title" => "Umzüge und Transport",
            "category_id" => 111,
            "icon_path" => null,
            "parent_id" => null,
        ],
        1 => [
            "id" => 2,
            "title" => "Sonstige Umzugsleistungen",
            "category_id" => 804040,
            "icon_path" => null,
            "parent_id" => 1,
        ],
        2 => [
            "id" => 3,
            "title" => "Abtransport, Entsorgung und Entrümpelung",
            "category_id" => 802030,
            "icon_path" => null,
            "parent_id" => null,
        ],
        3 => [
            "id" => 4,
            "title" => "Fensterreinigung",
            "category_id" => 411070,
            "icon_path" => null,
            "parent_id" => null,
        ],
        4 => [
            "id" => 5,
            "title" => "Holzdielen schleifen",
            "category_id" => 402020,
            "icon_path" => null,
            "parent_id" => null,
        ],
        5 => [
            "id" => 6,
            "title" => "Kellersanierung",
            "category_id" => 108140,
            "icon_path" => null,
            "parent_id" => null,
        ],
    ];

    public function testAll(ApiTester $api)
    {
        $api->sendGET("/category");

        $api->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $api->seeResponseIsJson();

        foreach ($this->categories as $category) {
            $api->seeResponseContainsJson($category);
        }
    }
}
