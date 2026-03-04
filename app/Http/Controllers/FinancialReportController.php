<?php

namespace App\Http\Controllers;

use App\Service\FinancialReportService;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    protected FinancialReportService $service;

    public function __construct(FinancialReportService $service)
    {
        $this->service = $service;
    }

    public function monthlySummary(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        return $this->service->monthlySummary($month, $year);
    }

    public function expensesByCategory(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        return $this->service->expensesByCategory($month, $year);
    }

    public function incomeVsExpense(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        return $this->service->incomeVsExpense($month, $year);
    }

    public function monthlyTransactions(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        $page = $request->query('page', 1);
        $per_page = $request->query('per_page', 15);

        return $this->service->monthlyTransactions($month, $year, $page, $per_page);
    }

    public function completeDashboard(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        $page = $request->query('page', 1);

        return $this->service->completeDashboard($month, $year, $page);
    }
}
