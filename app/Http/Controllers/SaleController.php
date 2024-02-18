<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleLineItem;
use App\Models\Member;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    // Display a listing of the sales.
    public function index()
    {
        $sales = Sale::all();
        return response()->json($sales);
    }

    // Store a newly created sale in the database.
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $sale = new Sale();
            $sale->date = now(); // Set the current time as the sale date
            // ... additional sale properties from the request
            $sale->save();

            // Handle adding line items to the sale
            foreach ($request->line_items as $lineItem) {
                $item = Item::find($lineItem['item_id']);
                if ($item) {
                    $saleLineItem = new SaleLineItem();
                    $saleLineItem->sale_id = $sale->id;
                    $saleLineItem->item_id = $item->id;
                    $saleLineItem->quantity = $lineItem['quantity'];
                    $saleLineItem->subtotal = $item->price * $lineItem['quantity'];
                    $saleLineItem->save();
                }
            }

            // If a member ID is given, apply the discount
            if ($request->has('member_id')) {
                $member = Member::find($request->member_id);
                if ($member) {
                    $sale->applyMemberDiscount($member); // This function needs to be defined in the Sale model
                }
            }

            $sale->save(); // Save the sale again if any updates were made
            DB::commit();
            return response()->json($sale, 201);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['error' => 'Error creating sale: ' . $e->getMessage()], 500);
        }
    }

    // Display the specified sale.
    public function show($id)
    {
        $sale = Sale::with('saleLineItems.item', 'member')->findOrFail($id);
        return response()->json($sale);
    }

    // Update the specified sale in the database.
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::findOrFail($id);
            // ... update sale properties from the request
            $sale->save();

            // Assuming you have logic here to update the sale line items

            DB::commit();
            return response()->json($sale);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['error' => 'Error updating sale: ' . $e->getMessage()], 500);
        }
    }

    // Remove the specified sale from the database.
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::findOrFail($id);
            $sale->saleLineItems()->delete(); // Delete related line items
            $sale->delete();

            DB::commit();
            return response()->json(['message' => 'Sale deleted successfully'], 200);
        } catch (\Throwable $e) {
            DB::rollback();
            return response()->json(['error' => 'Error deleting sale: ' . $e->getMessage()], 500);
        }
    }
}
