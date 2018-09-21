<?php
/*
 * Author: Lucas Rothamel, Eye-Catching Webdesign
 * info@eye-catching-webdesign.de
 * www.eye-catching-webdesign.de
 */

namespace App\Controller;

use App\Entity\Job;
use App\Repository\CategoryRepository;
use App\Repository\JobRepository;
use App\Repository\UserRepository;
use App\Repository\ZipCodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Rest\RouteResource(
 *     "Job",
 *     pluralize=false
 * )
 */
class JobController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var JobRepository
     */
    private $jobRepository;

    /**
     * @var ZipCodeRepository
     */
    private $zipCodeRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        JobRepository $jobRepository,
        ZipCodeRepository $zipCodeRepository,
        CategoryRepository $categoryRepository,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->jobRepository = $jobRepository;
        $this->zipCodeRepository = $zipCodeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * get all the jobs in the database
     * @SWG\Response(
     *     response=200,
     *     description="Returns all the jobs with their information",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=App\Entity\Job::class))
     *     )
     * )
     * @return View
     */
    public function cgetAction(): View
    {
        return $this->view($this->jobRepository->findAll());
    }

    /**
     * retrieve details of one given job
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="a unique job id"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns the job with their information based on the given unique id",
     *     @Model(type=App\Entity\Job::class)
     * )
     * @SWG\Response(
     *     response=404,
     *     description="If the given unique id could not be found in the database"
     * )
     * @param string $jobId
     * @return View
     */
    public function getAction(string $jobId): View
    {
        $element = $this->jobRepository->find($jobId);

        if ($element == null) {
            throw new NotFoundHttpException("job {$jobId} not found");
        }

        return $this->view($element);
    }

    /**
     * Save a new job to the database
     * @Rest\Post("/job")
     * @SWG\Post(
     *     path="/job",
     *     @SWG\Parameter(
     *         name="title",
     *         in="query",
     *         required=true,
     *         type="string",
     *         description="Name of the job to be created"
     *     ),
     *     @SWG\Parameter(
     *         name="description",
     *         in="query",
     *         required=true,
     *         type="string",
     *         description="Description of the job to be created"
     *     ),
     *     @SWG\Parameter(
     *         name="zip_code",
     *         in="query",
     *         required=true,
     *         type="integer",
     *         description="unique id of ZipCode (Postleitzahl)"
     *     ),
     *     @SWG\Parameter(
     *         name="category",
     *         in="query",
     *         required=true,
     *         type="integer",
     *         description="unique id of category"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         examples={"application/json":{"status":"ok","id":25,},},
     *         description="created the job, returning the unique job Id created",
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="validation of parameters failed",
     *     ),
     * )
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return View
     */
    public function postAction(Request $request, ValidatorInterface $validator): View
    {
        $defaultUser = 1; # TODO: this should be read from user login / session data
        $zipCode = $this->zipCodeRepository->find($request->get("zip_code", 0));
        $category = $this->categoryRepository->find($request->get("category", 0));
        $user = $this->userRepository->find($defaultUser);

        $job = new Job();
        $job->setTitle($request->get("title", ""));
        $job->setDescription($request->get("description", ""));
        $job->setZipCode($zipCode);
        $job->setCategory($category);
        $job->setUser($user);

        $errors = $validator->validate($job);

        if (count($errors) > 0) {
            return View::create($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $this->view(['status' => 'ok', 'id' => $job->getId()], Response::HTTP_CREATED);
    }
}
