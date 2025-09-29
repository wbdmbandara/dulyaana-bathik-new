<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SliderImages;

class SliderController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $response['sliders'] = SliderImages::all();
        return view('slider', $response);
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
        if (!Auth::check()) {
            return redirect('/');
        }
        $latID = SliderImages::max('id');
        $validated = $request->validate([
            'slideImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'altText' => 'required|string|max:255',
            'slideOrder' => 'required|integer|min:1',
            'slideVisibility' => 'required|boolean',
        ]);

        $slide = new SliderImages();
        $slide->alt_text = $validated['altText'];
        $slide->order = $validated['slideOrder'];
        $slide->visibility = $validated['slideVisibility'];

        if ($request->hasFile('slideImage')) {
            $image = $request->file('slideImage');
            $fileName = 'slider_' . ($latID + 1) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/slider');
            $image->move($destinationPath, $fileName);
            $slide->image_path = 'assets/slider/' . $fileName;
        }

        $slide->save();

        if($request->ajax()){
            return response()->json([
                'success' => true,
                'message' => 'Slide added successfully.',
                'slide' => $slide
            ]);
        }

        return redirect()->back()->with('success', 'Slide added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $slide = SliderImages::find($id);
        if ($slide) {
            return response()->json([
                'success' => true,
                'slide' => $slide
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Slide not found.'
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
        if (!Auth::check()) {
            return redirect('/');
        }
        $slide = SliderImages::find($id);
        if (!$slide) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slide not found.'
                ], 404);
            }
            return redirect()->back()->with('error', 'Slide not found.');
        }

        $validated = $request->validate([
            'slideImage' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'altText' => 'required|string|max:255',
            'slideOrder' => 'required|integer|min:1',
            'slideVisibility' => 'required|boolean',
        ]);

        $slide->alt_text = $validated['altText'];
        $slide->order = $validated['slideOrder'];
        $slide->visibility = $validated['slideVisibility'];

        if ($request->hasFile('slideImage')) {
            $image = $request->file('slideImage');
            $fileName = 'slider_' . $id . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/slider');
            $image->move($destinationPath, $fileName);
            $slide->image_path = 'assets/slider/' . $fileName;
        }

        $slide->save();

        if($request->ajax()){
            return response()->json([
                'success' => true,
                'message' => 'Slide updated successfully.',
                'slide' => $slide
            ]);
        }

        return redirect()->back()->with('success', 'Slide updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        $slide = SliderImages::find($id);
        if (!$slide) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slide not found.'
                ], 404);
            }
            return redirect()->back()->with('error', 'Slide not found.');
        }

        // Optionally, delete the image file from the server
        $imagePath = public_path($slide->image_path);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $slide->delete();

        if(request()->ajax()){
            return response()->json([
                'success' => true,
                'message' => 'Slide deleted successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Slide deleted successfully.');
    }

    public function displaySlides()
    {
        $slides = SliderImages::where('visibility', true)->orderBy('order', 'asc')->get();
        return response()->json($slides);
    }
}
