<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Display a listing of items
    public function index()
    {
        $items = Item::all();
        return response()->json($items);
    }

    // Store a new item
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            // 'type' => 'nullable|string', // If you have a type column
        ]);

        $item = Item::create($validatedData);
        return response()->json($item, 201);
    }

    // Display the specified item
    public function show($id)
    {
        $item = Item::findOrFail($id);
        return response()->json($item);
    }

    // Update the specified item
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            // 'type' => 'nullable|string', // If you have a type column
        ]);

        $item->update($validatedData);
        return response()->json($item);
    }

    // Remove the specified item
    public function destroy($id)
    {
        $item = Item::findOrFail($id); // This will automatically return a 404 if not found
        $item->delete();
        return response()->json(['message' => 'Item removed'], 200);
    }
}