<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - Techbytes')]

class HomePage extends Component
{
    public function render()
    {
        $brands = Brand::where('is_active', 1)->get();
        $category = Category::where('is_active',1)->get();
        return view('livewire.home-page',[
            'brands' => $brands,
            'category' => $category,
        ]);

        //$rec = $brands->toArray();
        //var_dump($rec[0][]);
    }
}
