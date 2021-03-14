<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("todoItems")
 */
class TodoItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @ORM\Column(type="string")
     */
    protected $description;
    /**
     * @ORM\Column(type="datetime", name="due_date")
     */
    protected $dueDate;
    /**
     * @ORM\Column(type="boolean", name="is_completed")
     */
    protected $isCompleted;
    /**
     * @ORM\ManyToOne(targetEntity="TodoList", inversedBy="todoItem")
     * @ORM\JoinColumn(name="todoList_id", referencedColumnName="id")
     */
    protected $todoList;


    public function __construct($list)
    {
        $this->todoList = $list;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDueDate()
    {
        return $this->dueDate->format('Y-m-d');
    }

    public function setDueDate($dueDate)
    {
        $dd = new DateTime($dueDate);
        $this->dueDate = new DateTime($dd->format('Y-m-d'));
    }

    public function getIsCompleted()
    {
        return $this->isCompleted;
    }

    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;
    }
}
