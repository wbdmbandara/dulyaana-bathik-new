<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function addToCart(Request $request)
    {
        try {
            $message = 'Item added to cart successfully';
            $existingCartItem = Cart::where('customer_id', $request->json('customer_id'))
                ->where('item_id', $request->json('item_id'))
                ->first();

            if ($existingCartItem) {
                // If the item exists, update the quantity
                $existingCartItem->quantity += $request->json('quantity');
                $existingCartItem->save();
                $message = 'Item quantity updated in cart successfully';
            } else {
                // If the item doesn't exist, create a new cart item
                $cartItem = Cart::create([
                    'customer_id' => $request->json('customer_id'),
                    'item_id' => $request->json('item_id'),
                    'quantity' => $request->json('quantity'),
                    'added_at' => now(),
                ]);
                $message = 'Item added to cart successfully';
            }
            return response()->json(['message' => $message], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        }

    }

    public function viewCart(Request $request)
    {
        try{
            $customerId = $request->json('customer_id');
            // $cartItems = Cart::where('customer_id', $customerId)->get();
            // Get cart items from join cart table and items table
            $cartItems = Cart::join('items', 'cart.item_id', '=', 'items.item_id')
                ->where('cart.customer_id', $customerId)
                ->get(['cart.*', 'items.name', 'items.price as item_price', 'items.discount_price', 'items.url', 'items.main_image', 'items.quantity as available_qty']);

            return response()->json(['cart_items' => $cartItems], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        }

    }
}
