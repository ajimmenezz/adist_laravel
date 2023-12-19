<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\SAE8\Products;
use App\Models\SAE8\Warehouses;
use Illuminate\Http\Request;

class Inventory2023 extends Controller
{
    public function index()
    {
        return view('warehouse.inventory2023.index', [
            'title_content' => $this->title_content('warehouse.inventory2023.index'),
            'warehouses' => Warehouses::where('STATUS', 'A')
            ->whereIN('CVE_ALM', [1,4,10,101])
            ->orderBy('DESCR')->get(),
        ]);
    }
}
