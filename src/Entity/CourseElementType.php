<?php

namespace App\Entity;

use App\Repository\CourseElementTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: CourseElementTypeRepository::class)]
class CourseElementType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $displayName = null;

    /**
     * @var Collection<int, CourseElement>
     */
    #[ORM\OneToMany(targetEntity: CourseElement::class, mappedBy: 'elementType')]
    #[Ignore]
    private Collection $courseElements;

    public function __construct()
    {
        $this->courseElements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return Collection<int, CourseElement>
     */
    public function getCourseElements(): Collection
    {
        return $this->courseElements;
    }

    public function addCourseElement(CourseElement $courseElement): static
    {
        if (!$this->courseElements->contains($courseElement)) {
            $this->courseElements->add($courseElement);
            $courseElement->setElementType($this);
        }

        return $this;
    }

    public function removeCourseElement(CourseElement $courseElement): static
    {
        if ($this->courseElements->removeElement($courseElement)) {
            // set the owning side to null (unless already changed)
            if ($courseElement->getElementType() === $this) {
                $courseElement->setElementType(null);
            }
        }

        return $this;
    }

    public function __serialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "display_name" => $this->displayName,
            "course_elements" => $this->courseElements
        ];
    }
}
