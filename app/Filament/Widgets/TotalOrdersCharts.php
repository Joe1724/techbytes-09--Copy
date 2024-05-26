<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalOrdersCharts extends ChartWidget
{
    protected static ?string $heading = 'Total Orders';

    protected function getData(): array
    {
        // Query to get the total number of orders per month
        $orders = Order::selectRaw('count(*) as total, MONTH(created_at) as month')
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Find the highest value and its corresponding month
        $maxTotal = max($orders);
        $maxMonth = array_search($maxTotal, $orders, true); // Use strict comparison

        // Preparing data for the chart
        $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $data = array_fill(0, 12, 0);

        foreach ($orders as $month => $total) {
            $data[$month - 1] = $total; // Adjust index to start from 0
        }

        // Assigning colors to the data points
        $colors = [];
        foreach ($data as $month => $total) {
            if ($month === $maxMonth - 1) {
                $colors[] = '#193b96'; // Unique color for the highest value
            } else {
                $colors[] = '#0e2154'; // Default color for other values
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Orders',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
