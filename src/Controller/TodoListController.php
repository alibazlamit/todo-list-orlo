<?php

namespace App\Controller;

use App\Controller;
use App\Entity\TodoList;
use App\Service\TodoListSvc;
use Exception;
use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Container\ContainerInterface;


class TodoListController  extends Controller
{

    private $listService;
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
        $this->setListService(new TodoListSvc($this->getEntityManager()));
    }

    /**
     * @param null $id
     */
    public function get(Request $request, Response $response)
    {
        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');
        if ($id === null) {
            $data = $this->getListService()->getLists();
        } else {
            $data = $this->getListService()->getList($id);
        }

        if ($data === null) {
            return $response->withJson(['list' => []]);
            return;
        }
        return $response->withJson(['list' => $data]);
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function create(Request $request, Response $response)
    {
        $body = $request->getParsedBody();
        $body_data = [];
        $body_data['name'] = filter_var($body['name'], FILTER_SANITIZE_STRING);
        try {
            $data  = $this->getListService()->createList($body_data['name']);
        } catch (Exception $e) {
            return $response->withStatus(500)->write(sprintf('Error creating  Todo List', $e->getMessage()));
        }

        return $response->withJson(['list' => $data]);
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
        $id = $route->getArgument('id');
        $body_data['name'] = filter_var($body['name'], FILTER_SANITIZE_STRING);

        try {
            $data  = $this->getListService()->updateList($id, $body_data['name']);
        } catch (Exception $e) {
            return $response->withStatus(500)->write(sprintf('Error updating Todo List', $e->getMessage()));
        }

        return $response->withJson(['list' => $data]);
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
        $id = $route->getArgument('id');
        try {
            $this->getListService()->deleteList($id);
        } catch (Exception $e) {
            return $response->withStatus(500)->write(sprintf('Error deleting Todo List', $e->getMessage()));
        }

        return $response;
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
