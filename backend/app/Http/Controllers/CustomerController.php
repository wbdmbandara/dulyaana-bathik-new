<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    protected $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $response['customers'] = $this->customer->all();
        return view('customers', $response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
    }

    public function register(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:customers',
        //     'password' => 'required|string|min:8',
        //     'password_confirmation' => 'required|string|same:password',
        // ], [], [], function () use ($request) {
        //     return $request->json()->all();
        // });

        $exixtingCustomer = Customer::where('email', $request->json('email'))->first();
        if($exixtingCustomer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email already exists',
            ], 409);
        }

        $customer = Customer::create([
            'name' => $request->json('name'),
            'email' => $request->json('email'),
            'password' => bcrypt($request->json('password')),
            'subscribed_to_newsletter' => $request->json('newsletter', false),
        ]);

        if($customer) {
            return response()->json([
                'status' => 'success',
                'message' => 'Customer registered successfully',
                'customer' => $customer
            ], 201);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Customer registration failed',
        ], 500);
    }

    public function login(Request $request)
    {
        $customer = Customer::where('email', $request->input('email'))->first();

        if (!$customer || !Hash::check($request->input('password'), $customer->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'customer' => $customer
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $customer = $this->customer->find($id);

        if($customer) {
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $customer = $this->customer->find($id);

        if ($customer) {
            $customer->delete();
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Customer not found'
        ], 404);
    }
}
