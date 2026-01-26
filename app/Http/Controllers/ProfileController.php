<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Valoration;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller {
    public function edit(Request $request): View {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse {
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

		public function show() {
        $user = Auth::user();
        
        // Para usuarios normales: obtener sus compras
        $purchases = Purchase::with('product')
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        
        // Para administradores: obtener productos en venta
        $productsForSale = $user->isAdmin() ? Product::with('style')->latest()->get() : collect();
        
        // Obtener valoraciones del usuario
        $valorations = Valoration::with('product')
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        
        return view('profile.show', compact('user', 'purchases', 'productsForSale', 'valorations'));
    }
}
