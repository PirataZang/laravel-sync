<?php

namespace App\Service;

use App\Models\Transaction;
use App\Models\Enum\CategoryType;

class DashboardService extends Service
{
    protected function modelClass(): string
    {
        return Transaction::class;
    }

    public function index($request = [])
    {
        $query = Transaction::with('category');

        if (!empty($request['start_date']) && !empty($request['end_date'])) {
            $query->whereBetween('created_at', [$request['start_date'] . ' 00:00:00', $request['end_date'] . ' 23:59:59']);
        }

        $transactions = $query->get();

        $incomesByCategory = [];
        $expensesByCategory = [];
        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($transactions as $transaction) {
            $category = $transaction->category;
            $type = $category ? $category->type->value : null;

            $amount = (float) $transaction->amount;

            if ($type === CategoryType::INCOME->value) {
                $totalIncome += $amount;
                $catName = $category ? $category->name : 'Sem Categoria';
                if (!isset($incomesByCategory[$catName])) {
                    $incomesByCategory[$catName] = 0;
                }
                $incomesByCategory[$catName] += $amount;
            } elseif ($type === CategoryType::EXPENSE->value) {
                $totalExpense += $amount;
                $catName = $category ? $category->name : 'Sem Categoria';
                if (!isset($expensesByCategory[$catName])) {
                    $expensesByCategory[$catName] = 0;
                }
                $expensesByCategory[$catName] += $amount;
            }
        }

        $incomeColors = $this->generateColors(empty($incomesByCategory) ? 1 : count($incomesByCategory), 'income');
        $expenseColors = $this->generateColors(empty($expensesByCategory) ? 1 : count($expensesByCategory), 'expense');

        $data = [
            'totals' => [
                'income' => $totalIncome,
                'expense' => $totalExpense,
                'balance' => $totalIncome - $totalExpense,
            ],
            'charts' => [
                'incomes_by_category' => [
                    'labels' => empty($incomesByCategory) ? ['Sem Dados'] : array_keys($incomesByCategory),
                    'data' => empty($incomesByCategory) ? [1] : array_values($incomesByCategory),
                    'backgroundColor' => $incomeColors
                ],
                'expenses_by_category' => [
                    'labels' => empty($expensesByCategory) ? ['Sem Dados'] : array_keys($expensesByCategory),
                    'data' => empty($expensesByCategory) ? [1] : array_values($expensesByCategory),
                    'backgroundColor' => $expenseColors
                ],
                'income_vs_expense' => [
                    'labels' => ($totalIncome == 0 && $totalExpense == 0) ? ['Sem Dados'] : ['Receitas', 'Despesas'],
                    'data' => ($totalIncome == 0 && $totalExpense == 0) ? [1, 1] : [$totalIncome, $totalExpense],
                    'backgroundColor' => ['#28a745', '#dc3545'],
                ]
            ]
        ];

        return $this->success($data, 'Dashboard data retrieved successfully');
    }

    private function generateColors($count, $type)
    {
        $colors = [];
        $baseHue = $type === 'income' ? 120 : 0; // Green for income, Red for expense
        for ($i = 0; $i < $count; $i++) {
            $lightness = 40 + (($i * 15) % 40); // Vary lightness between 40% and 80%
            $colors[] = "hsl({$baseHue}, 70%, {$lightness}%)";
        }
        return $colors;
    }
}
