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
 *  * @Hateoas\Relation(
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
     * @Groups({"details"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Specification::class, inversedBy="phones")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Expose()
     * @Groups({"details"})
     */
    private $specifications;

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

    public function getSpecifications(): ?specification
    {
        return $this->specifications;
    }

    public function setSpecifications(?specification $specifications): self
    {
        $this->specifications = $specifications;

        return $this;
    }
}
