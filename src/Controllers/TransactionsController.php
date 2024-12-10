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
}