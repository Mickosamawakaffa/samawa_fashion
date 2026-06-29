<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();
        $orders = Order::where('user_id', $user->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.edit', compact('user', 'addresses', 'orders'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|confirmed|min:8|string',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Avatar Upload & Compression
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            
            // Intervention Image v3 usage
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->read($file);
            $image->scale(width: 300);
            
            $path = storage_path('app/public/avatars/' . $filename);
            if (!file_exists(storage_path('app/public/avatars'))) {
                mkdir(storage_path('app/public/avatars'), 0755, true);
            }
            $image->save($path);
            
            // Delete old avatar if exists
            if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))) {
                @unlink(storage_path('app/public/' . $user->avatar));
            }

            $user->avatar = 'avatars/' . $filename;
        }

        // Change Password if provided
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return Redirect::back()->withErrors(['current_password' => 'Password lama tidak sesuai'])->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // --- Addresses Book CRUD ---

    public function storeAddress(Request $request): RedirectResponse
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        $user = $request->user();
        $isFirst = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'address_line' => $request->address_line,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'is_default' => $isFirst,
        ]);

        return Redirect::route('profile.edit', ['tab' => 'alamat'])->with('success', 'Alamat berhasil ditambahkan');
    }

    public function updateAddress(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        $address = $request->user()->addresses()->findOrFail($id);
        $address->update($request->only('recipient_name', 'phone', 'address_line', 'city', 'province', 'postal_code'));

        return Redirect::route('profile.edit', ['tab' => 'alamat'])->with('success', 'Alamat berhasil diperbarui');
    }

    public function destroyAddress(Request $request, $id): RedirectResponse
    {
        $address = $request->user()->addresses()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $nextDefault = $request->user()->addresses()->first();
            if ($nextDefault) {
                $nextDefault->update(['is_default' => true]);
            }
        }

        return Redirect::route('profile.edit', ['tab' => 'alamat'])->with('success', 'Alamat berhasil dihapus');
    }

    public function setDefaultAddress(Request $request, $id): RedirectResponse
    {
        $request->user()->addresses()->update(['is_default' => false]);
        $address = $request->user()->addresses()->findOrFail($id);
        $address->update(['is_default' => true]);

        return Redirect::route('profile.edit', ['tab' => 'alamat'])->with('success', 'Alamat default berhasil diubah');
    }
}
