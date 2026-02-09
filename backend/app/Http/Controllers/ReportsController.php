<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    protected $order;

    public function __construct() {
        $this->order = new Order();
    }

    public function index() {
        if (!Auth::check()) {
            return redirect('/');
        }
        $query = $this->order
            ->leftJoin('customer', 'orders.customer_id', '=', 'customer.id')
            ->select('orders.*', 'customer.name as customer_name', 'customer.email as email', 'customer.phone as phone')
            ->orderBy('orders.id', 'desc');

        // Search functionality
        if (request()->has('search') && !empty(request()->get('search'))) {
            $searchTerm = request()->get('search');
            $query->where(function($q) use ($searchTerm) {
            $q->where('customer.name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('customer.email', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('customer.phone', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('orders.id', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('orders.order_date', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('orders.payment_method', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('orders.status', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Filter by date range
        if (request()->has('start_date') && !empty(request()->get('start_date'))) {
            $startDate = request()->get('start_date');
            $query->where('orders.order_date', '>=', $startDate);
        }
        if (request()->has('end_date') && !empty(request()->get('end_date'))) {
            $endDate = request()->get('end_date');
            $query->where('orders.order_date', '<=', $endDate);
        }

        // Filter by status
        if (request()->has('status') && !empty(request()->get('status'))) {
            $statusFilter = request()->get('status');
            $query->where('orders.status', $statusFilter);
        }

        // Filter by payment status
        if (request()->has('payment_status') && !empty(request()->get('payment_status'))) {
            $paymentStatusFilter = request()->get('payment_status');
            $query->where('orders.payment_status', $paymentStatusFilter);
        }

        // Filter by payment method
        if (request()->has('payment_method') && !empty(request()->get('payment_method'))) {
            $paymentMethodFilter = request()->get('payment_method');
            $query->where('orders.payment_method', $paymentMethodFilter);
        }

        $response['orders'] = $query->get();

        return view('sales-report', $response);
    }
}
