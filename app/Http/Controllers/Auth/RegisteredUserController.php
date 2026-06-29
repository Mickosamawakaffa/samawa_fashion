<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'user',
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Merge session cart to DB cart upon registration
        $sessionCart = session()->get('cart', []);
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $quantity) {
                $cartItem = \App\Models\Cart::where('user_id', $user->id)
                    ->where('product_id', $productId)
                    ->first();
                if ($cartItem) {
                    $cartItem->increment('quantity', $quantity);
                } else {
                    \App\Models\Cart::create([
                        'user_id' => $user->id,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                    ]);
                }
            }
            session()->forget('cart');
        }

        if ($user->isAdmin()) {
            return redirect(route('admin.dashboard'));
        }

        return redirect('/');
    }
}
