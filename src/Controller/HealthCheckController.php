<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HealthCheckController extends FOSRestController
{
    /**
     * ping-pong connection health test, returns a json "pong"
     * @Get(path="/ping")
     * @SWG\Response(
     *     response=200,
     *     description="json string 'pong'",
     * )
     * @Rest\View()
     */
    public function getAction(): Response
    {
        return new JsonResponse('pong');
    }
}
