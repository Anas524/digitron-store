<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'tax' => Setting::get('tax_percent', 5),
            'shipping_type' => Setting::get('shipping_type', 'free'),
            'flat_rate' => Setting::get('shipping_flat_rate', 0),
            'free_min' => Setting::get('free_shipping_min', 0),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'tax_percent' => 'required|numeric|min:0|max:100',
            'shipping_type' => 'required|in:free,flat,conditional',
            'shipping_flat_rate' => 'nullable|numeric|min:0',
            'free_shipping_min' => 'nullable|numeric|min:0',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // clear cache so changes apply instantly
        cache()->flush();

        return back()->with('success', 'Settings updated successfully.');
    }
}
