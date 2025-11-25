<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Customer;
use App\Services\MailConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class CustomerController extends Controller
{
    use HasApiTokens;
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
            MailConfigService::applyMailSettings();
            $customerData = Customer::where('email', $request->json('email'))->first();
            Mail::to($customerData['email'])->send(new WelcomeMail($customerData));
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
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        
        // First try to find the customer by email
        $customer = Customer::where('email', $credentials['email'])->first();
        
        if ($customer && Hash::check($credentials['password'], $customer->password)) {
            // Authentication successful
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful', 
                'user' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'role' => $customer->role ?? 'customer'
                ]
            ], 200);
        }
        
        // If customer authentication fails, try the users table as fallback
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful', 
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ?? 'admin'
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid login details'
        ], 401);
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
