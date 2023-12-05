<?php

namespace App\View\Components\Generals;

use Illuminate\View\Component;

class Menu extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $menu;
    public function __construct()
    {
        $path = $this->getUrl();
        $this->menu = [
            [
                'label' => 'Almacén',
                'icon' => 'bi bi-box-seam',
                'active' => (isset($path[1]) && $path[1] == 'Almacen') ? true : false,
                'children' => [
                    [
                        'label' => 'Distribución',
                        'url' => route('warehouse.distribution.index'),
                        'active' => (isset($path[2]) && $path[2] == 'Distribucion') ? true : false
                    ]
                ]
            ],
            [
                'label' => 'Logística',
                'icon' => 'bi bi-truck',
                'active' => (isset($path[1]) && $path[1] == 'Logistica') ? true : false,
                'children' => [
                    [
                        'label' => 'Recolección',
                        'url' => route('logistic.pickup.index'),
                        'active' => (isset($path[2]) && $path[2] == 'Recoleccion') ? true : false
                    ],
                    [
                        'label' => 'Distribución',
                        'url' => route('logistic.distribution.index'),
                        'active' => (isset($path[2]) && $path[2] == 'Distribucion') ? true : false
                    ]
                ]
            ],
            [
                'label' => 'Soporte en Sitio',
                'icon' => 'bi bi-tools',
                'active' => (isset($path[1]) && $path[1] == 'Soporte-en-sitio') ? true : false,
                'children' => [
                    [
                        'label' => 'Censos',
                        'url' => route('support.branch_inventory.index'),
                        'active' => (isset($path[2]) && $path[2] == 'Censos') ? true : false
                    ]
                ]
            ]
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.generals.menu', ['menu' => $this->menu]);
    }

    public function getUrl()
    {
        $current = url()->current();
        $path = parse_url($current, PHP_URL_PATH);
        return explode("/", $path);
    }
}
