<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Items;
use App\Models\ItemImages;
use App\Models\Categories;
use Illuminate\Contracts\Support\ValidatedData;

class ItemsController extends Controller
{
    protected $item;
    protected $itemImages;

    public function __construct(Items $item, ItemImages $itemImages)
    {
        $this->item = $item;
        $this->itemImages = $itemImages;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        if (session()->has('temp_image')) {
            $previousTempImage = session('temp_image');
            if (file_exists($previousTempImage)) {
                unlink($previousTempImage);
            }
            session()->forget('temp_image');
        }
        if (session()->has('temp_additional_images')) {
            $previousTempImages = session('temp_additional_images');
            foreach ($previousTempImages as $prevImage) {
                if (file_exists($prevImage)) {
                    unlink($prevImage);
                }
            }
            session()->forget('temp_additional_images');
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

            // Store the uploaded image temporarily for redisplay if validation fails
            if ($request->hasFile('main_image')) {
                if (session()->has('temp_image')) {
                    $previousTempImage = session('temp_image');
                    if (file_exists($previousTempImage)) {
                        unlink($previousTempImage);
                    }
                    session()->forget('temp_image');
                }
                $tempImage = $request->file('main_image');
                $tempImageName = 'temp_image.' . $tempImage->getClientOriginalExtension();
                $tempPath = 'assets/sarees';
                copy($tempImage->getRealPath(), $tempPath . '/' . $tempImageName);
                session(['temp_image' => 'assets/sarees/' . $tempImageName]);
            }

            if ($request->hasFile('additional_images')) {
                if (session()->has('temp_additional_images')) {
                    $previousTempImages = session('temp_additional_images');
                    foreach ($previousTempImages as $prevImage) {
                        if (file_exists($prevImage)) {
                            unlink($prevImage);
                        }
                    }
                    session()->forget('temp_additional_images');
                }
                $additionalImages = $request->file('additional_images');
                $additionalImagePaths = [];
                foreach ($additionalImages as $index => $image) {
                    if ($image->isValid()) {
                        $tempImageName = 'temp_image_' . $index . '.' . $image->getClientOriginalExtension();
                        $tempPath = 'assets/sarees/temp';
                        if (!file_exists($tempPath)) {
                            mkdir($tempPath, 0755, true);
                        }
                        copy($image->getRealPath(), $tempPath . '/' . $tempImageName);
                        $additionalImagePaths[$index] = $tempPath . '/' . $tempImageName;
                    }
                }
                session(['temp_additional_images' => $additionalImagePaths]);
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
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
                'additional_images.*.image' => 'Each additional file must be an image.',
                'additional_images.*.mimes' => 'Each additional image must be jpeg, png, jpg, gif, or svg format.',
                'additional_images.*.max' => 'Each additional image size must not exceed 2MB.',
            ];

            $validatedData = $request->validate($rules, $messages);

            if (session()->has('temp_image')) {
                $previousTempImage = session('temp_image');
                if (file_exists($previousTempImage)) {
                    $imageName = $validatedData['url'] . '.' . pathinfo($previousTempImage, PATHINFO_EXTENSION);
                    rename($previousTempImage, 'assets/sarees/' . $imageName);
                    $validatedData['main_image'] = 'assets/sarees/' . $imageName;
                    session()->forget('temp_image');
                }
            } else {
                $validatedData['main_image'] = null;
            }
            $validatedData['added_by'] = Auth::id();

            $item = $this->item->create($validatedData);

            if (session()->has('temp_additional_images')) {
                $previousTempImages = session('temp_additional_images');
                $additionalImagePaths = [];
                foreach ($previousTempImages as $index => $tempImagePath) {
                    if (file_exists($tempImagePath)) {
                        $imageName = $validatedData['url'] . '_' . $index . '.' . pathinfo($tempImagePath, PATHINFO_EXTENSION);
                        $destinationPath = 'assets/sarees/' . $imageName;
                        rename($tempImagePath, $destinationPath);
                        $additionalImagePaths[] = $destinationPath;
                    }
                }
                if (!empty($additionalImagePaths)) {
                    $itemImages = [];
                    foreach ($additionalImagePaths as $path) {
                        $itemImages[] = [
                            'item_id' => $item->id,
                            'image_path' => $path,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                    $this->itemImages->insert($itemImages);
                }
                session()->forget('temp_additional_images');
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item added successfully.',
                    'item' => $item
                ]);
            }
            if ($item) {
                $request->session()->flash('success', 'Item added successfully.');
                if (session()->has('temp_image')) {
                    $previousTempImage = session('temp_image');
                    if (file_exists($previousTempImage)) {
                        unlink($previousTempImage);
                    }
                    session()->forget('temp_image');
                }
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
