<?php

namespace App\Service;

use App\Entity\TodoItem;
use App\Service;
use App\Entity\TodoList as TodoList;

class TodoListSvc extends Service
{
    /**
     * @param $id
     * @return object
     */
    public function getList($id)
    {

        /**
         * @var \App\Entity\TodoList $list
         */
        $repository = $this->getEntityManager()->getRepository('App\Entity\TodoList');
        $todoList = $repository->find($id);

        if ($todoList === null) {
            return null;
        }

        return array(
            'id' => $todoList->getId(),
            'name' => $todoList->getName(),
            'items' => $todoList->getItems()->map(function (TodoItem $itm) {
                return [
                    'description' => $itm->getDescription(),
                    'due_date' => $itm->getDueDate(),
                    'completed' => $itm->getIsCompleted()
                ];
            })->toArray(),
        );
    }

    /**
     * @return array|null
     */
    public function getLists()
    {
        $repository = $this->getEntityManager()->getRepository('App\Entity\TodoList');
        $lists = $repository->findAll();

        if (empty($lists)) {
            return null;
        }

        /**
         * @var \App\Entity\TodoList $list
         */
        $data = array();
        foreach ($lists as $list) {
            $data[] = array(
                'id' => $list->getId(),
                'name' => $list->getName(),
                'items' => $list->getItems()
            );
        }

        return $data;
    }

    /**
     * @param $name
     * @return array
     */
    public function createList($name)
    {
        $list = new TodoList();
        $list->setName($name);

        $this->getEntityManager()->persist($list);
        $this->getEntityManager()->flush();

        return array(
            'id' => $list->getId(),
            'name' => $list->getName(),
        );
    }

    /**
     * @param $name
     * @return array|null
     */
    public function updateList($id, $name)
    {
        /**
         * @var \App\Entity\TodoList $list
         */
        $repository = $this->getEntityManager()->getRepository('App\Entity\TodoList');
        $list = $repository->find($id);

        if ($list === null) {
            return null;
        }

        $list->setName($name);

        $this->getEntityManager()->persist($list);
        $this->getEntityManager()->flush();

        return array(
            'id' => $list->getId(),
            'name' => $list->getName()
        );
    }

    public function deleteList($id)
    {
        /**
         * @var \App\Entity\TodoList $list
         */
        $repository = $this->getEntityManager()->getRepository('App\Entity\TodoList');
        $list = $repository->find($id);

        if ($list === null) {
            return false;
        }

        $this->getEntityManager()->remove($list);
        $this->getEntityManager()->flush();

        return true;
    }
}
