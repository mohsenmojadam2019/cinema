<?php

namespace App\Http\Controllers;

use App\Models\Order;

class AdminExportController extends Controller
{
    public function sales()
    {
        $orders = Order::with('user')->where('status', 'paid')->latest()->get();

        return response()->streamDownload(function () use ($orders): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['order', 'customer', 'amount', 'created_at']);
            foreach ($orders as $order) {
                fputcsv($output, [$order->code, $order->user?->name, $order->total, $order->created_at]);
            }
            fclose($output);
        }, 'sales-report.csv', ['Content-Type' => 'text/csv']);
    }
}
