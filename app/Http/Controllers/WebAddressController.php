<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class WebAddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->get();
        return view('customer.address', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        $isFirst = !Address::where('user_id', Auth::id())->exists();

        Address::create([
            'user_id' => Auth::id(),
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'is_primary' => $isFirst ? true : false,
        ]);

        return redirect()->back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        $address->update($request->all());

        return redirect()->back()->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        return redirect()->back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function setPrimary($id)
    {
        $user = Auth::user();
        
        // Reset all
        Address::where('user_id', $user->id)->update(['is_primary' => false]);
        
        // Set new primary
        Address::where('user_id', $user->id)->where('id', $id)->update(['is_primary' => true]);

        return redirect()->back()->with('success', 'Alamat utama berhasil diubah.');
    }
}
