<?php
/*
 * Author: Lucas Rothamel, Eye-Catching Webdesign
 * info@eye-catching-webdesign.de
 * www.eye-catching-webdesign.de
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ZipCodeRepository")
 * @ORM\Table(name="zip_codes")
 */
class ZipCode implements \JsonSerializable
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @SWG\Property(description="The unique identifier of the user.")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     * @SWG\Property(type="string", maxLength=5, description="The 5-digit german postcode.")
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @SWG\Property(type="string", maxLength=100, description="The city to which the postcode belongs.")
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Job", mappedBy="zip_code")
     * @SWG\Property(description="list of jobs related to this zipcode")
     */
    private $jobs;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    /**
     * @return Collection|Job[]
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setZipCode($this);
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->jobs->contains($job)) {
            $this->jobs->removeElement($job);
            // set the owning side to null (unless already changed)
            if ($job->getZipCode() === $this) {
                $job->setZipCode(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code Zipcode (PLZ)
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * provide a json serialisable version of the zipcode (associative array)
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'city' => $this->getCity(),
        ];
    }
}
