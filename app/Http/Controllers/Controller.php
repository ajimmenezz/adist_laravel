<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success(String $message = 'success', array $data = [])
    {
        return response()->json([
            'code' => 200,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function error($code = 500, String $message = 'error', array $data = [])
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }

    protected function title_content(String $section, array $data = [])
    {
        switch ($section) {
            case 'logistic.pickup.index':
                return [
                    'title' => 'Recolección',
                    'subtitle' => 'Equipos y accesorios del cliente',
                    'breadcrumb' => [
                        [
                            'label' => 'Logística',
                        ],
                        [
                            'label' => 'Recolección',
                            'url' => route('logistic.pickup.index')
                        ]
                    ]
                ];
                break;
            case 'support.branch_inventory.index':
                return [
                    'title' => $data['title'],
                    'subtitle' => 'Equipos y accesorios por sucursal',
                    'breadcrumb' => [
                        [
                            'label' => 'Soporte',
                        ],
                        [
                            'label' => 'Censos',
                            'url' => route('support.branch_inventory.index')
                        ]
                    ]
                ];
                break;
            case 'support.branch_inventory.one':
                return [
                    'title' => "Censo " . $data['branch'],
                    'subtitle' => 'Captura de inventario de sucursal',
                    'breadcrumb' => [
                        [
                            'label' => 'Soporte',
                        ],
                        [
                            'label' => 'Censos',
                            'url' => route('support.branch_inventory.index')
                        ],
                        [
                            'label' => $data['branch']
                        ]
                    ]
                ];
                break;
            case 'support.branch_inventory.area':
                return [
                    'title' => "Censo " . ucfirst(mb_strtolower($data['branch'])) . " - " . $data['area'] . " " . $data['point'],
                    'subtitle' => 'Captura de inventario de sucursal',
                    'breadcrumb' => [
                        [
                            'label' => 'Soporte',
                        ],
                        [
                            'label' => 'Censos',
                            'url' => route('support.branch_inventory.index')
                        ],
                        [
                            'label' => $data['branch'],
                            'url' => route('support.branch_inventory.one', $data['serviceId'])
                        ],
                        [
                            'label' => $data['area']
                        ]
                    ]
                ];
                break;
            default:
                return [
                    'title' => '',
                    'subtitle' => '',
                    'breadcrumb' => []
                ];
                break;
        }
    }
}
