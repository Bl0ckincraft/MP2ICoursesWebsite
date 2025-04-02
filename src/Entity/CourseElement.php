<?php

namespace App\Entity;

use App\Repository\CourseElementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: CourseElementRepository::class)]
class CourseElement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'courseElements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CourseElementType $elementType = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(length: 10000)]
    private ?string $statement = null;

    #[ORM\Column]
    private array $details = [];

    #[ORM\Column]
    private array $proofs = [];

    #[ORM\ManyToOne(inversedBy: 'elements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?Chapter $chapter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getElementType(): ?CourseElementType
    {
        return $this->elementType;
    }

    public function setElementType(?CourseElementType $elementType): static
    {
        $this->elementType = $elementType;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getStatement(): ?string
    {
        return $this->statement;
    }

    public function setStatement(string $statement): static
    {
        $this->statement = $statement;

        return $this;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDetails(array $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getProofs(): array
    {
        return $this->proofs;
    }

    public function setProofs(array $proofs): static
    {
        $this->proofs = $proofs;

        return $this;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): static
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function __serialize(): array
    {
        return [
            "id" => $this->id,
            "element_type" => $this->elementType,
            "number" => $this->number,
            "statement" => $this->statement,
            "details" => $this->details,
            "proofs" => $this->proofs
        ];
    }
}
