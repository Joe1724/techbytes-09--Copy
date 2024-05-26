<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Doctrine\DBAL\Types\FloatType;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
       if(is_null(Order::query()->avg('grand_total'))){
        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count())
            ->description('All users from the database')
            ->descriptionIcon('heroicon-m-arrow-trendingup')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
            Stat::make('Order Processing', Order::query()->where('status', 'processing')->count())
            ,
            Stat::make('Order Shipped', Order::query()->where('status', 'shipped')->count()),
            Stat::make('Average Price', Number::currency(0, 'PHP'))

        ];
       }else{
        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count())
            ->description('New Orders')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
            Stat::make('Order Processing', Order::query()->where('status', 'processing')->count()),
            Stat::make('Order Shipped', Order::query()->where('status', 'shipped')->count()),
            Stat::make('Average Price', Number::currency(Order::query()->avg('grand_total'), 'PHP'))

        ];

       }

    }
}
