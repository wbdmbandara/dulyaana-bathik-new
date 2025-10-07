<?php

namespace App\Http\Controllers;

use App\Models\FooterContents;
use App\Models\FooterMenus;
use App\Models\SocialLinks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FooterController extends Controller
{
    public function index()
    {
        if(!Auth::check()){
            return redirect('/');
        }

        $footerContents = FooterContents::first();
        $socialLinks = SocialLinks::all();
        $menu01Links = FooterMenus::where('menu_type', 'menu_01')->get();
        $menu02Links = FooterMenus::where('menu_type', 'menu_02')->get();
        $footerLinks = FooterMenus::where('menu_type', 'footer_menu')->get();
        $response = [
            'footerContent' => $footerContents,
            'socialLinks' => $socialLinks,
            'menu01Links' => $menu01Links,
            'menu02Links' => $menu02Links,
            'footerLinks' => $footerLinks,
        ];
        return view('footer', $response);
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
            'socialLinks.*.platform' => 'string|max:255',
            'socialLinks.*.url' => 'url',
            'socialLinks.*.icon' => 'string|max:255',
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
            'footerMenu.*.name' => 'required|string|max:255',
            'footerMenu.*.url' => 'required|url',
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

        $socialLinksData = $validatedData['socialLinks'] ?? [];
        $menu01LinksData = $validatedData['menu01Links'] ?? [];
        $menu02LinksData = $validatedData['menu02Links'] ?? [];
        $footerMenuData = $validatedData['footerMenu'] ?? [];

        try {
            $footer = new FooterContents();
            FooterContents::truncate();
            $footer->fill($footerData);
            $footer->save();

            $socialLinks = new SocialLinks();
            SocialLinks::truncate();
            foreach ($socialLinksData as $link) {
                $socialLinks->create([
                    'platform' => $link['platform'],
                    'url' => $link['url'],
                    'icon' => $link['icon'],
                    'user_id' => Auth::id(),
                ]);
            }

            $menuLinks = new FooterMenus();
            FooterMenus::truncate();
            
            foreach ($menu01LinksData as $link) {
                $menuLinks->create([
                    'title' => $link['name'],
                    'url' => $link['url'],
                    'menu_type' => 'menu_01',
                    'user_id' => Auth::id(),
                ]);
            }

            foreach ($menu02LinksData as $link) {
                $menuLinks->create([
                    'title' => $link['name'],
                    'url' => $link['url'],
                    'menu_type' => 'menu_02',
                    'user_id' => Auth::id(),
                ]);
            }

            foreach ($footerMenuData as $link) {
                $menuLinks->create([
                    'title' => $link['name'],
                    'url' => $link['url'],
                    'menu_type' => 'footer_menu',
                    'user_id' => Auth::id(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Footer content updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update footer content.');
        }
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

    public function getFooterData(Request $request)
    {
        $footerContents = FooterContents::first();
        $socialLinks = SocialLinks::all();
        $menu01Links = FooterMenus::where('menu_type', 'menu_01')->get();
        $menu02Links = FooterMenus::where('menu_type', 'menu_02')->get();
        $footerLinks = FooterMenus::where('menu_type', 'footer_menu')->get();
        return response()->json([
            'footerContent' => $footerContents,
            'socialLinks' => $socialLinks,
            'menu01Links' => $menu01Links,
            'menu02Links' => $menu02Links,
            'footerLinks' => $footerLinks,
        ]);
    }
}
