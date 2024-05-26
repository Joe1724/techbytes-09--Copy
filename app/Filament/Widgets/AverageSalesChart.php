<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AverageSalesChart extends ChartWidget
{
    protected static ?string $heading = 'Average Sales Chart';

    protected function getData(): array
    {
        $orders = Order::selectRaw('COUNT(*) as total, MONTH(created_at) as month')
        ->groupBy('month')
        ->get()
        ->pluck('total', 'month')
        ->toArray();

    $averageOrdersPerMonth = array_sum($orders) / count($orders);

    // Prepare data for the chart
    $data = array_fill(0, 12, $averageOrdersPerMonth);

    return [
        'datasets' => [
            [
                'label' => 'Average Sales per Month',
                'data' => $data,
            ],
        ],
        'labels' => [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ],
    ];
}

protected function getType(): string
{
    return 'line';
}

protected function getStats(): array
{
    $newOrdersCount = Order::query()->where('status', 'new')->count();
    $processingOrdersCount = Order::query()->where('status', 'processing')->count();
    $shippedOrdersCount = Order::query()->where('status', 'shipped')->count();
    $averageOrderPrice = Order::query()->avg('grand_total') ?? 0;

    return [
        Stat::make('New Orders', $newOrdersCount),
        Stat::make('Order Processing', $processingOrdersCount),
        Stat::make('Order Shipped', $shippedOrdersCount),
        Stat::make('Average Price', $this->formatCurrency($averageOrderPrice)),
    ];
}

private function formatCurrency($amount): string
{
    return number_format($amount, 2, '.', ',') . ' PHP';
    }
}
