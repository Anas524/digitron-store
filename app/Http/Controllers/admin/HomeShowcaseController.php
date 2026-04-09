<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeShowcaseSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeShowcaseController extends Controller
{
    public function index()
    {
        $slides = HomeShowcaseSlide::latest()->get();
        return view('admin.home_showcase.index', compact('slides'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $path = $request->file('image')->store('home-showcase', 'public');

        HomeShowcaseSlide::create([
            'image_path' => $path,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Showcase poster uploaded successfully.');
    }

    public function update(Request $request, HomeShowcaseSlide $slide)
    {
        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($slide->image_path && Storage::disk('public')->exists($slide->image_path)) {
                Storage::disk('public')->delete($slide->image_path);
            }

            $slide->image_path = $request->file('image')->store('home-showcase', 'public');
        }

        $slide->is_active = $request->boolean('is_active');
        $slide->save();

        return back()->with('success', 'Showcase poster updated successfully.');
    }

    public function destroy(HomeShowcaseSlide $slide)
    {
        if ($slide->image_path && Storage::disk('public')->exists($slide->image_path)) {
            Storage::disk('public')->delete($slide->image_path);
        }

        $slide->delete();

        return back()->with('success', 'Showcase poster deleted successfully.');
    }
}