<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class InvoiceController extends Controller
{
    public function index()
    {
        try {
            // Authenticate user from JWT token
            $user = JWTAuth::parseToken()->authenticate();

            // Fetch only invoices that belong to the authenticated user
            $invoices = Invoice::where('user_id', $user->id)->with('items')->get();

            return response()->json($invoices);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0'
        ]);

        $totalAmount = collect($request->items)->sum(fn($item) => $item['quantity'] * $item['unit_price']);

        $invoice = Invoice::create([
            'user_id' => $request->user_id,
            'invoice_number' => $request->invoice_number,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'total_amount' => $totalAmount,
            'status' => 'pending'
        ]);

        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price']
            ]);
        }

        return response()->json($invoice->load('items'), 201);
    }

    public function show($id)
    {
        return response()->json(Invoice::with('items')->findOrFail($id));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update($request->only(['issue_date', 'due_date', 'status']));
        return response()->json($invoice);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted']);
    }
}
