<?php

namespace App\Entity;

use Hateoas\Configuration\Annotation as Hateoas;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PhoneRepository;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *      name = "self",
 *      href = @Hateoas\Route(
 *         "details_phone",
 *         parameters = { "id" = "expr(object.getId())" },
 *         absolute = true
 *      ),
 *     attributes={"method"="GET"},
 *     exclusion = @Hateoas\Exclusion(groups={"details","list"})
 * )
 *
 * @Hateoas\Relation(
 *    "list",
 *    href = @Hateoas\Route(
 *        "list_phones",
 *        absolute = true
 *    ),
 *     attributes={"method"="GET"},
 *     exclusion = @Hateoas\Exclusion(groups={"list","details"})
 * )
 */
class Phone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Groups({"list","details"})
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Groups({"list","details"})
     */
    private $brand;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Expose()
     * @Groups({"list","details"})
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Groups({"details"})
     */
    private $stock;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose()
     * @Groups({"details"}).
     */
    private $description;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     * @Serializer\Expose()
     */
    private $OS;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     * @Serializer\Expose()
     */
    private $storage;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     * @Serializer\Expose()
     */
    private $sim;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     * @Serializer\Expose()
     */
    private $network;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     * @Serializer\Expose()
     */
    private $wifi;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     * @Serializer\Expose()
     */
    private $rear_camera_resolution;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     * @Serializer\Expose()
     */
    private $front_camera_resolution;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     * @Serializer\Expose()
     */
    private $display_resolution;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     * @Serializer\Expose()
     */
    private $dimensions;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("details")
     * @Serializer\Expose()
     */
    private $weight;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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
}
