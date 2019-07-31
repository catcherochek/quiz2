<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TasksListRepository")
 */
class TasksList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasksLists")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="tasksList")
     */
    private $task;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotBlank(message= " The name cannot be null ")
     * @Assert\Length(
     *     min = 3,
     *     max = 30,
     *     minMessage="The name must be at least {{ limit }} characters long",
     *     maxMessage="The name cannot be longer than {{ limit }} characters"
     * )
     *
     * @Assert\Type("string")
     */
    private $name;


    /**
     * @ORM\Column(name="descr",type="text")
     */
    private $desc;

    public function __construct()
    {
        $this->task = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTask(): Collection
    {
        return $this->task;
    }

    public function addTask(Task $task): self
    {
        if (!$this->task->contains($task)) {
            $this->task[] = $task;
            $task->setTasksList($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->task->contains($task)) {
            $this->task->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getTasksList() === $this) {
                $task->setTasksList(null);
            }
        }

        return $this;
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

    public function getDesc(): ?string
    {
        return $this->desc;
    }

    public function setDesc(string $content): self
    {
        $this->desc = $content;

        return $this;
    }
}
