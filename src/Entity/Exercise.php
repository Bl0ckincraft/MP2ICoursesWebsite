<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    private ?ExerciseType $exerciseType = null;

    #[ORM\Column(length: 10000)]
    private ?string $statement = null;

    #[ORM\Column]
    private array $hints = [];

    #[ORM\Column]
    private array $solutions = [];

    #[ORM\ManyToOne(inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?Chapter $chapter = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExerciseType(): ?ExerciseType
    {
        return $this->exerciseType;
    }

    public function setExerciseType(?ExerciseType $exerciseType): static
    {
        $this->exerciseType = $exerciseType;

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

    public function getHints(): array
    {
        return $this->hints;
    }

    public function setHints(array $hints): static
    {
        $this->hints = $hints;

        return $this;
    }

    public function getSolutions(): array
    {
        return $this->solutions;
    }

    public function setSolutions(array $solutions): static
    {
        $this->solutions = $solutions;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function __serialize(): array
    {
        return [
            "id" => $this->id,
            "exercise_type" => $this->exerciseType->__serialize(),
            "title" => $this->title,
            "statement" => $this->statement,
            "hints" => $this->hints,
            "solutions" => $this->solutions,
            "number" => $this->number
        ];
    }
}
