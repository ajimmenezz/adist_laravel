<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchInventory extends Controller
{
    public function index()
    {
        $title_content = $this->title_content('support.branch_inventory.index', [
            'title' => 'Censos'
        ]);

        $table_headers = [
            ['label' => '', 'classes' => ['all']],
            ['label' => __('Id'), 'classes' => ['never']],
            ['label' => __('Sucursal'), 'classes' => ['all align-middle']],
            ['label' => __('Atiende'), 'classes' => ['desktop align-middle']],
            ['label' => __('Estado'), 'classes' => ['all align-middle']],
            ['label' => __('Fecha de creación'), 'classes' => ['desktop align-middle']]
        ];

        return view('support.branch_inventory.index', [
            'title' => $title_content['title'],
            'subtitle' => $title_content['subtitle'],
            'breadcrumb' => $title_content['breadcrumb'],
            'table_headers' => $table_headers
        ]);
    }
}
