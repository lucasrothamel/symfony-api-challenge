<?php
/*
 * Author: Lucas Rothamel, Eye-Catching Webdesign
 * info@eye-catching-webdesign.de
 * www.eye-catching-webdesign.de
 */

namespace App\Controller;

use App\Repository\ZipCodeRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Rest\RouteResource(
 *     "Zipcode",
 *     pluralize=false
 * )
 */
class ZipcodeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @var ZipCodeRepository
     */
    private $zipcodeRepository;

    public function __construct(ZipCodeRepository $zipcodeRepository)
    {
        $this->zipcodeRepository = $zipcodeRepository;
    }

    /**
     * Returns all zip codes in the database
     * @SWG\Response(
     *     response=200,
     *     description="Returns all the zipcodes and their corresponding database ids",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=App\Entity\ZipCode::class))
     *     )
     * )
     * @Rest\View()
     * @return View
     */
    public function cgetAction(): View
    {
        return $this->view($this->zipcodeRepository->findAll());
    }

    /**
     * Search for a german postcode in the database
     * @SWG\Parameter(
     *     name="code",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="A 5-digit postcode, eg. 10106"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns the zip_code id of the database entry of the zip code",
     *     @Model(type=App\Entity\ZipCode::class),
     * )
     * @SWG\Response(
     *     response=404,
     *     description="If the given postcode could not be found in the database"
     * )
     * @param string $code the zipcode, eg. 64295, to search for. Always a 5-digit string.
     * @Rest\View()
     * @return View
     */
    public function getAction(string $code): View
    {
        $element = $this->zipcodeRepository->findOneBy(["code" => $code]);

        if ($element == null) {
            throw new NotFoundHttpException("code {$code} not found");
        }

        return $this->view($element);
    }
}
