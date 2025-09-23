<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Items;
use App\Models\ItemImages;
use App\Models\ItemVideos;
use App\Models\Categories;
use Illuminate\Contracts\Support\ValidatedData;

class ItemsController extends Controller
{
    protected $item;
    protected $itemImages;
    protected $itemVideos;

    public function __construct(Items $item, ItemImages $itemImages, ItemVideos $itemVideos)
    {
        $this->item = $item;
        $this->itemImages = $itemImages;
        $this->itemVideos = $itemVideos;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        
        $response['sarees'] = $this->item
            ->leftJoin('item_category as category', 'items.category', '=', 'category.id')
            ->select('items.*', 'category.cat_name as category_name')
            ->paginate(10);
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
                            'item_id' => $item->id,
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
}
