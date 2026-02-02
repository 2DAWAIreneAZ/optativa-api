<?php

namespace App\Http\Controllers;

use App\Models\Style;
use Illuminate\Http\Request;

class StyleController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $styles = Style::withCount('products')->get();
        return view('styles.index', ['styles' => $styles]);
    }

    public function create() {
        $this->authorize('create', Style::class);
        return view('styles.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Style::class);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:styles',
            'difficulty' => 'required|in:easy,medium,hard'
        ]);

        Style::create($validated);

        return redirect()->route('styles.index')
            ->with('success', 'Style created successfully!');
    }

    public function edit(Style $style)
    {
        $this->authorize('update', $style);
        return view('styles.edit', ['style' => $style]);
    }

    public function update(Request $request, Style $style)
    {
        $this->authorize('update', $style);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:styles,name,' . $style->id,
            'difficulty' => 'required|in:easy,medium,hard'
        ]);

        $style->update($validated);

        return redirect()->route('styles.index')
            ->with('success', 'Style updated successfully!');
    }

    public function destroy(Style $style)
    {
        $this->authorize('delete', $style);

        $style->delete();

        return redirect()->route('styles.index')
            ->with('success', 'Style deleted successfully!');
    }
}