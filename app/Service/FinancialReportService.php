<?php

namespace App\Service;

use App\Models\Transaction;
use App\Models\Enum\CategoryType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FinancialReportService
{
    public function monthlySummary(?int $month = null, ?int $year = null)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $user = Auth::user();

        // Get all transactions for the month
        $transactions = Transaction::query()
            ->where('user_id', $user->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        // Calculate totals
        $totalIncome = $transactions
            ->filter(fn($t) => $t->category->type === CategoryType::INCOME)
            ->sum('amount');

        $totalExpense = $transactions
            ->filter(fn($t) => $t->category->type === CategoryType::EXPENSE)
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;
        $status = $balance >= 0 ? 'positive' : 'negative';

        return [
            'summary' => [
                'total_income' => (float) $totalIncome,
                'total_expense' => (float) $totalExpense,
                'balance' => (float) $balance,
                'status' => $status,
                'month' => $month,
                'year' => $year,
            ],
        ];
    }

    public function expensesByCategory(?int $month = null, ?int $year = null)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $user = Auth::user();

        // Get expenses grouped by category
        $expenses = Transaction::query()
            ->with('category')
            ->where('user_id', $user->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()
            ->filter(fn($t) => $t->category->type === CategoryType::EXPENSE)
            ->groupBy('category.name')
            ->map(fn($group) => $group->sum('amount'));

        return [
            'charts' => [
                'expenses_by_category' => [
                    'labels' => array_keys($expenses->toArray()),
                    'values' => array_map(fn($v) => (float) $v, array_values($expenses->toArray())),
                    'colors' => $this->generateChartColors(count($expenses)),
                ],
            ],
        ];
    }

    public function incomeVsExpense(?int $month = null, ?int $year = null)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $user = Auth::user();

        // Get all transactions for the month
        $transactions = Transaction::query()
            ->where('user_id', $user->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $totalIncome = (float) $transactions
            ->filter(fn($t) => $t->category->type === CategoryType::INCOME)
            ->sum('amount');

        $totalExpense = (float) $transactions
            ->filter(fn($t) => $t->category->type === CategoryType::EXPENSE)
            ->sum('amount');

        return [
            'charts' => [
                'income_vs_expense' => [
                    'labels' => ['Receitas', 'Despesas'],
                    'values' => [$totalIncome, $totalExpense],
                    'colors' => ['rgba(34, 197, 94, 0.6)', 'rgba(239, 68, 68, 0.6)'],
                    'borderColors' => ['rgb(34, 197, 94)', 'rgb(239, 68, 68)'],
                ],
            ],
        ];
    }

    public function monthlyTransactions(?int $month = null, ?int $year = null, int $page = 1, int $per_page = 15)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $user = Auth::user();

        // Get paginated transactions for the month
        $transactions = Transaction::query()
            ->with('category')
            ->where('user_id', $user->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);

        return [
            'transactions' => $transactions->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'description' => $t->description,
                'category' => $t->category->name,
                'category_type' => $t->category->type->value,
                'type' => $t->category->type === CategoryType::INCOME ? 'Receita' : 'Despesa',
                'amount' => (float) $t->amount,
                'date' => $t->created_at->format('d/m/Y'),
                'date_iso' => $t->created_at->toIso8601String(),
            ]),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'last_page' => $transactions->lastPage(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
            ],
        ];
    }

    public function completeDashboard(?int $month = null, ?int $year = null, int $page = 1)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $summary = $this->monthlySummary($month, $year);
        $expensesByCategory = $this->expensesByCategory($month, $year);
        $incomeVsExpense = $this->incomeVsExpense($month, $year);
        $transactions = $this->monthlyTransactions($month, $year, $page);

        return array_merge(
            $summary,
            $expensesByCategory,
            $incomeVsExpense,
            $transactions
        );
    }

    private function generateChartColors(int $count): array
    {
        $colors = [
            'rgba(59, 130, 246, 0.6)',    // blue
            'rgba(34, 197, 94, 0.6)',     // green
            'rgba(239, 68, 68, 0.6)',     // red
            'rgba(251, 146, 60, 0.6)',    // orange
            'rgba(168, 85, 247, 0.6)',    // purple
            'rgba(236, 72, 153, 0.6)',    // pink
            'rgba(14, 165, 233, 0.6)',    // sky
            'rgba(249, 115, 22, 0.6)',    // amber
        ];

        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $colors[$i % count($colors)];
        }

        return $result;
    }
}
