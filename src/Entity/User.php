<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Serializer\ExclusionPolicy("all")
 * @UniqueEntity(
 * fields={"email"},
 *     message="Your E-Mail adress has already been registered"
 * )
 *
 * @Hateoas\Relation(
 *      "Self",
 *          href = @Hateoas\Route(
 *          "api_user_details",
 *          parameters = {
 *              "id" = "expr(object.getId())"
 *           },
 *          absolute = true
 *    ),
 *      attributes={"method"="GET"},
 *      exclusion = @Hateoas\Exclusion(groups={"details", "list"})
 * )
 *
 * @Hateoas\Relation(
 *      "Users list",
 *          href = @Hateoas\Route(
 *          "api_user_list",
 *          absolute = true
 *    ),
 *      attributes={"method"="GET"},
 *      exclusion = @Hateoas\Exclusion(groups={"details"})
 * )
 *
 * @Hateoas\Relation(
 *      "Remove user",
 *          href = @Hateoas\Route(
 *          "api_remove_user",
 *          parameters = {
 *              "id" = "expr(object.getId())"
 *           },
 *          absolute = true
 *    ),
 *      attributes={"method"="DELETE"},
 *      exclusion = @Hateoas\Exclusion(groups={"details", "list"})
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Groups({"list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min="2", max="100")
     * @Serializer\Expose()
     * @Groups({"list", "details"})
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
     * @ORM\Column(type="string", length=255, unique=true)
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
    private $phone_number;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
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

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

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
