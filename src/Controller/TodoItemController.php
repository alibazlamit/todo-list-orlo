<?php

namespace App\Controller;

use App\Controller;
use App\Entity\TodoList;
use App\Service\TodoItemSvc;
use App\Service\TodoListSvc;
use Exception;
use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Container\ContainerInterface;


class TodoItemController  extends Controller
{

    private $itemService;
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->setEntityManager();
        $this->init();
    }

    /**
     * Get list service
     */
    public function init()
    {
        $this->setItemService(new TodoItemSvc($this->getEntityManager()));
        $this->setListService(new TodoListSvc($this->getEntityManager()));
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function get(Request $request, Response $response)
    {
        $route = $request->getAttribute('route');
        $listId = $route->getArgument('id');
        $itemId = $route->getArgument('itemId');
        $dueDateFilter = $request->getQueryParam('due_date', $default = null);
        $completedFilter = $request->getQueryParam('completed', $default = null);


        if ($itemId === null) {
            $data = $this->getItemService()->getItems($listId, $dueDateFilter, $completedFilter);
        } else {
            $data = $this->getItemService()->getItem($listId, $itemId);
        }

        if ($data === null) {
            return $response->withJson(['items' => []]);
            return;
        }

        return $response->withJson(['items' => $data]);
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function create(Request $request, Response $response)
    {
        $route = $request->getAttribute('route');
        $list_id = $route->getArgument('id');
        $list = $this->getListService()->getList($list_id);
        if ($list === null) {
            return $response->withStatus(404)->write(sprintf('no list with id %d found', $list_id));
        }
        $body = $request->getParsedBody();
        try {
            foreach ($body['items'] as $item) {
                $data[]  = $this->getItemService()->createItem(
                    $item['description'],
                    $item['due_date'],
                    $item['completed'],
                    $list_id

                );
            }
        } catch (Exception $e) {
            return $response->withStatus(500)->write(sprintf('Error while creating Todo Items', $e->getMessage()));
        }

        return $response->withJson(['item' =>  $data]);
    }


    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function put(Request $request, Response $response)
    {
        $body = $request->getParsedBody();
        $route = $request->getAttribute('route');
        $id = $route->getArgument('itemId');
        $item = $this->getItemService()->getItem($id);
        if ($item === null) {
            return $response->withStatus(404)->write(sprintf('no item with id %d found', $id));
        }
        $body_data['description'] = filter_var($body['description'], FILTER_SANITIZE_STRING);
        $body_data['due_date'] = filter_var($body['due_date'], FILTER_SANITIZE_STRING);
        $body_data['completed'] = filter_var($body['completed'], FILTER_SANITIZE_STRING);
        try {
            $data  = $this->getItemService()->updateItem(
                $id,
                $body_data['description'],
                $body_data['due_date'],
                $body_data['completed']
            );
        } catch (Exception $e) {
            return $response->withStatus(500)->write(sprintf('Error while updating Todo Item', $e->getMessage()));
        }


        if ($data === null) {
            return $response->withJson(['item' => []]);
            return;
        }

        return $response->withJson(['item' => $data]);
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function delete(Request $request, Response $response)
    {
        $route = $request->getAttribute('route');
        $id = $route->getArgument('itemId');
        try {
            $this->getItemService()->deleteItem($id);
        } catch (Exception $e) {
            return $response->withStatus(500)->write(sprintf('Error deleting Todo Item', $e->getMessage()));
        }

        return $response;
    }

    /**
     * @return \App\Service\TodoItemSvc
     */
    public function getItemService()
    {
        return $this->itemService;
    }

    /**
     * @param \App\Service\TodoItemSvc $itemService
     */
    public function setItemService($itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * @return \App\Service\TodoListSvc
     */
    public function getListService()
    {
        return $this->listService;
    }

    /**
     * @param \App\Service\TodoListSvc $listService
     */
    public function setListService($listService)
    {
        $this->listService = $listService;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
