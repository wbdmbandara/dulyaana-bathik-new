<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Items;
use App\Models\ItemImages;
use App\Models\ItemVideos;
use App\Models\Categories;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{
    protected $item;
    protected $itemImages;
    protected $itemVideos;
    protected $categories;

    public function __construct(Items $item, ItemImages $itemImages, ItemVideos $itemVideos, Categories $categories)
    {
        $this->item = $item;
        $this->itemImages = $itemImages;
        $this->itemVideos = $itemVideos;
        $this->categories = $categories;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        
        $query = $this->item
            ->leftJoin('item_category as category', 'items.category', '=', 'category.id')
            ->select('items.*', 'category.cat_name as category_name');

        // Search functionality
        if (request()->has('search') && !empty(request()->get('search'))) {
            $searchTerm = request()->get('search');
            $query->where(function($q) use ($searchTerm) {
            $q->where('category.cat_name', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('items.name', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('items.description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Hide zero quantity items
        if (request()->has('hide_zero_qty')) {
            $query->where('items.quantity', '>', 0);
        }

        $response['sarees'] = $query->paginate(10);
        return view('sarees', $response);
    }

    public function create()
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
        if (session()->has('temp_videos')) {
            $previousTempVideos = session('temp_videos');
            foreach ($previousTempVideos as $prevVideo) {
                if (file_exists($prevVideo)) {
                    unlink($prevVideo);
                }
            }
            session()->forget('temp_videos');
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

            // Store the uploaded main image temporarily for redisplay if validation fails
            if ($request->hasFile('main_image')) {
                $mainImageFile = $request->file('main_image');
                
                if (session()->has('temp_image')) {
                    $previousTempImage = session('temp_image');
                    if (file_exists(public_path($previousTempImage))) {
                        unlink(public_path($previousTempImage));
                    }
                    session()->forget('temp_image');
                }
                
                $tempImageName = 'temp_main_' . time() . '.' . $mainImageFile->getClientOriginalExtension();
                $tempPath = public_path('assets/sarees/temp');
                
                // Ensure directory exists
                if (!file_exists($tempPath)) {
                    mkdir($tempPath, 0755, true);
                }
                
                $mainImageFile->move($tempPath, $tempImageName);
                session(['temp_image' => 'assets/sarees/temp/' . $tempImageName]);
            }

            // Handle existing additional images from session (for validation error redisplay)
            $existingAdditionalImages = [];
            if ($request->has('existing_additional_images')) {
                $existingAdditionalImages = json_decode($request->input('existing_additional_images'), true) ?: [];
                // Filter out invalid paths
                $existingAdditionalImages = array_filter($existingAdditionalImages, function($path) {
                    return file_exists(public_path($path));
                });
            }

            // Handle new additional images
            $newAdditionalImagePaths = [];
            if ($request->hasFile('additional_images')) {
                $additionalImages = $request->file('additional_images');
                
                foreach ($additionalImages as $index => $image) {
                    if ($image->isValid()) {
                        $tempImageName = 'temp_additional_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                        $tempPath = public_path('assets/sarees/temp');
                        
                        // Ensure directory exists
                        if (!file_exists($tempPath)) {
                            mkdir($tempPath, 0755, true);
                        }
                        
                        $image->move($tempPath, $tempImageName);
                        $newAdditionalImagePaths[] = 'assets/sarees/temp/' . $tempImageName;
                    }
                }
            }
            
            // Merge existing and new additional images
            $allAdditionalImages = array_merge($existingAdditionalImages, $newAdditionalImagePaths);
            if (!empty($allAdditionalImages)) {
                session(['temp_additional_images' => $allAdditionalImages]);
            }

            // Handle existing videos from session (for validation error redisplay)
            $existingVideos = [];
            if ($request->has('existing_videos')) {
                $existingVideos = json_decode($request->input('existing_videos'), true) ?: [];
                // Filter out invalid paths
                $existingVideos = array_filter($existingVideos, function($path) {
                    return file_exists(public_path($path));
                });
            }

            // Handle new videos
            $newVideoPaths = [];
            if ($request->hasFile('videos')) {
                $videos = $request->file('videos');
                
                foreach ($videos as $index => $video) {
                    if ($video->isValid()) {
                        $tempVideoName = 'temp_video_' . time() . '_' . $index . '.' . $video->getClientOriginalExtension();
                        $tempPath = public_path('assets/sarees/temp');
                        
                        // Ensure directory exists
                        if (!file_exists($tempPath)) {
                            mkdir($tempPath, 0755, true);
                        }
                        
                        $video->move($tempPath, $tempVideoName);
                        $newVideoPaths[] = 'assets/sarees/temp/' . $tempVideoName;
                    }
                }
            }
            
            // Merge existing and new videos
            $allVideos = array_merge($existingVideos, $newVideoPaths);
            if (!empty($allVideos)) {
                session(['temp_videos' => $allVideos]);
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
            ];

            $validatedData = $request->validate($rules, $messages);

            // Move main image from temp to permanent location
            if (session()->has('temp_image')) {
                $tempImagePath = session('temp_image');
                $fullTempPath = public_path($tempImagePath);
                if (file_exists($fullTempPath)) {
                    $imageName = $validatedData['url'] . '.' . pathinfo($tempImagePath, PATHINFO_EXTENSION);
                    $destinationPath = public_path('assets/sarees/' . $imageName);
                    rename($fullTempPath, $destinationPath);
                    $validatedData['main_image'] = 'assets/sarees/' . $imageName;
                    session()->forget('temp_image');
                }
            } else {
                $validatedData['main_image'] = null;
            }
            
            $validatedData['added_by'] = Auth::id();
            $item = $this->item->create($validatedData);

            // Move additional images from temp to permanent location and save to database
            if (session()->has('temp_additional_images')) {
                $tempAdditionalImages = session('temp_additional_images');
                $additionalImagePaths = [];
                
                foreach ($tempAdditionalImages as $index => $tempImagePath) {
                    $fullTempPath = public_path($tempImagePath);
                    if (file_exists($fullTempPath)) {
                        $imageName = $validatedData['url'] . '_' . $index . '.' . pathinfo($tempImagePath, PATHINFO_EXTENSION);
                        $destinationPath = public_path('assets/sarees/' . $imageName);
                        rename($fullTempPath, $destinationPath);
                        $additionalImagePaths[] = 'assets/sarees/' . $imageName;
                    }
                }
                
                if (!empty($additionalImagePaths)) {
                    $itemImages = [];
                    foreach ($additionalImagePaths as $path) {
                        $itemImages[] = [
                            'item_id' => $item->item_id,
                            'image_path' => $path,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                    $this->itemImages->insert($itemImages);
                }
                session()->forget('temp_additional_images');
            }

            // Move videos from temp to permanent location and save to database
            if (session()->has('temp_videos')) {
                $tempVideos = session('temp_videos');
                $videoPaths = [];
                
                // Ensure videos directory exists
                $videosDir = public_path('assets/sarees/videos');
                if (!file_exists($videosDir)) {
                    mkdir($videosDir, 0755, true);
                }
                
                foreach ($tempVideos as $index => $tempVideoPath) {
                    $fullTempPath = public_path($tempVideoPath);
                    if (file_exists($fullTempPath)) {
                        $videoName = $validatedData['url'] . '_video_' . $index . '.' . pathinfo($tempVideoPath, PATHINFO_EXTENSION);
                        $destinationPath = public_path('assets/sarees/videos/' . $videoName);
                        rename($fullTempPath, $destinationPath);
                        $videoPaths[] = 'assets/sarees/videos/' . $videoName;
                    }
                }
                
                if (!empty($videoPaths)) {
                    $itemVideos = [];
                    foreach ($videoPaths as $path) {
                        $itemVideos[] = [
                            'item_id' => $item->item_id,
                            'video_url' => $path,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                    $this->itemVideos->insert($itemVideos);
                }
                session()->forget('temp_videos');
            }

            // Clean up temp directory
            $tempDir = public_path('assets/sarees/temp');
            if (is_dir($tempDir)) {
                $files = glob($tempDir . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item added successfully.',
                    'item' => $item
                ]);
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

    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        
        $item = $this->item->where('item_id', $id)->first();
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $response['saree'] = $item;
        $response['categories'] = Categories::all();
        $response['additional_images'] = $this->itemImages->where('item_id', $id)->pluck('image_path')->toArray();
        $response['videos'] = $this->itemVideos->where('item_id', $id)->pluck('video_url')->toArray();
        return view('edit-saree', $response);
    }

    public function update(Request $request, $id)
    {
        try {
            if (!Auth::check()) {
                return redirect('/');
            }

            $item = $this->item->where('item_id', $id)->first();
            if (!$item) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Item not found.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Item not found.');
            }

            // Store the uploaded main image temporarily for redisplay if validation fails
            if ($request->hasFile('main_image')) {
                $mainImageFile = $request->file('main_image');
                
                if (session()->has('temp_image')) {
                    $previousTempImage = session('temp_image');
                    if (file_exists(public_path($previousTempImage))) {
                        unlink(public_path($previousTempImage));
                    }
                    session()->forget('temp_image');
                }
                
                $tempImageName = 'temp_main_' . time() . '.' . $mainImageFile->getClientOriginalExtension();
                $tempPath = public_path('assets/sarees/temp');
                
                // Ensure directory exists
                if (!file_exists($tempPath)) {
                    mkdir($tempPath, 0755, true);
                }
                
                $mainImageFile->move($tempPath, $tempImageName);
                session(['temp_image' => 'assets/sarees/temp/' . $tempImageName]);
            } else {
                // If no new main image uploaded, keep existing main image path in session for redisplay
                session(['temp_image' => $item->main_image]);
            }

            // Handle existing additional images from session (for validation error redisplay)
            $existingAdditionalImages = [];
            if ($request->has('existing_additional_images')) {
                $existingAdditionalImages = json_decode($request->input('existing_additional_images'), true) ?: [];
                // Filter out invalid paths
                $existingAdditionalImages = array_filter($existingAdditionalImages, function($path) {
                    return file_exists(public_path($path));
                });
            }

            // Handle new additional images
            $newAdditionalImagePaths = [];
            if ($request->hasFile('additional_images')) {
                $additionalImages = $request->file('additional_images');
                
                foreach ($additionalImages as $index => $image) {
                    if ($image->isValid()) {
                        $tempImageName = 'temp_additional_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                        $tempPath = public_path('assets/sarees/temp');
                        
                        // Ensure directory exists
                        if (!file_exists($tempPath)) {
                            mkdir($tempPath, 0755, true);
                        }
                        
                        $image->move($tempPath, $tempImageName);
                        $newAdditionalImagePaths[] = 'assets/sarees/temp/' . $tempImageName;
                    }
                }
                session(['temp_additional_images' => $newAdditionalImagePaths]);
            }

            // Handle existing videos from session (for validation error redisplay)
            $existingVideos = [];
            if ($request->has('existing_videos')) {
                $existingVideos = json_decode($request->input('existing_videos'), true) ?: [];
                // Filter out invalid paths
                $existingVideos = array_filter($existingVideos, function($path) {
                    return file_exists(public_path($path));
                });
            }

            // Handle new videos
            $newVideoPaths = [];
            if ($request->hasFile('videos')) {
                $videos = $request->file('videos');

                foreach ($videos as $index => $video) {
                    if ($video->isValid()) {
                        $tempVideoName = 'temp_video_' . time() . '_' . $index . '.' . $video->getClientOriginalExtension();
                        $tempPath = public_path('assets/sarees/temp');

                        // Ensure directory exists
                        if (!file_exists($tempPath)) {
                            mkdir($tempPath, 0755, true);
                        }

                        $video->move($tempPath, $tempVideoName);
                        $newVideoPaths[] = 'assets/sarees/temp/' . $tempVideoName;
                    }
                }
                session(['temp_videos' => $newVideoPaths]);
            }
            $rules = [
                'name' => 'required|string|max:255|unique:items,name,' . $item->item_id . ',item_id',
                'description' => 'required|string|max:255',
                'price' => 'required|numeric',
                'discount_price' => 'nullable|numeric',
                'quantity' => 'required|integer',
                'url' => 'required|string|max:255|unique:items,url,' . $item->item_id . ',item_id',
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
            ];
            $validatedData = $request->validate($rules, $messages);
            // Move main image from temp to permanent location
            if (session()->has('temp_image')) {
                $tempImagePath = session('temp_image');
                $fullTempPath = public_path($tempImagePath);
                if (file_exists($fullTempPath) && strpos($tempImagePath, 'assets/sarees/temp/') === 0) {
                    // New main image uploaded, move it to permanent location
                    $imageName = $validatedData['url'] . '.' . pathinfo($tempImagePath, PATHINFO_EXTENSION);
                    $destinationPath = public_path('assets/sarees/' . $imageName);
                    rename($fullTempPath, $destinationPath);
                    $validatedData['main_image'] = 'assets/sarees/' . $imageName;
                } else {
                    // No new image, keep existing path
                    $validatedData['main_image'] = $item->main_image;
                }
                session()->forget('temp_image');
            } else {
                $validatedData['main_image'] = $item->main_image;
            }
            $validatedData['added_by'] = Auth::id();
            $item->where('item_id', $id)->update($validatedData);
            // Move additional images from temp to permanent location and save to database
            $allAdditionalImages = [];
            if (session()->has('temp_additional_images')) {
                $tempAdditionalImages = session('temp_additional_images');
                $newAdditionalImagePaths = [];
                
                foreach ($tempAdditionalImages as $index => $tempImagePath) {
                    $fullTempPath = public_path($tempImagePath);
                    if (file_exists($fullTempPath) && strpos($tempImagePath, 'assets/sarees/temp/') === 0) {
                        $imageName = $validatedData['url'] . '_' . $index . '.' . pathinfo($tempImagePath, PATHINFO_EXTENSION);
                        $destinationPath = public_path('assets/sarees/' . $imageName);
                        rename($fullTempPath, $destinationPath);
                        $newAdditionalImagePaths[] = 'assets/sarees/' . $imageName;
                    } elseif (file_exists($fullTempPath)) {
                        // Existing image, keep its path
                        $newAdditionalImagePaths[] = $tempImagePath;
                    }
                }
                $allAdditionalImages = array_merge($existingAdditionalImages, $newAdditionalImagePaths);
                session()->forget('temp_additional_images');
            } else {
                $allAdditionalImages = $existingAdditionalImages;
            }
            // Sync additional images in database
            $this->itemImages->where('item_id', $item->item_id)->delete();
            if (!empty($allAdditionalImages)) {
                $itemImages = [];
                foreach ($allAdditionalImages as $path) {
                    $itemImages[] = [
                        'item_id' => $item->item_id,
                        'image_path' => $path,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                $this->itemImages->insert($itemImages);
            }
            // Move videos from temp to permanent location and save to database
            $allVideos = [];
            if (session()->has('temp_videos')) {
                $tempVideos = session('temp_videos');
                $newVideoPaths = [];
                
                // Ensure videos directory exists
                $videosDir = public_path('assets/sarees/videos');
                if (!file_exists($videosDir)) {
                    mkdir($videosDir, 0755, true);
                }
                
                foreach ($tempVideos as $index => $tempVideoPath) {
                    $fullTempPath = public_path($tempVideoPath);
                    if (file_exists($fullTempPath) && strpos($tempVideoPath, 'assets/sarees/temp/') === 0) {
                        $videoName = $validatedData['url'] . '_video_' . time() . '_' . $index . '.' . pathinfo($tempVideoPath, PATHINFO_EXTENSION);
                        $destinationPath = public_path('assets/sarees/videos/' . $videoName);
                        rename($fullTempPath, $destinationPath);
                        $newVideoPaths[] = 'assets/sarees/videos/' . $videoName;
                    } elseif (file_exists($fullTempPath)) {
                        // Existing video, keep its path
                        $newVideoPaths[] = $tempVideoPath;
                    }
                }
                $allVideos = array_merge($existingVideos, $newVideoPaths);
                session()->forget('temp_videos');
            } else {
                $allVideos = $existingVideos;
            }
            // Sync videos in database
            $this->itemVideos->where('item_id', $item->item_id)->delete();
            if (!empty($allVideos)) {
                $itemVideos = [];
                foreach ($allVideos as $path) {
                    $itemVideos[] = [
                        'item_id' => $item->item_id,
                        'video_url' => $path,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                $this->itemVideos->insert($itemVideos);
            }
            // Clean up temp directory
            $tempDir = public_path('assets/sarees/temp');
            if (is_dir($tempDir)) {
                $files = glob($tempDir . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item updated successfully.',
                    'item' => $item
                ]);
            }
            return redirect()->back()->with('success', 'Item updated successfully.');
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
                    'message' => 'Error updating item: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error updating item: ' . $e->getMessage())->withInput();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 401);
            }

            $item = $this->item->where('item_id', $id)->first();
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found.'
                ], 404);
            }

            // Delete associated images from filesystem and database
            $itemImages = $this->itemImages->where('item_id', $item->item_id)->get();
            foreach ($itemImages as $image) {
                if (file_exists(public_path($image->image_path))) {
                    unlink(public_path($image->image_path));
                }
            }
            $this->itemImages->where('item_id', $item->item_id)->delete();

            // Delete associated videos from filesystem and database
            $itemVideos = $this->itemVideos->where('item_id', $item->item_id)->get();
            foreach ($itemVideos as $video) {
                if (file_exists(public_path($video->video_url))) {
                    unlink(public_path($video->video_url));
                }
            }
            $this->itemVideos->where('item_id', $item->item_id)->delete();

            // Delete main image from filesystem
            if (file_exists(public_path($item->main_image))) {
                unlink(public_path($item->main_image));
            }

            // Finally, delete the item record
            $this->item->where('item_id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductDetails(Request $request, $url)
    {
        try {
            if (!$url) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product URL is required.'
                ], 400);
            }

            $item = $this->item
                ->leftJoin('item_category as category', 'items.category', '=', 'category.id')
                ->select('items.*', 'category.cat_name as category_name')
                ->where('items.url', $url)
                ->first();

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 404);
            }

            $additionalImages = $this->itemImages->where('item_id', $item->item_id)->pluck('image_path')->toArray();
            $videos = $this->itemVideos->where('item_id', $item->item_id)->pluck('video_url')->toArray();

            return response()->json([
                'success' => true,
                'product' => $item,
                'additional_images' => $additionalImages,
                'videos' => $videos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRecentProducts(Request $request)
    {
        try {
            $limit = $request->input('limit', 12);
            $items = $this->item
                ->leftJoin('item_category as category', 'items.category', '=', 'category.id')
                ->select('items.*', 'category.cat_name as category_name')
                ->where('items.quantity', '>', 0)
                ->where('items.status', 'active')
                ->orderBy('items.created_at', 'desc')
                ->limit($limit)
                ->get();

            $response = [];
            foreach ($items as $item) {
                $additionalImages = $this->itemImages->where('item_id', $item->item_id)->pluck('image_path')->toArray();
                $videos = $this->itemVideos->where('item_id', $item->item_id)->pluck('video_url')->toArray();

                $response[] = [
                    'product' => $item,
                    'additional_images' => $additionalImages,
                    'videos' => $videos
                ];
            }

            return response()->json([
                'success' => true,
                'products' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching recent products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMinAndMaxPrices()
    {
        try {
            $minPrice = $this->item->min('price');
            $maxPrice = $this->item->max('price');

            return response()->json([
                'success' => true,
                'min_price' => $minPrice,
                'max_price' => $maxPrice
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching price range: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFabricList()
    {
        try {
            // get fabrics and count of items for each fabric
            $fabrics = $this->item->select('fabric', DB::raw('count(*) as item_count'))
                ->where('quantity', '>', 0)
                ->where('status', 'active')
                ->groupBy('fabric')
                ->orderBy('item_count', 'desc')
                ->get();
            $fabrics = $fabrics->map(function ($item) {
                return [
                    'fabric' => $item->fabric ?? 'Not Specified',
                    'item_count' => $item->item_count
                ];
            });
            return response()->json([
                'success' => true,
                'fabrics' => $fabrics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching fabric list: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showItems(Request $request)
    {
        return $this->filterItems($request, null);
    }

    public function filterItems(Request $request)
    {
        if (empty($request)) {
            $query = $this->item->leftJoin('item_category as category', 'items.category', '=', 'category.id')
                ->select('items.*', 'category.cat_name as category_name')
                ->where('items.quantity', '>', 0)
                ->where('items.status', 'active')
                ->orderBy('items.item_id', 'desc')
                ->paginate(12);
            $items = $query->appends($request->except('page'));
            return response()->json([
                'success' => true,
                'message' => 'No category specified, displaying all items.',
                'data' => $items
            ], 200);
        }

        // Base query with join to category
        $query = $this->item->leftJoin('item_category as category', 'items.category', '=', 'category.id')
            ->select('items.*', 'category.cat_name as category_name', 'category.cat_slug as category_slug');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('items.name', 'like', '%' . $search . '%')
                    ->orWhere('items.description', 'like', '%' . $search . '%')
                    ->orWhere('items.url', 'like', '%' . $search . '%')
                    ->orWhere('category.cat_name', 'like', '%' . $search . '%');
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            $category = $request->input('category');
            $categoryID = $this->categories->where('cat_slug', $category)->value('id');
            $query->where('items.category', $categoryID);
        }

        // Apply price range filter
        if ($request->filled('min_price') && is_numeric($request->input('min_price'))) {
            $minPrice = $request->input('min_price');
            $query->where('items.price', '>=', $minPrice);
        }

        if ($request->filled('max_price') && is_numeric($request->input('max_price'))) {
            $maxPrice = $request->input('max_price');
            $query->where('items.price', '<=', $maxPrice);
        }

        // Apply fabric filter (supports multiple fabrics via comma-separated values)
        if ($request->filled('fabrics')) {
            $fabrics = explode(',', $request->input('fabrics'));
            $query->whereIn('items.fabric', $fabrics);
        }

        // Apply default filter to show only items with quantity > 0 and status = active
        $query->where('items.quantity', '>', 0)->where('items.status', 'active');

        // Apply sorting
        if ($request->filled('sort')) {
            $sortBy = $request->input('sort');
            switch ($sortBy) {
                case 'featured':
                    $query->orderBy('items.created_at', 'desc'); // Placeholder, as no featured field exists
                    break;
                case 'price_low_high':
                    $query->orderBy('items.price', 'asc');
                    break;
                case 'price_high_low':
                    $query->orderBy('items.price', 'desc');
                    break;
                case 'customer_rating':
                    $query->orderBy('items.item_id', 'desc'); // Placeholder, as no rating field exists
                    break;
                case 'newest_arrivals':
                    $query->orderBy('items.created_at', 'desc');
                    break;
                default:
                    $query->orderBy('items.item_id', 'desc');
                    break;
            }
        } else {
            $query->orderBy('items.item_id', 'desc');
        }

        // Pagination
        $perPage = $request->input('limit', 12); // Default to 12 items per page
        $items = $query->paginate($perPage)->appends($request->except('page'));

        $response = $items->map(function ($item) {
            $additionalImages = $this->itemImages->where('item_id', $item->item_id)->pluck('image_path')->toArray();

            return [
                'product' => $item,
                'additional_images' => $additionalImages,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $response,
            'pagination' => [
                'total' => $items->total(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage()
            ]
        ]);
    }

}