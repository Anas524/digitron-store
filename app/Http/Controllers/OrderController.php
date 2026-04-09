<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        $data = [
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
        ];

        if ($request->order_status === 'delivered' && $order->payment_method === 'cash_on_delivery') {
            $data['payment_status'] = 'paid';
        }

        $order->update($data);

        return redirect()->back()->with('success', 'Order updated successfully.');
    }
}
