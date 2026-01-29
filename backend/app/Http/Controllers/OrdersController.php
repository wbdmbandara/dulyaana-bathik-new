<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Items;
use App\Models\Order;
use App\Models\OrderedItems;
use App\Models\OrderShipping;
use App\Models\OrderStatus;
use App\Models\Cart;

class OrdersController extends Controller
{
    protected $items;
    protected $order;
    protected $orderedItems;
    protected $orderShipping;
    protected $orderStatus;
    protected $cart;

    public function __construct(Items $items, Order $order, OrderedItems $orderedItems, OrderShipping $orderShipping, OrderStatus $orderStatus, Cart $cart)
    {
        $this->items = $items;
        $this->order = $order;
        $this->orderedItems = $orderedItems;
        $this->orderShipping = $orderShipping;
        $this->orderStatus = $orderStatus;
        $this->cart = $cart;
    }

    public function placeOrder(Request $request)
    {
        try{
            $customer = $request->json('customer');
            $totals = $request->json('totals');
            $payment = $request->json('payment');
            $orderedItemsData = $request->json('items');
            $customerID = $orderedItemsData[0]['customer_id'];
            $shipping = $request->json('shipping');
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
                    'discount' => $request->json('discount') ? $request->json('discount') : 0, // optional discount
                    'final_amount' => $totals['total'],
                    'payment_method' => $payment['displayName'],
                    'note' => $request->json('note') ?? '', // optional note
                    'status' => 'pending',
                ]);

                // get order id
                $orderId = $order->id;

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

                // Clear Cart
                $this->cart->where('customer_id', $customerID)->delete();

                DB::commit();

                // Send Email

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
}
