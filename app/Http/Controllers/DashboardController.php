<?php

namespace App\Http\Controllers;

use App\Models\Companie;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Financing;
use App\Models\FinancingType;
use App\Models\Offer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
 public function index(Request $request)
    {
        $type = $request->input('type', 'month');
        $date = $request->input('date', now());
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $financingTypeId = $request->input('financing_type_id');

        [$filterStartDate, $filterEndDate] = $this->getDateRange($type, $date, $year, $month, $startDate, $endDate);

        $financingTypes = FinancingType::where('is_active', true)->get();

        $totalCommission = $this->calculateTotalCommission($filterStartDate, $filterEndDate, $financingTypeId);

        $financingsQuery = Financing::whereBetween('created_at', [$filterStartDate, $filterEndDate]);
        if ($financingTypeId) {
            $financingsQuery->where('financing_type_id', $financingTypeId);
        }
        $totalFinancings = $financingsQuery->count();
        $totalSales = $financingsQuery->sum('price');

        $companiesQuery = Company::where('is_active', true);
        if ($financingTypeId) {
            $companiesQuery->where('financing_type_id', $financingTypeId);
        }
        $activeCompanies = $companiesQuery->count();

        // الكومشن لكل شركة
        $companiesCommission = $this->calculateAllCommissions($filterStartDate, $filterEndDate, $financingTypeId);

        // آخر التمويلات
        $recentFinancingsQuery = Financing::with(['financingType'])
            ->whereBetween('created_at', [$filterStartDate, $filterEndDate]);

        if ($financingTypeId) {
            $recentFinancingsQuery->where('financing_type_id', $financingTypeId);
        }

        $recentFinancings = $recentFinancingsQuery->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // أفضل الشركات
        $topCompanies = collect($companiesCommission)
            ->sortByDesc('total_commission')
            ->take(5)
            ->values();


        return view('dashboard.index', compact(
            'totalCommission',
            'totalFinancings',
            'totalSales',
            'activeCompanies',
            'companiesCommission',
            'recentFinancings',
            'topCompanies',
            'type',
            'filterStartDate',
            'filterEndDate',
            'financingTypes',
            'financingTypeId'
        ));
    }

    private function getDateRange($type, $date, $year, $month, $startDate, $endDate)
    {
        switch ($type) {
            case 'day':
                $start = Carbon::parse($date)->startOfDay();
                $end = Carbon::parse($date)->endOfDay();
                break;

            case 'week':
                $parsedDate = Carbon::parse($date);
                $start = $parsedDate->copy()->startOfWeek();
                $end = $parsedDate->copy()->endOfWeek();
                break;

            case 'month':
                $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                $end = Carbon::createFromDate($year, $month, 1)->endOfMonth();
                break;

            case 'year':
                $start = Carbon::createFromDate($year, 1, 1)->startOfYear();
                $end = Carbon::createFromDate($year, 12, 31)->endOfYear();
                break;

            case 'custom':
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
                break;

            default:
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
        }

        return [$start, $end];
    }

    private function calculateTotalCommission($startDate, $endDate, $financingTypeId = null)
    {
        $companiesQuery = Company::where('is_active', true);

        if ($financingTypeId) {
            $companiesQuery->where('financing_type_id', $financingTypeId);
        }

        $companies = $companiesQuery->get();
        $total = 0;

        foreach ($companies as $company) {
            // تأكد من وجود user_id
            if (!$company->user_id) {
                continue;
            }

            $financings = Financing::where('company_id', $company->user_id)
                ->where('financing_type_id', $company->financing_type_id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            \Log::info("Company: {$company->name}, user_id: {$company->user_id}, financing_type: {$company->financing_type_id}, financings count: " . $financings->count());

            foreach ($financings as $financing) {
                if ($company->contract_type === 'percentage') {
                    $total += ($financing->price * $company->contract_value) / 100;
                } else {
                    $total += $company->contract_value;
                }
            }
        }

        return $total;
    }

    private function calculateAllCommissions($startDate, $endDate, $financingTypeId = null)
    {
        $companiesQuery = Company::where('is_active', true)
            ->with('financingType')
            ->where('contract_value', '!=', null);

        if ($financingTypeId) {
            $companiesQuery->where('financing_type_id', $financingTypeId);
        }

        $companies = $companiesQuery->get();
        $results = [];

        foreach ($companies as $company) {
            // تأكد من وجود user_id
            if (!$company->user_id) {
                continue;
            }

            $commissionData = $this->calculateCompanyCommission($company, $startDate, $endDate);

            $results[] = $commissionData;
        }

        return $results;
    }

  private function calculateCompanyCommission(Company $company, $startDate, $endDate)
{
    $financings = Financing::where('company_id', $company->user_id)
        ->where('financing_type_id', $company->financing_type_id)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();




    $totalCommission = 0;

    foreach ($financings as $financing) {
        if ($company->contract_type === 'percentage') {
            $commission = ($financing->price * $company->contract_value) / 100;
        } else {
            $commission = $company->contract_value;
        }
        $totalCommission += $commission;
    }

    return [
        'company_id' => $company->id,
        'company_name' => $company->name,
        'user_id' => $company->user_id,
        'financing_type' => $company->financingType->name ?? 'N/A',
        'financing_type_id' => $company->financing_type_id,
        'contract_type' => $company->contract_type,
        'contract_value' => $company->contract_value,
        'total_financings' => $financings->count(),
        'total_sales' => $financings->sum('price'),
        'total_commission' => $totalCommission,
    ];
}
}
