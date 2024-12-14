<?php

namespace App\Controllers;

use App\Models\CategoriesModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoriesController
{
    private $categoriesModel;

    public function __construct(CategoriesModel $categoriesModel)
    {
        $this->categoriesModel = $categoriesModel;
    }

    public function getAllCategories(Request $request, Response $response): Response
    {
        try {
            $categories = $this->categoriesModel->getAllCategories();
            $response->getBody()->write(json_encode($categories));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $error = ['error' => 'Erro ao buscar categorias: ' . $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function getCategoryById(Request $request, Response $response): Response
    {
        $category_id = (int) $request->getQueryParams()['category_id'];
        
        if (!$category_id) {
            $error = ['error' => 'O parâmetro category_id é obrigatório.'];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $category = $this->categoriesModel->getCategoryById($category_id);
            $response->getBody()->write(json_encode($category));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (PDOException $e) {
            $error = ['error' => 'Erro ao buscar categoria: ' . $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function createCategory(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $title = $body['title'];

        if (!$title) {
            $error = ['error' => 'O parâmetro title é obrigatório.'];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $createdCategory = $this->categoriesModel->createCategory($title);

            $success = ['message' => 'Categoria criada com sucesso!'];
            $response->getBody()->write(json_encode($success));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (PDOException $e) {
            $error = ['error' => 'Erro ao criar categoria: ' . $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}