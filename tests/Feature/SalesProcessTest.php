<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Member;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleLineItem;
use App\Models\Payment;

class SalesProcessTest extends TestCase
{
    use RefreshDatabase;

    public function testCompleteSalesProcess()
    {
        // Create a member
        $member = Member::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'member_since' => now()->subYear()
        ]);

        // Create items
        $item1 = Item::factory()->create(['name' => 'Widget', 'price' => 100]);
        $item2 = Item::factory()->create(['name' => 'Gadget', 'price' => 200]);

        // Create a sale
        $sale = Sale::create([
            'member_id' => $member->id,
            'total_price' => 0, // Will be calculated
            'status' => 'pending'
        ]);

        // Add items to the sale as line items
        $lineItem1 = SaleLineItem::create([
            'sale_id' => $sale->id,
            'item_id' => $item1->id,
            'quantity' => 2,
            'price' => $item1->price,
            'subtotal' => 2 * $item1->price
        ]);

        $lineItem2 = SaleLineItem::create([
            'sale_id' => $sale->id,
            'item_id' => $item2->id,
            'quantity' => 1,
            'price' => $item2->price,
            'subtotal' => 1 * $item2->price
        ]);

        // Calculate and set total price of the sale
        $totalPrice = $lineItem1->subtotal + $lineItem2->subtotal;
        $sale->update(['total_price' => $totalPrice]);

        // Create a payment for the sale
        $payment = Payment::create([
            'sale_id' => $sale->id,
            'amount' => $totalPrice,
            'payment_method' => 'credit_card',
            'payment_date' => now()
        ]);

        // Assertions to verify the process
        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'member_id' => $member->id,
            'total_price' => $totalPrice
        ]);

        $this->assertDatabaseHas('sale_line_items', [
            'sale_id' => $sale->id,
            'item_id' => $item1->id,
            'quantity' => 2
        ]);

        $this->assertDatabaseHas('payments', [
            'sale_id' => $sale->id,
            'amount' => $totalPrice
        ]);

    }
}
