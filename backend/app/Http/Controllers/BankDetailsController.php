<?php

namespace App\Http\Controllers;

use App\Models\BankDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BankDetailsController extends Controller
{
    protected $bankDetails;

    public function __construct(BankDetails $bankDetails)
    {
        $this->bankDetails = $bankDetails;
    }

    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $bankDetails = $this->bankDetails->all();
        return view('bank-details', ['bankDetails' => $bankDetails]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $validatedData = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'branch' => 'required|string|max:255',
            'branch_code' => 'required|string|max:50',
            'instructions' => 'nullable|string',
        ]);

        $validatedData['user_id'] = Auth::id();
        $this->bankDetails->create($validatedData);

        return redirect('/bank-details')->with('success', 'Bank details added successfully.');
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $validatedData = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'branch' => 'required|string|max:255',
            'branch_code' => 'required|string|max:50',
            'instructions' => 'nullable|string',
        ]);

        $bankDetail = $this->bankDetails->findOrFail($id);
        $bankDetail->update($validatedData);

        return redirect('/bank-details')->with('success', 'Bank details updated successfully.');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $bankDetail = $this->bankDetails->findOrFail($id);
        $bankDetail->delete();

        // ajax request support
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Bank details deleted successfully.']);
        }

        return redirect('/bank-details')->with('success', 'Bank details deleted successfully.');
    }
}
