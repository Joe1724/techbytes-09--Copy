<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Categories Page - Techbytes')]

class CategoryPage extends Component
{
    public function render()
    {
        $category = Category::where('is_active', 1)->get();
        return view('livewire.category-page', [
            'categories' => $category,
        ]);
    }
}
