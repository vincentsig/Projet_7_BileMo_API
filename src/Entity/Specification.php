<?php

namespace App\Entity;

use App\Repository\SpecificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpecificationRepository::class)
 */
class Specification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $OS;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $storage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sim;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $network;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wifi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rear_camera_resolution;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $front_camera_resolution;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $display_resolution;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dimensions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $weight;

    /**
     * @ORM\OneToMany(targetEntity=Phone::class, mappedBy="specifications")
     */
    private $phones;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOS(): ?string
    {
        return $this->OS;
    }

    public function setOS(string $OS): self
    {
        $this->OS = $OS;

        return $this;
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(string $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getSim(): ?string
    {
        return $this->sim;
    }

    public function setSim(string $sim): self
    {
        $this->sim = $sim;

        return $this;
    }

    public function getNetwork(): ?string
    {
        return $this->network;
    }

    public function setNetwork(string $network): self
    {
        $this->network = $network;

        return $this;
    }

    public function getWifi(): ?string
    {
        return $this->wifi;
    }

    public function setWifi(string $wifi): self
    {
        $this->wifi = $wifi;

        return $this;
    }

    public function getRearCameraResolution(): ?string
    {
        return $this->rear_camera_resolution;
    }

    public function setRearCameraResolution(string $rear_camera_resolution): self
    {
        $this->rear_camera_resolution = $rear_camera_resolution;

        return $this;
    }

    public function getFrontCameraResolution(): ?string
    {
        return $this->front_camera_resolution;
    }

    public function setFrontCameraResolution(string $front_camera_resolution): self
    {
        $this->front_camera_resolution = $front_camera_resolution;

        return $this;
    }

    public function getDisplayResolution(): ?string
    {
        return $this->display_resolution;
    }

    public function setDisplayResolution(string $display_resolution): self
    {
        $this->display_resolution = $display_resolution;

        return $this;
    }

    public function getDimensions(): ?string
    {
        return $this->dimensions;
    }

    public function setDimensions(string $dimensions): self
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Collection|Phone[]
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
            $phone->setSpecifications($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phones->contains($phone)) {
            $this->phones->removeElement($phone);
            // set the owning side to null (unless alreary changed)
            if ($phone->getSpecifications() === $this) {
                $phone->setSpecifications(null);
            }
        }

        return $this;
    }
}
