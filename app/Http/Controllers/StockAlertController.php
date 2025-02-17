<?php

namespace App\Http\Controllers;

use App\Models\StockAlert;
use Illuminate\Http\Request;

class StockAlertController extends Controller
{
    public function index()
    {
        return response()->json(StockAlert::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
        ]);

        $alert = StockAlert::create($validated);
        return response()->json($alert, 201);
    }
}
