<?php

namespace App\Controllers;

use App\Models\TransactionsModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TransactionsController
{
    private $transactionsModel;

    public function __construct(TransactionsModel $transactionsModel)
    {
        $this->transactionsModel = $transactionsModel;
    }

    public function getAll(Request $request, Response $response): Response
    {
        try {
            $transactions = $this->transactionsModel->getAllTransactions();
            $response->getBody()->write(json_encode($transactions));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $error = ['error' => 'Erro ao buscar transações: ' . $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function getTransactionsByCategory(Request $request, Response $response, array $args): Response
    {
        $categoryId = (int) $request->getQueryParams()['categoryId'];
        $transactions = $this->transactionsModel->getTransactionsByCategory((int)$categoryId);
        
        if (!$transactions) {
            $response->getBody()->write(json_encode(['error' => 'Falha ao buscar as transações.']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $response->getBody()->write(json_encode($transactions));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    
    public function getTransactionsBySubcategory(Request $request, Response $response, array $args): Response
    {
        $subcategoryId = (int) $request->getQueryParams()['subcategoryId'];
        $transactions = $this->transactionsModel->getTransactionsBySubcategory((int)$subcategoryId);
        
        if (!$transactions) {
            $response->getBody()->write(json_encode(['error' => 'Falha ao buscar as transações.']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $response->getBody()->write(json_encode($transactions));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    
    public function getTransactionsByType(Request $request, Response $response, array $args): Response
    {
        $type = (string) $request->getQueryParams()['type'];
        $transactions = $this->transactionsModel->getTransactionsByType((string)$type);
        
        if (!$transactions) {
            $response->getBody()->write(json_encode(['error' => 'Falha ao buscar as transações.']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $response->getBody()->write(json_encode($transactions));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    
    public function getTransactionsByDate(Request $request, Response $response, array $args): Response
    {
        $date = (string) $request->getQueryParams()['date'];
        $transactions = $this->transactionsModel->getTransactionsByDate((string)$date);
        
        if (!$transactions) {
            $response->getBody()->write(json_encode(['error' => 'Falha ao buscar as transações.']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $response->getBody()->write(json_encode($transactions));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function createTransaction(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $userId = $body['userId'];
        $categoryId = $body['categoryId'];
        $subcategoryId = $body['subcategoryId'];
        $title = $body['title'];
        $value = $body['value'];
        $type = $body['type'];
        $date = $body['date'];

        if (!$userId || !$categoryId || !$subcategoryId || !$title || !$value || !$type || !$date) {
            $response->getBody()->write(json_encode(['error' => 'Algum campo obrigatório não está preenchido.']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $transaction = $this->transactionsModel->createTransaction($userId, $categoryId, $subcategoryId, $title, $value, $type, $date);

            $success = ['message' => 'Transação criada com sucesso!'];
            $response->getBody()->write(json_encode($success));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao criar a transação.' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    public function updateTransaction(Request $request, Response $response, array $args): Response
    {
        $body = $request->getParsedBody();
        $transactionId = $body['transaction_id'] ?? null;

        if (!$transactionId) {
            $error = ['error' => 'O ID da transação é obrigatório.'];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $fields = [];

        if (isset($body['category_id'])) {
            $fields['category_id'] = $body['category_id'];
        }
        if (isset($body['subcategory_id'])) {
            $fields['subcategory_id'] = $body['subcategory_id'];
        }
        if (isset($body['title'])) {
            $fields['title'] = $body['title'];
        }
        if (isset($body['value'])) {
            $fields['value'] = $body['value'];
        }
        if (isset($body['type'])) {
            $fields['type'] = $body['type'];
        }
        if (isset($body['date'])) {
            $fields['date'] = $body['date'];
        }

        if (empty($fields)) {
            $error = ['error' => 'Nenhum campo para atualizar foi fornecido.'];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $updated = $this->transactionsModel->updateTransaction($fields, $transactionId);

            if ($updated) {
                $success = ['message' => 'Transação atualizada com sucesso!'];
                $response->getBody()->write(json_encode($success));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                $error = ['error' => 'Erro ao atualizar a transação.'];
                $response->getBody()->write(json_encode($error));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }
        } catch (\Exception $e) {
            $error = ['error' => 'Erro ao atualizar a transação: ' . $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

    }

    public function deleteTransaction(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $transactionId = $body['transaction_id'];

        if (!$transactionId) {
            $error = ['error' => 'O ID da transação é obrigatório.'];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        try {
            $deleteTransaction = $this->transactionsModel->deleteTransaction($transactionId);

            $success = ['message' => 'Transação deletada com sucesso!'];
            $response->getBody()->write(json_encode($success));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (PDOException $e) {
            $error = ['error' => 'Erro ao deletar a transação.' . $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

    }
}