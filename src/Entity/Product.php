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

    /**
     * Getter for Id
     * @return integer id current value
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Name
     * @return string name current value
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name
     * 
     * @param string $name name value to set
     * @return string name current value
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for description
     * @return string description current value
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for description
     * 
     * @param string $description description value to set
     * @return string description current value
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for price
     * @return float price current value
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Setter for price
     * 
     * @param float $price price value to set
     * @return float price current value
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
