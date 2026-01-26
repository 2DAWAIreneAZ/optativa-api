<?php

namespace App\Http\Controllers;

use App\Models\Valoration;
use Illuminate\Http\Request;

class ValorationController extends Controller
{
    public function destroy(Valoration $valoration)
    {
        // Solo el dueño o admin puede eliminar
        if (auth()->id() !== $valoration->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $valoration->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
}
