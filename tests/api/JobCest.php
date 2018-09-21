<?php

namespace App\Tests;

use Codeception\Util\HttpCode;

class JobCest
{
    /**
     * @return array
     */
    protected function jobs()
    {
        return [
            [
                "id" => 1,
                "zip_code" => ["id" => 1, "code" => "10115", "city" => "Berlin"],
                "category" => [
                    "id" => 3,
                    "title" => "Abtransport, Entsorgung und Entrümpelung",
                    "category_id" => 802030,
                    "icon_path" => null,
                    "parent_id" => null,
                ],
                "user" => ['id' => '1', 'name' => 'Joe Doe'],
                "title" => "Ein Auftrag",
                "description" => "Gaaaaanz viel text",
            ],
            [
                "id" => 2,
                "zip_code" => ["id" => 5, "code" => "06895", "city" => "Bülzig"],
                "category" => [
                    "id" => 2,
                    "title" => "Sonstige Umzugsleistungen",
                    "category_id" => 804040,
                    "icon_path" => null,
                    "parent_id" => 1,
                ],
                "user" => ['id' => '3', 'name' => 'Michaela Musterfrau'],
                "title" => "Ein zweiter Auftrag",
                "description" => "Lorem Ipsum Dolor Sit Amet",
            ],
        ];
    }

    public function testPost(ApiTester $api)
    {
        $testJob = [
            "title" => "foobar",
            "zip_code" => '1',
            "description" => "We need a new roof, and fast!",
            'category' => '1',
        ];
        $api->sendPOST("/job", $testJob);
        $api->seeResponseCodeIs(HttpCode::CREATED);
        $api->seeResponseIsJson();
        $api->seeResponseContainsJson(["status" => "ok"]);

        $response = $api->grabResponse();
        $data = json_decode($response, true);
        $jobId = $data["id"];

        $api->sendGET("/job/" . $jobId);

        $api->seeResponseCodeIs(HttpCode::OK);
        $api->seeResponseIsJson();
        $api->seeResponseContainsJson(["city" => "Berlin"]);
        $api->seeResponseContainsJson(["category_id" => 111]);
        $api->seeResponseContainsJson(["title" => $testJob["title"]]);
        $api->seeResponseContainsJson(["description" => $testJob["description"]]);
    }

    public function testPostEmpty(ApiTester $api)
    {
        $expectedValidation = [
            "detail" =>
                "zip_code: Bitte geben Sie eine Postleitzahl an\n"
                . "title: Bitte geben Sie einen Auftragstitel ein\n"
                . "description: Bitte geben Sie eine Beschreibung ein\n"
                . "category: Bitte geben Sie eine Kategorie an",
        ];

        $api->sendPOST("/job", []);

        $api->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $api->seeResponseIsJson();
        $api->seeResponseContainsJson($expectedValidation);
    }

    /**
     * Testing the /job endpoint delivers all required data
     * @param ApiTester $api
     */
    public function testCGet(ApiTester $api)
    {
        $api->sendGET("/job");
        $api->seeResponseCodeIs(HttpCode::OK);
        $api->seeResponseIsJson();
        $api->seeResponseContainsJson($this->jobs());

        foreach ($this->jobs() as $job) {
            $api->seeResponseContainsJson($job);
        }
    }

    /**
     * Testing all the individual /job/<id> endpoints
     * @param ApiTester $api
     */
    public function testGet(ApiTester $api)
    {
        foreach ($this->jobs() as $job) {
            $api->sendGET("/job/" . $job["id"]);

            $api->seeResponseCodeIs(HttpCode::OK);
            $api->seeResponseIsJson();
            $api->seeResponseContainsJson($job);
        }
    }

    /**
     * Testing an invalid id at /job/<id> endpoint
     * @param ApiTester $api
     */
    public function testInvalidGet(ApiTester $api)
    {
        $api->sendGET("/job/1929292929");

        $api->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $api->seeResponseIsJson();
    }
}
