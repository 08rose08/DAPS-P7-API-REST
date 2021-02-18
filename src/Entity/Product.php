<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
//use JMS\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;

/**
 * Class Product
 * 
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "show_product",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "list_products",
 *          absolute = true
 *      )
 * )
 * 
 * @OA\Schema(
 *     title="Product class",
 *     description="Product class",
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
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
     * 
     * @OA\Property(
     *     description="Name",
     *     title="Name",
     * )
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"showProduct"})
     * 
     * @OA\Property(
     *     description="Description",
     *     title="Description",
     * )
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * 
     * @OA\Property(
     *     description="Price",
     *     title="Price",
     * )
     * @var float
     */
    private $price;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
}
