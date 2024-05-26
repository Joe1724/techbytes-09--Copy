<?php
namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class TotalSalesChart extends ChartWidget
{
    protected static ?string $heading = 'Total Sales';

    protected function getData(): array
    {
       // Query to get the total sales per month
       $sales = Order::selectRaw('SUM(grand_total) as total, MONTH(created_at) as month')
       ->groupBy('month')
       ->get()
       ->pluck('total', 'month')
       ->toArray();

       // Preparing data for the chart
       $labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
       $data = array_fill(0, 12, 0);

       foreach ($sales as $month => $total) {
           $data[$month - 1] = $total;
       }

       return [
           'datasets' => [
               [
                   'label' => 'Total Sales',
                   'data' => $data,
                   'backgroundColor' => 'rgba(255, 99, 132, 0.2)', // Background color for the line
                   'borderColor' => 'rgba(255, 99, 132, 1)', // Color of the line
               ],
           ],
           'labels' => $labels,
       ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
