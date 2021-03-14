<?php

namespace App\Service;

use App\Service;
use App\Entity\TodoItem as TodoItem;
use DateTime;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class TodoItemSvc extends Service
{
    /**
     * @param $id
     * @return object
     */
    public function getItem($itemId)
    {

        /**
         * @var \App\Entity\TodoItem $list
         */
        $repository = $this->getEntityManager()->getRepository('App\Entity\TodoItem');
        $todoItem = $repository->findOneBy(array('id' => $itemId));
        if ($todoItem === null) {
            return null;
        }

        return array(
            'id' => $todoItem->getId(),
            'description' => $todoItem->getDescription(),
            'dueDate' => $todoItem->getDueDate(),
            'completed' => $todoItem->getIsCompleted()
        );
    }

    /**
     * @return array|null
     */
    public function getItems($list_id, $dueDate, $completed)
    {
        $em = $this->getEntityManager();
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('App\Entity\TodoItem', 'i');
        $rsm->addJoinedEntityFromClassMetadata('App\Entity\TodoList', 'l', 'i', 'todoList', array('id' => 'todoList_id'));

        $rawQuery = "select * from todo.todoItems as itm where itm.todoList_id =?";

        if ($completed != null) {
            $rawQuery = $rawQuery . " and itm.is_completed=?";
        }

        if ($dueDate != null) {
            $rawQuery = $rawQuery . " and itm.due_date =?";
        }
        $query = $em->createNativeQuery($rawQuery, $rsm);
        $query->setParameter(1, $list_id);
        $queryIndex = 1;
        if ($completed != null) {
            $query->setParameter(++$queryIndex, $completed === "true" ? 1 : 0);
        }
        if ($dueDate != null) {
            $query->setParameter(++$queryIndex, $dueDate . " 00:00:00");
        }
        $items = $query->getResult();

        if (empty($items)) {
            return null;
        }

        /**
         * @var \App\Entity\TodoItem $items
         */
        $data = array();
        foreach ($items as $item) {
            $data[] = array(
                'id' => $item->getId(),
                'description' => $item->getDescription(),
                'dueDate' => $item->getDueDate(),
                'completed' => $item->getIsCompleted()
            );
        }

        return $data;
    }

    /**
     * @param $name
     * @return array
     */
    public function createItem($description, $due_date, $completed, $list_id)
    {
        $repository = $this->getEntityManager()->getRepository('App\Entity\TodoList');
        $todoList = $repository->find($list_id);

        $item = new TodoItem($todoList);
        $item->setDescription($description);
        $item->setDueDate($due_date);
        $item->setIsCompleted($completed);

        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();

        return array(
            'id' => $item->getId(),
            'description' => $item->getDescription(),
            'dueDate' => $item->getDueDate(),
            'completed' => $item->getIsCompleted()
        );
    }

    /**
     * @param $name
     * @return array|null
     */
    public function updateItem($id, $description, $due_date, $completed)
    {
        /**
         * @var \App\Entity\TodoItem $item
         */
        $repository = $this->getEntityManager()->getRepository('App\Entity\TodoItem');
        $item = $repository->find($id);

        if ($item === null) {
            return null;
        }


        $item->setDescription($description == null ? $item->getDescription() : $description);
        $item->setDueDate($due_date == null ? $item->getDueDate() : $due_date);
        $item->setIsCompleted($completed == null ? $item->getIsCompleted() : $completed);

        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();

        return array(
            'id' => $item->getId(),
            'description' => $item->getDescription(),
            'dueDate' => $item->getDueDate(),
            'completed' => $item->getIsCompleted()
        );
    }

    public function deleteItem($id)
    {
        /**
         * @var \App\Entity\TodoItem $item
         */
        $repository = $this->getEntityManager()->getRepository('App\Entity\TodoItem');
        $item = $repository->find($id);

        if ($item === null) {
            return false;
        }

        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();

        return true;
    }
}
