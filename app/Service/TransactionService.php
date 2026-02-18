<?php

namespace App\Service;

use App\Models\Transaction;

class TransactionService extends Service
{
    protected function modelClass(): string
    {
        return Transaction::class;
    }
}
