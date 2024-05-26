<?php
namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Vite;

class PaymentMethodChart extends ChartWidget
{
    protected static ?string $heading = 'Payment Method';

    protected function getData(): array
    {
         // Query to get the count of orders based on payment methods
        $paymentMethods = Order::selectRaw('count(*) as total, payment_method')
        ->groupBy('payment_method')
        ->get()
        ->pluck('total', 'payment_method')
        ->toArray();

        // Preparing data for the chart
        $labels = array_keys($paymentMethods);
        $data = array_values($paymentMethods);

        // Define custom colors for the segments
        $colors = [ '#193b96', '#0e2154', '#4BC0C0', '#36A2EB', '#FF6384', '#FFCE56'];

        // Registering JavaScript asset
        FilamentAsset::register([
            Js::make('chart-js-plugins', Vite::asset('resources/js/filament-chart-js-plugins.js'))->module(),
        ]);

        return [
            'datasets' => [
                [
                    'label' => 'Number of Orders',
                    'data' => $data,
                    'backgroundColor' => $colors, // Set custom colors here
                ],
            ],
            'labels' => $labels,
            'options' => [ // Chart.js options
                'plugins' => [
                    'legend' => [
                        'position' => 'right',
                    ],
                ],
                'layout' => [
                    'padding' => [
                        'left' => 20,
                        'right' => 20,
                        'top' => 20,
                        'bottom' => 20,
                    ],
                ],
                'maintainAspectRatio' => false, // Setting to false allows changing the size
                'aspectRatio' => 1, // Adjust the aspect ratio to change the size of the pie chart
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
