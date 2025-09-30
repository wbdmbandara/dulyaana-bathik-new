<?php

namespace App\Http\Controllers;

use App\Models\PromoCards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromoCardsController extends Controller
{

    public function index()
    {
        if(!Auth::check()){
            return redirect('/');
        }
        $allPromoCards = PromoCards::all();
        $promo = [];
        if($allPromoCards->isEmpty()){
            $promo = [
                'small-text' => 'Our Features',
                'promo-title' => 'Explore the Powerful Features',
                'promo-description' => 'Discover the amazing features that make our product stand out in the market.',
                'promo-link' => '#',
                'button-text' => 'Learn More',
                'promo-image' => 'images/promo-image.png',
            ];
        }
        $response['allPromoCards'] = $allPromoCards;
        $response['promo'] = $promo;

        return view('promo-cards', $response);
    }

    public function store(Request $request)
    {
        if(!Auth::check()){
            return redirect('/');
        }
        $lastID = PromoCards::max('id');
        if(is_null($lastID)) {
            $lastID = 0;
        }
        $promoCount = PromoCards::count();
        if($promoCount >= 4) {
            if($request->ajax()){
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum of 4 promo cards allowed.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Maximum of 4 promo cards allowed.');
        }

        $validated = $request->validate([
            'smallText' => 'required|string|max:255',
            'promoTitle' => 'required|string|max:255',
            'promoDescription' => 'required|string|max:1000',
            'promoLink' => 'required|url|max:255',
            'buttonText' => 'required|string|max:100',
        ]);

        $promo = new PromoCards();
        $promo->small_text = $validated['smallText'];
        $promo->promo_title = $validated['promoTitle'];
        $promo->promo_description = $validated['promoDescription'];
        $promo->promo_link = $validated['promoLink'];
        $promo->button_text = $validated['buttonText'];

        if($request->hasFile('promoImage')) {
            $image = $request->file('promoImage');
            $fileName = 'promo_' . ($lastID + 1) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/promo'), $fileName);
            $promo->promo_image = 'assets/promo/' . $fileName;
        }

        $promo->save();

        if($request->ajax()){
            return response()->json([
                'success' => true,
                'message' => 'Promo card created successfully.',
                'promo' => $promo
            ]);
        }
        return redirect()->back()->with('success', 'Promo card created successfully.');
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
        if(!Auth::check()){
            return redirect('/');
        }
        $promo = PromoCards::find($id);
        if ($promo) {
            return response()->json([
                'success' => true,
                'promoCard' => $promo
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Promo card not found.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(!Auth::check()){
            return redirect('/');
        }
        $promo = PromoCards::find($id);
        if ($promo) {
            $validated = $request->validate([
                'smallText' => 'required|string|max:255',
                'promoTitle' => 'required|string|max:255',
                'promoDescription' => 'required|string|max:1000',
                'promoLink' => 'required|url|max:255',
                'buttonText' => 'required|string|max:100',
            ]);

            $promo->small_text = $validated['smallText'];
            $promo->promo_title = $validated['promoTitle'];
            $promo->promo_description = $validated['promoDescription'];
            $promo->promo_link = $validated['promoLink'];
            $promo->button_text = $validated['buttonText'];

            if($request->hasFile('promoImage')) {
                $image = $request->file('promoImage');
                $fileName = 'promo_' . $id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/promo'), $fileName);
                $promo->promo_image = 'assets/promo/' . $fileName;
            }

            $promo->save();

            if($request->ajax()){
                return response()->json([
                    'success' => true,
                    'message' => 'Promo card updated successfully.',
                    'promoCard' => $promo
                ]);
            }
            return redirect()->back()->with('success', 'Promo card updated successfully.');
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Promo card not found.'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(!Auth::check()){
            return redirect('/');
        }
        $promo = PromoCards::find($id);
        if ($promo) {
            if ($promo->promo_image && file_exists(public_path($promo->promo_image))) {
                unlink(public_path($promo->promo_image));
            }
            $promo->delete();
            return response()->json([
                'success' => true,
                'message' => 'Promo card deleted successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Promo card not found.'
            ], 404);
        }
    }
}
