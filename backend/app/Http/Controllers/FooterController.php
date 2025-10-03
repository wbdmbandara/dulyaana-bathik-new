<?php

namespace App\Http\Controllers;

use App\Models\FooterContents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index()
    {
        if(!Auth::check()){
            return redirect('/');
        }
        return view('footer');
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
        if(!Auth::check()){
            return redirect('/');
        }

        $validatedData = $request->validate([
            'siteName' => 'required|string|max:255',
            'aboutText' => 'string',
            'socialLinks.*.platform' => 'required|string|max:255',
            'socialLinks.*.url' => 'required|url',
            'menu01Links.*.name' => 'required|string|max:255',
            'menu01Links.*.url' => 'required|url',
            'menu02Links.*.name' => 'required|string|max:255',
            'menu02Links.*.url' => 'required|url',
            'contactInfo.address' => 'nullable|string|max:500',
            'contactInfo.email01' => 'required|email|max:255',
            'contactInfo.email02' => 'nullable|email|max:255',
            'contactInfo.phone01' => 'required|string|max:20',
            'contactInfo.phone02' => 'nullable|string|max:20',
            'contactInfo.openTime' => 'nullable|string|max:255',
            'appLinks.playStore' => 'nullable|url|max:255',
            'appLinks.appStore' => 'nullable|url|max:255',
        ]);

        $footerData = [
            'site_name' => $validatedData['siteName'],
            'about_text' => $validatedData['aboutText'] ?? '',
            'play_store_link' => $validatedData['appLinks']['playStore'] ?? '',
            'app_store_link' => $validatedData['appLinks']['appStore'] ?? '',
            'address' => $validatedData['contactInfo']['address'] ?? '',
            'email01' => $validatedData['contactInfo']['email01'],
            'email02' => $validatedData['contactInfo']['email02'] ?? '',
            'phone01' => $validatedData['contactInfo']['phone01'],
            'phone02' => $validatedData['contactInfo']['phone02'] ?? '',
            'open_time' => $validatedData['contactInfo']['openTime'] ?? '',
            'user_id' => Auth::id(),
        ];

        $footer = new FooterContents();
        FooterContents::truncate();
        $footer->fill($footerData);
        $footer->save();

        return redirect()->back()->with('success', 'Footer content updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
