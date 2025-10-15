<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;

class CategoryController extends Controller
{

    protected $category;
    public function __construct(Categories $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $response['categories'] = $this->category
            ->leftJoin('item_category as parent', 'item_category.parent_id', '=', 'parent.id')
            ->select('item_category.*', 'parent.cat_name as parent_name')
            ->get();
        return view('categories', $response);
    }

    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect('/');
            }
            
            $validatedData = $request->validate([
                'cat_name' => 'required|string|max:255|unique:item_category,cat_name',
                'cat_description' => 'required|string|max:255',
                'cat_slug' => 'required|string|max:255|unique:item_category,cat_slug',
                'parent_id' => 'required|integer',
            ]);

            $category = $this->category->create($validatedData);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category added successfully.',
                    'category' => $category
                ]);
            }

            return redirect()->back()->with('success', 'Category added successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error creating user: ' . $e->getMessage())->withInput();
        }
    }

    public function show(string $id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $category = $this->category->find($id);
        if ($category) {
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Find the category by ID
            $category = $this->category->find($id);
            if (!$category) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Category not found.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Category not found.');
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'cat_name' => 'required|string|max:255|unique:item_category,cat_name,' . $id,
                'cat_description' => 'required|string|max:255',
                'cat_slug' => 'required|string|max:255|unique:item_category,cat_slug,' . $id,
                'parent_id' => 'required|integer',
            ]);

            // Update the category with validated data
            $category->update($validatedData);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category updated successfully.',
                    'category' => $category
                ]);
            }

            return redirect()->back()->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            // Handle any other errors during category update
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating category: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error updating category: ' . $e->getMessage())->withInput();
        }
    }

    public function delete(string $id)
    {
        try {
            if (!Auth::check()) {
                return redirect('/');
            }

            $category = $this->category->find($id);
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found.'
                ], 404);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getParentCategories()
    {
        try{
            $categories = $this->category->where('parent_id', 0)->get();
            return response()->json(
                [
                    'success' => true,
                    'categories' => $categories
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error fetching parent categories: ' . $e->getMessage()
                ],
                500
            );
        }
    }
}
