<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\Groups;
//use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *  fields = {"email"},
 *  message = "This email already exists"
 * )
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "show_user",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "delete_user",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "list_users",
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "Customer",
 *      embedded = @Hateoas\Embedded("expr(object.getCustomer())")
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"usersList"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"usersList"})
     * @Assert\NotBlank(message="The name is required")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"usersList"})
     * @Assert\NotBlank(message="The email is required")
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;


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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
