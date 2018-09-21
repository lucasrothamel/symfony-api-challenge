<?php
/*
 * Author: Lucas Rothamel, Eye-Catching Webdesign
 * info@eye-catching-webdesign.de
 * www.eye-catching-webdesign.de
 */

namespace App\Controller;

use App\Repository\CategoryRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @Rest\RouteResource(
 *     "Category",
 *     pluralize=false
 * )
 */
class CategoryController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Returns all categories in the database
     * @Rest\View()
     * @SWG\Get(
     *     path="/category",
     *     @SWG\Response(
     *         response=200,
     *         description="list of categories",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=App\Entity\Category::class))
     *         )
     *     ),
     * )
     */
    public function cgetAction(): View
    {
        return $this->view($this->categoryRepository->findAll());
    }
}
