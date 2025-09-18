<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Items;
use App\Models\Categories;
use Illuminate\Contracts\Support\ValidatedData;

class ItemsController extends Controller
{
    protected $item;
    public function __construct(Items $item)
    {
        $this->item = $item;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $response['sarees'] = $this->item
            ->leftJoin('item_category as category', 'items.category', '=', 'category.id')
            ->select('items.*', 'category.cat_name as category_name')
            ->get();
        return view('sarees', $response);
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        // session(['success' => "Test success message"]);
        $response['categories'] = Categories::all();
        return view('new-saree', $response);
    }

    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect('/');
            }

            $rules = [
                'name' => 'required|string|max:255|unique:items,name',
                'description' => 'required|string|max:255',
                'price' => 'required|numeric',
                'discount_price' => 'nullable|numeric',
                'quantity' => 'required|integer',
                'url' => 'required|string|max:255|unique:items,url',
                'fabric' => 'nullable|string|max:255',
                'pattern' => 'nullable|string|max:255',
                'saree_work' => 'nullable|string|max:255',
                'saree_length' => 'nullable|string|max:255',
                'blouse_length' => 'nullable|string|max:255',
                'set_contents' => 'nullable|string|max:255',
                'weight' => 'nullable|string|max:255',
                'occasion' => 'nullable|string|max:255',
                'wash_care' => 'nullable|string|max:255',
                'status' => 'required|string|max:50',
                'category' => 'required|integer|exists:item_category,id',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];

            $messages = [
                'name.required' => 'The Title is required.',
                'name.unique' => 'This Title already exists.',
                'description.required' => 'The description is required.',
                'price.required' => 'The price is required.',
                'price.numeric' => 'The price must be a valid number.',
                'discount_price.numeric' => 'The discount price must be a valid number.',
                'quantity.required' => 'The quantity is required.',
                'quantity.integer' => 'The quantity must be a whole number.',
                'url.required' => 'The URL is required.',
                'url.unique' => 'This URL already exists.',
                'status.required' => 'The status is required.',
                'category.required' => 'Please select a category.',
                'category.exists' => 'The selected category is invalid.',
                'main_image.image' => 'The file must be an image.',
                'main_image.mimes' => 'The image must be jpeg, png, jpg, gif, or svg format.',
                'main_image.max' => 'The image size must not exceed 2MB.',
            ];

            $validatedData = $request->validate($rules, $messages);

            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = 'assets/sarees';
                $image->move($destinationPath, $imageName);
                $validatedData['main_image'] = 'assets/sarees/' . $imageName;
            } else {
                $validatedData['main_image'] = null;
            }
            $validatedData['added_by'] = Auth::id();

            $item = $this->item->create($validatedData);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item added successfully.',
                    'item' => $item
                ]);
            }
            if ($item) {
                $request->session()->flash('success', 'Item added successfully.');
            } else {
                $request->session()->flash('error', 'Failed to add item. Please try again.');
            }
            return redirect()->back()->with('success', 'Item added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating item: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error creating item: ' . $e->getMessage())->withInput();
        }
    }
}
