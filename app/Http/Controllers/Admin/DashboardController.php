<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Property;
use App\Models\SaleContract;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'properties' => Property::count(),
            'customers' => Customer::count(),
            'leads' => Lead::count(),
            'bookings' => Booking::count(),
            'sale_contracts' => SaleContract::count(),
            'invoices' => Invoice::count(),
            'users' => User::count(),
            'branches' => Branch::count(),
            'companies' => Company::count(),
        ];

        $invoicesByStatus = Invoice::query()
            ->selectRaw('status, count(*) as total, coalesce(sum(total_amount),0) as amount, coalesce(sum(paid_amount),0) as paid')
            ->groupBy('status')
            ->get();

        $recentBookings = Booking::with('customer', 'property', 'branch')
            ->latest()->limit(8)->get();

        $recentInvoices = Invoice::with('customer', 'branch')
            ->latest()->limit(8)->get();

        return view('admin.dashboard.index', compact(
            'stats', 'invoicesByStatus', 'recentBookings', 'recentInvoices'
        ));
    }
}
