<?php

namespace App\Entity;

use OpenApi\Annotations as OA;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use JMS\Serializer\Annotation\Groups;
//use Symfony\Component\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Class User
 * 
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * 
 * @UniqueEntity(
 *      fields = {"email"},
 *      message = "This email already exists"
 * )
 * 
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
 * 
 * @OA\Schema(
 *     title="User class",
 *     description="User class",
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Exclude
     * @OA\Property(
     *     description="Id",
     *     title="Id",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"usersList", "createUser"})
     * @Assert\NotBlank(message="The name is required")
     * 
     * @OA\Property(
     *     description="Name",
     *     title="Name",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"usersList", "createUser"})
     * @Assert\NotBlank(message="The email is required")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * 
     * @OA\Property(
     *     format="email",
     *     description="Email",
     *     title="Email",
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @OA\Property(
     *     description="Customer relation",
     *     title="Customer",
     * )
     *
     * @var Customer Class Customer
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
