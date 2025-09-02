<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function showLogin() {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function index ()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $response['users'] = $this->user->all();
        return view('users', $response);
    }
    
    public function store (Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:191|unique:users',
                'mobileno' => 'required|string|max:15|unique:users',
                'role' => 'required|string|in:admin,customer',
                'password' => 'required|string|min:8',
            ]);

            // Create the user with validated data
            $user = $this->user->create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'mobileno' => $validatedData['mobileno'],
                'role' => $validatedData['role'],
                'password' => bcrypt($validatedData['password']),
            ]);

            // Return success response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully.',
                    'user' => $user
                ]);
            }

            return redirect()->back()->with('success', 'User created successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            // Handle any other errors during user creation
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error creating user: ' . $e->getMessage())->withInput();
        }
    }

    public function show ($id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $user = $this->user->find($id);
        if ($user) {
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }
    }

    public function update (Request $request, $id)
    {
        try {
            // Find the user by ID
            $user = $this->user->find($id);
            if (!$user) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User not found.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'User not found.');
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:191|unique:users,email,' . $id,
                'mobileno' => 'required|string|max:15|unique:users,mobileno,' . $id,
                'role' => 'required|string|in:admin,customer',
                'password' => 'nullable|string|min:8',
            ]);

            // Update user details
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->mobileno = $validatedData['mobileno'];
            $user->role = $validatedData['role'];
            if (!empty($validatedData['password'])) {
                $user->password = bcrypt($validatedData['password']);
            }
            $user->save();

            // Return success response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully.',
                    'user' => $user
                ]);
            }

            return redirect()->back()->with('success', 'User updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            // Handle any other errors during user update
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating user: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error updating user: ' . $e->getMessage())->withInput();
        }
    }

    public function delete (Request $request, $id)
    {
        try {
            // Find the user by ID
            $user = $this->user->find($id);
            if (!$user) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User not found.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'User not found.');
            }

            // Delete the user
            $user->delete();

            // Return success response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully.'
                ]);
            }

            return redirect()->back()->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            // Handle any errors during user deletion
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting user: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

}


?>