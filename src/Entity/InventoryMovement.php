<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InventoryMovementRepository;

#[ORM\Entity(repositoryClass: InventoryMovementRepository::class)]
class InventoryMovement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: "inventoryMovements")]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "id", nullable: false)]
    public ?Product $product = null;

    #[ORM\Column(type: "integer")]
    public int $quantityChange;

    #[ORM\Column(type: "string", length: 255)]
    public string $reason;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    // Getters y setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantityChange(): int
    {
        return $this->quantityChange;
    }

    public function setQuantityChange(int $quantityChange): self
    {
        $this->quantityChange = $quantityChange;

        return $this;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function toArray()
    {
        $stocktaking = get_object_vars($this);
        $stocktaking['product'] = $this->product->getName();
        return $stocktaking;
    }
}
