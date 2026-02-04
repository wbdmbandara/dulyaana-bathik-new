<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Mail\ResetPasswordSuccessMail;
use App\Mail\WelcomeMail;
use App\Models\Customer;
use App\Models\CustomerAddress;
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
    protected $customerAddress;

    public function __construct(Customer $customer, CustomerAddress $customerAddress)
    {
        $this->customer = $customer;
        $this->customerAddress = $customerAddress;
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

    public function forgotPassword(Request $request)
    {
        // find customer using email
        $customer = $this->customer->where('email', $request->json('email'))->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        // generate reset token
        $token = bin2hex(random_bytes(16));
        $customer->pw_reset_token = $token;
        $customer->save();

        // send reset email
        Mail::to($customer->email)->send(new ResetPasswordMail($customer));

        return response()->json([
            'success' => true,
            'message' => 'Reset password email sent'
        ]);
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

    public function getCustomerEmail(Request $request){
        // get token from url query string
        $token = $request->query('token');

        // find customer using token
        $customer = $this->customer->where('pw_reset_token', $token)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'email' => $customer->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find customer by token
        $customer = Customer::where('pw_reset_token', $request->token)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired reset token.'
            ], 404);
        }

        // Update and Clear Token
        $customer->password = bcrypt($request->password);
        $customer->pw_reset_token = null; 
        $customer->save();

        // send success email
        Mail::to($customer->email)->send(new ResetPasswordSuccessMail($customer));

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
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

    public function addAddress(Request $request, $id)
    {
        try {
            $customer = $this->customer->find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $isDefault = $request->json('isDefault', false) ? 1 : 0;

            // If this address is set as default, unset other default addresses for this customer
            if ($isDefault) {
                $this->customerAddress->where('customer_id', $id)->update(['is_default' => 0]);
            }

            $address = $this->customerAddress->create([
                'full_name' => $request->json('fullName'),
                'phone_number' => $request->json('phoneNumber'),
                'customer_id' => $id,
                'address_line1' => $request->json('addressLine1'),
                'address_line2' => $request->json('addressLine2'),
                'type' => $request->json('type'),
                'city' => $request->json('city'),
                'state' => $request->json('state'),
                'postal_code' => $request->json('zipCode'),
                'label' => $request->json('addressLabel'),
                'is_default' => $isDefault,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully',
                'address' => $address
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAddresses(Request $request, $id)
    {
        try {
            $customer = $this->customer->find($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $addresses = $this->customerAddress->where('customer_id', $id)->orderBy('is_default', 'desc')->get();

            return response()->json([
                'success' => true,
                'addresses' => $addresses
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch addresses: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAddress(Request $request, $id)
    {
        try {
            $address = $this->customerAddress->find($id);

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found'
                ], 404);
            }

            $isDefault = $request->json('isDefault', false) ? 1 : 0;

            // If this address is set as default, unset other default addresses for this customer
            if ($isDefault) {
                $this->customerAddress
                    ->where('customer_id', $address->customer_id)
                    ->where('id', '!=', $id)
                    ->update(['is_default' => 0]);
            }

            $address->update([
                'full_name' => $request->json('fullName'),
                'phone_number' => $request->json('phoneNumber'),
                'address_line1' => $request->json('addressLine1'),
                'address_line2' => $request->json('addressLine2'),
                'type' => $request->json('type'),
                'city' => $request->json('city'),
                'state' => $request->json('state'),
                'postal_code' => $request->json('zipCode'),
                'label' => $request->json('addressLabel'),
                'is_default' => $isDefault,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'address' => $address
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAddress($id)
    {
        try {
            $address = $this->customerAddress->find($id);

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found'
                ], 404);
            }

            // Don't allow deletion of the default address if it's the only one
            if ($address->is_default) {
                $addressCount = $this->customerAddress
                    ->where('customer_id', $address->customer_id)
                    ->count();

                if ($addressCount > 1) {
                    // Set another address as default
                    $nextAddress = $this->customerAddress
                        ->where('customer_id', $address->customer_id)
                        ->where('id', '!=', $id)
                        ->first();

                    if ($nextAddress) {
                        $nextAddress->update(['is_default' => 1]);
                    }
                }
            }

            $address->delete();

            return response()->json([
                'success' => true,
                'message' => 'Address deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setDefaultAddress($id)
    {
        try {
            $address = $this->customerAddress->find($id);

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found'
                ], 404);
            }

            // Unset other default addresses for this customer
            $this->customerAddress
                ->where('customer_id', $address->customer_id)
                ->update(['is_default' => 0]);

            // Set this address as default
            $address->update(['is_default' => 1]);

            return response()->json([
                'success' => true,
                'message' => 'Default address updated successfully',
                'address' => $address
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set default address: ' . $e->getMessage()
            ], 500);
        }
    }
}
