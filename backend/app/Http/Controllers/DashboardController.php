<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderedItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $orders;
    protected $orderItems;

    public function __construct()
    {
        $this->orders = new Order();
        $this->orderItems = new OrderedItems();
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $selectedFilter = request()->get('filter', 'today');
        $fromDate = $today;
        $toDate = $today;
        $prevFromDate = $yesterday;
        $prevToDate = $yesterday;
        $response['selectedFilter'] = $selectedFilter;
        
        if ($selectedFilter === 'today') {
            $fromDate = $today;
            $toDate = $today;
        } elseif ($selectedFilter === 'this-week') {
            $fromDate = now()->subDays(6)->toDateString();
            $toDate = $today;
            $prevFromDate = now()->subDays(13)->toDateString();
            $prevToDate = now()->subDays(7)->toDateString();
        } elseif ($selectedFilter === 'this-month') {
            $fromDate = now()->startOfMonth()->toDateString();
            $toDate = now()->endOfMonth()->toDateString();
            $prevFromDate = now()->subMonth()->startOfMonth()->toDateString();
            $prevToDate = now()->subMonth()->endOfMonth()->toDateString();
        } elseif ($selectedFilter === 'this-year') {
            $fromDate = now()->startOfYear()->toDateString();
            $toDate = now()->endOfYear()->toDateString();
            $prevFromDate = now()->subYear()->startOfYear()->toDateString();
            $prevToDate = now()->subYear()->endOfYear()->toDateString();
        }
        
        $response['dates'] = [
            'from' => $fromDate,
            'to' => $toDate,
            'prevFrom' => $prevFromDate,
            'prevTo' => $prevToDate
        ];

        // Period revenue and % change from previous period
        $response["todayRevenue"] = $this->orders->whereBetween('order_date', [$fromDate, $toDate])->sum('total_amount');
        $yesterdayRevenue = $this->orders->whereBetween('order_date', [$prevFromDate, $prevToDate])->sum('total_amount');
        $response["yesterdayRevenue"] = $yesterdayRevenue;
        $response["revenuePercentageChange"] = $yesterdayRevenue > 0 ? (($response["todayRevenue"] - $yesterdayRevenue) / $yesterdayRevenue) * 100 : 100;
        $percentage = number_format(abs($response["revenuePercentageChange"]), 2);
        if ($response["revenuePercentageChange"] > 0) {
            $class = "text-success";
            $changeText = "increase";
        } elseif ($response["revenuePercentageChange"] < 0) {
            $class = "text-danger";
            $changeText = "decrease";
        } else {
            $class = "text-muted";
            $changeText = "no change";
        }
        $response["revenueChangeDisplay"] = "<span class=\"$class small pt-1 fw-bold\" id=\"revenueChange\">{$percentage}%</span> <span class=\"text-muted small pt-2 ps-1\">$changeText</span>";

        // Total orders and % change from previous period
        $response["totalOrders"] = $this->orders->whereBetween('order_date', [$fromDate, $toDate])->count();
        $response["totalOrdersDisplay"] = $response["totalOrders"] == 1 ? $response["totalOrders"] . " Order" : $response["totalOrders"] . " Orders";
        $yesterdayOrders = $this->orders->whereBetween('order_date', [$prevFromDate, $prevToDate])->count();
        $response["yesterdayOrders"] = $yesterdayOrders;
        $response["ordersPercentageChange"] = $yesterdayOrders > 0 ? (($response["totalOrders"] - $yesterdayOrders) / $yesterdayOrders) * 100 : 100;
        $percentage = number_format(abs($response["ordersPercentageChange"]), 2);
        if ($response["ordersPercentageChange"] > 0) {
            $class = "text-success";
            $changeText = "increase";
        } elseif ($response["ordersPercentageChange"] < 0) {
            $class = "text-danger";
            $changeText = "decrease";
        } else {
            $class = "text-muted";
            $changeText = "no change";
        }
        $response["ordersChangeDisplay"] = "<span class=\"$class small pt-1 fw-bold\" id=\"ordersChange\">{$percentage}%</span> <span class=\"text-muted small pt-2 ps-1\">$changeText</span>";

        // Pending orders count and value
        $response["pendingOrdersValue"] = $this->orders->where('status', 'pending')->whereBetween('order_date', [$fromDate, $toDate])->sum('total_amount');
        $response["pendingOrders"] = $this->orders->where('status', 'pending')->whereBetween('order_date', [$fromDate, $toDate])->count();

        // Pending bank transfers count and value
        $response["pendingBankTransfersValue"] = $this->orders->where('payment_status', 'pending')->where('payment_method', 'Bank Transfer')->whereBetween('order_date', [$fromDate, $toDate])->sum('total_amount');
        $response["pendingBankTransfers"] = $this->orders->where('payment_status', 'pending')->where('payment_method', 'Bank Transfer')->whereBetween('order_date', [$fromDate, $toDate])->count();

        // get one week summary of revenue, orders, bank transfers, cod
        $startDate = \Carbon\Carbon::parse($fromDate);
        $endDate = \Carbon\Carbon::parse($toDate);
        $response["salesChart"] = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $currentDate = $date->toDateString();
            $response["salesChart"][] = [
            "date" => $currentDate,
            "revenue" => $this->orders->where('order_date', $currentDate)->sum('total_amount'),
            "orders" => $this->orders->where('order_date', $currentDate)->count(),
            "bankTransfers" => $this->orders->where('payment_method', 'Bank Transfer')->where('order_date', $currentDate)->sum('total_amount'),
            "cod" => $this->orders->where('payment_method', 'Cash On Delivery')->where('order_date', $currentDate)->sum('total_amount'),
            ];
        }

        // Recent sales
        $response['recentSales'] = $this->orderItems->leftJoin('orders', 'ordered_items.order_id', '=', 'orders.id')
            ->leftJoin('customer', 'orders.customer_id', '=', 'customer.id')
            ->leftJoin('items', 'ordered_items.product_id', '=', 'items.item_id')
            ->whereBetween('orders.order_date', [$fromDate, $toDate])
            ->select('ordered_items.*', 'customer.name as customer_name', 'orders.status as status', 'items.name as item_name')
            ->orderBy('ordered_items.id', 'desc')->limit(10)->get();

        // Top selling products
        $response['topSelling'] = $this->orderItems->leftJoin('orders', 'ordered_items.order_id', '=', 'orders.id')
            ->leftJoin('items', 'ordered_items.product_id', '=', 'items.item_id')
            ->leftJoin('item_category as category', 'items.category', '=', 'category.id')
            ->whereBetween('orders.order_date', [$fromDate, $toDate])
            ->select('items.name as item_name', 'items.main_image', 'category.cat_name as category_name', DB::raw('SUM(ordered_items.quantity) as total_quantity'), DB::raw('SUM(ordered_items.quantity * ordered_items.price) as total_revenue'), 'items.quantity as stock_quantity')
            ->groupBy('ordered_items.product_id', 'items.name', 'items.main_image', 'category.cat_name', 'items.quantity')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', $response);
    }
}
?>