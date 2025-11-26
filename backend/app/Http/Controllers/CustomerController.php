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
use Carbon\Carbon;

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
            // Update last login timestamp
            $customer->update(['last_login' => Carbon::now()]);
            
            // Create a token that expires in 2 hours
            $token = $customer->createToken('auth_token', ['*'], Carbon::now()->addHours(2))->plainTextToken;

            // Authentication successful
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful', 
                'token' => $token,
                'token_expires_at' => Carbon::now()->addHours(2)->timestamp,
                'user' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'email_confirmed' => $customer->email_confirmed,
                    'phone' => $customer->phone,
                    'birthday' => $customer->birthday,
                    'gender' => $customer->gender,
                    'role' => $customer->role ?? 'customer'
                ]
            ], 200);
        }
        
        // If customer authentication fails, try the users table as fallback
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Create a token that expires in 2 hours for admin users
            $token = $user->createToken('auth_token', ['*'], Carbon::now()->addHours(2))->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful', 
                'token' => $token,
                'token_expires_at' => Carbon::now()->addHours(2)->timestamp,
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

    public function logout(Request $request)
    {
        try {
            // Get the current user from the token
            $user = $request->user();
            
            if ($user) {
                // Revoke the current token
                $request->user()->currentAccessToken()->delete();
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Logged out successfully'
                ], 200);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function validateToken(Request $request)
    {
        try {
            // Get the current user from the token
            $user = $request->user();
            
            if ($user) {
                // Check if the token is still valid
                $token = $request->user()->currentAccessToken();
                
                if ($token && (!$token->expires_at || $token->expires_at->isFuture())) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Token is valid',
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => $user->role ?? ($user instanceof Customer ? 'customer' : 'admin')
                        ],
                        'token_expires_at' => $token->expires_at ? $token->expires_at->timestamp : null
                    ], 200);
                }
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Token is invalid or expired'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token validation failed: ' . $e->getMessage()
            ], 401);
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'email_confirmed' => $user->email_confirmed ?? null,
                        'phone' => $user->phone ?? null,
                        'birthday' => $user->birthday ?? null,
                        'gender' => $user->gender ?? null,
                        'role' => $user->role ?? ($user instanceof Customer ? 'customer' : 'admin'),
                        'last_login' => $user->last_login ?? null,
                    ]
                ], 200);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:customers,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'phone' => 'sometimes|string|max:15',
            'birthday' => 'sometimes|date',
            'gender' => 'sometimes|string|in:male,female,other',
        ]);

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
        try {
            $customer = $this->customer->find($id);

            if ($customer) {
                $customer->fill([
                    'name' => $request->json('name'),
                    'email' => $request->json('email'),
                    'phone' => $request->json('phone'),
                    'birthday' => $request->json('birthday'),
                    'gender' => $request->json('gender'),
                    'password' => $request->json('password') ? bcrypt($request->json('password')) : $customer->password,
                ]);

                $customer->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully',
                    'customer' => $customer
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
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
