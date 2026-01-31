<?php

namespace App\Http\Controllers;

use App\Models\FooterContents;
use Illuminate\Http\Request;

class ContactDetailsController extends Controller
{
    public function getContactDetails(Request $request)
    {
        $footerContents = FooterContents::first();
        return response()->json([
            'success' => true,
            'contactDetails' => $footerContents,
        ]);
    }
}
