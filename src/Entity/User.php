<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Serializer\ExclusionPolicy("all")
 * 
 * @Hateoas\Relation(
 *    "self",
 *    href = @Hateoas\Route(
 *        "list_users",
 *        absolute = true
 *    ),
 *     exclusion = @Hateoas\Exclusion(groups={"list"})
 * )
 * 
 * @Hateoas\Relation(
 *    "detail",
 *    href = @Hateoas\Route(
 *        "details_user",
 * parameters = {
 *             "id" = "expr(object.getId())"
 *             },
 *        absolute = true
 *    ),
 *     exclusion = @Hateoas\Exclusion(groups={"list","detail"})
 * )
 * 
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min="2", max="100")
     * @Serializer\Expose()
     * @Groups({"list","details"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min="2", max="100")
     * @Serializer\Expose()
     * @Groups({"list","details"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email(message="This field must be an email address")
     * @Serializer\Expose()
     * @Groups({"details"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min="4", max="12")
     * @Serializer\Expose()
     * @Groups({"details"})
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="users")
     * @Assert\NotBlank
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Expose()
     * @Groups({"details"})
     * 
     */
    private $company;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCompany(): ?company
    {
        return $this->company;
    }

    public function setCompany(?company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
