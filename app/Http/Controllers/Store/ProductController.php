<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        // Temporary: until DB setup
        $productName = ucwords(str_replace('-', ' ', $slug));

        return view('pages.product', [
            'title' => $productName . ' - Digitron',
            'productName' => $productName
        ]);
    }
}
