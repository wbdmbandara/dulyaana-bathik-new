<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Items;
use App\Models\Order;
use App\Models\OrderedItems;
use App\Models\OrderShipping;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Services\MailConfigService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpParser\JsonDecoder;

class OrdersController extends Controller
{
    protected $items;
    protected $order;
    protected $orderedItems;
    protected $orderShipping;
    protected $orderStatus;
    protected $paymentStatus;
    protected $cart;
    protected $customer;
    protected $customerAddresses;

    public function __construct(Items $items, Order $order, OrderedItems $orderedItems, OrderShipping $orderShipping, OrderStatus $orderStatus, PaymentStatus $paymentStatus, Cart $cart, Customer $customer, CustomerAddress $customerAddresses)
    {
        $this->items = $items;
        $this->order = $order;
        $this->orderedItems = $orderedItems;
        $this->orderShipping = $orderShipping;
        $this->orderStatus = $orderStatus;
        $this->paymentStatus = $paymentStatus;
        $this->cart = $cart;
        $this->customer = $customer;
        $this->customerAddresses = $customerAddresses;
    }

    // view orders in admin dashboard
    public function index()
    {
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

        $response['orders'] = $query->paginate(10);
        return view('orders', $response);
    }

    public function placeOrder(Request $request)
    {
        try{
            $customer = json_decode($request->customer, true);
            $totals = json_decode($request->totals, true);
            $payment = json_decode($request->payment, true);
            $orderedItemsData = json_decode($request->items, true);
            $customerID = $orderedItemsData[0]['customer_id'];
            $shipping = json_decode($request->shipping, true);
            $saveAddress = json_decode($request->saveAddress, true);
            $isDefaultAddress = json_decode($request->isDefaultAddress, true);
            $trackingNumber = $this->generateTrackingNumber();

            DB::beginTransaction();

            try {
                // Create new order
                $order = Order::create([
                    'customer_id' => $customerID,
                    'order_date' => date('Y-m-d'),
                    'order_time' => date('H:i:s'),
                    'items_count' => count($orderedItemsData),
                    'total_quantity' => $totals['total_quantity'],
                    'total_amount' => $totals['subtotal'],
                    'shipping_fee' => $totals['shipping'],
                    'discount' => 0, // optional discount
                    'final_amount' => $totals['total'],
                    'payment_method' => $payment['displayName'],
                    'note' => $request->json('note') ?? '', // optional note
                    'status' => 'pending',
                ]);

                // get order id
                $orderId = $order->id;

                // Handle payment slip upload
                if ($request->hasFile('paymentSlip')) {
                    $file = $request->file('paymentSlip');
                    $filename = 'order_' . $order->id . '_payment_slip.' . $file->getClientOriginalExtension();

                    $file->move(public_path('payment_slips'), $filename);

                    $order->update([
                        'payment_slip' => $filename
                    ]);
                }

                // Insert ordered items
                foreach ($orderedItemsData as $item) {
                    $item['price'] = $item['item_price'];
                    if($item['discount_price'] > 0) {
                    $item['price'] = $item['discount_price'];
                    }
                    $item['value'] = $item['price'] * $item['quantity'];
                    $this->orderedItems->create([
                    'order_id' => $orderId,
                    'product_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'value' => $item['value'],
                    ]);

                    // Decrease stock quantity and update sold_qty, find by item_id
                    $product = $this->items->where('item_id', $item['item_id'])->first();
                    if ($product) {
                    $this->items->where('item_id', $item['item_id'])
                        ->update([
                        'quantity' => $product->quantity - $item['quantity'],
                        'sold_qty' => $product->sold_qty + $item['quantity'],
                        ]);
                    }
                }
                
                // Insert order shipping details
                $this->orderShipping->create([
                    'order_id' => $orderId,
                    'tracking_number' => $trackingNumber,
                    'full_name' => $shipping['fullName'],
                    'phone_number' => $shipping['phoneNumber'],
                    'customer_id' => $customerID,
                    'address_line1' => $shipping['addressLine1'],
                    'address_line2' => $shipping['addressLine2'] ?? '',
                    'type' => 'shipping',
                    'city' => $shipping['city'],
                    'state' => $shipping['state'],
                    'postal_code' => $shipping['postalCode'],
                    'email' => $customer['email'],
                ]);

                // Insert initial order status
                $this->orderStatus->create([
                    'order_id' => $orderId,
                    'status' => 'pending',
                ]);

                // Insert initial payment status
                $this->paymentStatus->create([
                    'order_id' => $orderId,
                    'payment_status' => 'pending',
                ]);

                // Clear Cart
                $this->cart->where('customer_id', $customerID)->delete();

                // Save address if required
                if ($saveAddress == true) {
                    if($isDefaultAddress == true) {
                        // Set all other addresses to not default
                        $this->customerAddresses->where('customer_id', $customerID)->update(['is_default' => 0]);
                        $isDefaultAddress = 1;
                    }else{
                        $isDefaultAddress = 0;
                    }
                    $this->customerAddresses->create([
                        'customer_id' => $customerID,
                        'full_name' => $shipping['fullName'],
                        'phone_number' => $shipping['phoneNumber'],
                        'address_line1' => $shipping['addressLine1'],
                        'address_line2' => $shipping['addressLine2'] ?? '',
                        'city' => $shipping['city'],
                        'state' => $shipping['state'],
                        'postal_code' => $shipping['postalCode'],
                        'is_default' => $isDefaultAddress,
                        'type' => 'shipping',
                        'label' => $shipping['addressLabel'] ?? 'Home',
                    ]);
                }

                // Update customer info
                $this->customer->where('id', $customerID)->update([
                    'name' => $customer['name'],
                    'phone' => $customer['phone'],
                ]);

                DB::commit();

                // Send Email
                $this->sendCustomerEmail($orderId);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Your order has been placed successfully! Thank you for your purchase.',
                    'description' => 'We are processing your order and will update you with the shipping details soon.',
                    'order_id' => $orderId,
                    'tracking_number' => $trackingNumber
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
            'status' => 'error',
            'message' => 'Error placing order',
            'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generateTrackingNumber()
    {
        // return 'D-' . str_pad($orderId, 8, '0', STR_PAD_LEFT);
        // Generate a unique tracking number for the order using hash or any other logic
        return strtoupper(uniqid('DBTRK-'));
    }

    public function orderDetails()
    {
        $orderID = request()->route('id');
        $order = $this->order->find($orderID);
        $orderedItems = $this->orderedItems
            ->where('order_id', $orderID)
            ->join('items', 'ordered_items.product_id', '=', 'items.item_id')
            ->select('ordered_items.*', 'items.name', 'items.url', 'items.main_image')
            ->get();
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'order_data' => $order,
            'ordered_items' => $orderedItems,
        ]);
    }

    public function orderConfirmation()
    {
        $orderID = request()->route('id');
        $order = $this->order->find($orderID);
        $orderedItems = $this->orderedItems
            ->where('order_id', $orderID)
            ->join('items', 'ordered_items.product_id', '=', 'items.item_id')
            ->select('ordered_items.*', 'items.name', 'items.url', 'items.main_image')
            ->get();
        
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'order_data' => $order,
            'ordered_items' => $orderedItems,
        ]);
    }

    public function sendCustomerEmail($orderID)
    {
        $order = $this->order->find($orderID);
        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        $customer = $this->customer->find($order->customer_id);
        MailConfigService::applyMailSettings();
        Mail::to($customer->email)->send(new NewOrderMail($order, $customer));
    }

    public function viewOrderDetails($orderID)
    {
        $order = $this->order
            ->leftJoin('customer', 'orders.customer_id', '=', 'customer.id')
            ->leftJoin('order_shippings', 'orders.id', '=', 'order_shippings.order_id')
            ->select('orders.*', 'customer.name as customer_name', 'customer.email as email', 'customer.phone as phone', 'order_shippings.address_line1', 'order_shippings.address_line2', 'order_shippings.city', 'order_shippings.state', 'order_shippings.postal_code', 'order_shippings.courier_name', 'order_shippings.courier_tracking_no')
            ->where('orders.id', $orderID)
            ->first();

        $orderedItems = $this->orderedItems
            ->where('order_id', $orderID)
            ->join('items', 'ordered_items.product_id', '=', 'items.item_id')
            ->select('ordered_items.*', 'items.name', 'items.url', 'items.main_image')
            ->get();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'order_data' => $order,
            'ordered_items' => $orderedItems,
        ]);
    }

    public function updateOrder(Request $request, $orderID)
    {
        try {
            DB::beginTransaction();
            
            $order = $this->order->find($orderID);
            $orderStatus = $order->status;
            $paymentStatus = $order->payment_status;
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ], 404);
            }
            
            // Update order details
            $order->update([
                'status' => $request->input('status'),
                'payment_status' => $request->input('payment_status'),
                'admin_note' => $request->input('notes'),
            ]);
            
            // Update order shipping details
            $this->orderShipping->where('order_id', $orderID)->update([
                'courier_name' => $request->input('courier_name'),
                'courier_tracking_no' => $request->input('tracking_number'),
            ]);
            
            // Insert order status history if status changed
            if ($request->input('status') !== $orderStatus) {
                $this->orderStatus->create([
                    'order_id' => $orderID,
                    'status' => $request->input('status'),
                    'changed_user_id' => Auth::id(),
                ]);
            }

            // Insert payment status history if payment status changed
            if ($request->input('payment_status') !== $paymentStatus) {
                $this->paymentStatus->create([
                    'order_id' => $orderID,
                    'payment_status' => $request->input('payment_status'),
                    'changed_user_id' => Auth::id(),
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating order: ' . $e->getMessage(),
            ], 500);
        }
    }
}
