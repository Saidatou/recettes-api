<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\HasIdTrait;
use App\Repository\TagRepository;
use App\Entity\Traits\HasNameTrait;
use App\Entity\Traits\HasDescriptionTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    use HasIdTrait;
    use HasNameTrait;
    use HasDescriptionTrait;

    #[ORM\Column]
    private ?bool $menu = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: false)]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $children;

    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'tags')]
    private Collection $recipes;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->recipes = new ArrayCollection();
    }

    public function isMenu(): ?bool
    {
        return $this->menu;
    }

    public function setMenu(bool $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->addTag($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeTag($this);
        }

        return $this;
    }

    

    

    
}
