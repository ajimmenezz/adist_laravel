<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Notifications\Telegram\SimpleMessage;
use App\Notifications\Telegram\FileMessage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

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

    protected function errorResponse(String $message = 'error', $e = null)
    {
        if (env('APP_ENV') != 'production') {
            return response()->json([
                'message' => $message,
                'error' => $e->getMessage() ?? null,
                'line' => $e->getLine() ?? null,
                'file' => $e->getFile() ?? null,
                'trace' => $e->getTrace() ?? null
            ], 500);
        } else {
            return response()->json([
                'message' => $message
            ], 500);
        }
    }

    protected function title_content(String $section, array $data = [])
    {
        switch ($section) {
            case 'logistic.distribution.index':
                return [
                    'title' => 'Distribución de Equipos',
                    'subtitle' => 'Destinos de equipos por sucursal y proyecto',
                    'breadcrumb' => [
                        [
                            'label' => 'Logística',
                        ],
                        [
                            'label' => 'Distribuciones',
                            'url' => route('logistic.distribution.index')
                        ]
                    ]
                ];
                break;
            case 'warehouse.distribution.one':
                return [
                    'title' => $data['project'],
                    'subtitle' => 'Proyecto de distribución' . ' - ' . $data['customer'],
                    'breadcrumb' => [
                        [
                            'label' => 'Almacén',
                        ],
                        [
                            'label' => 'Distribuciones',
                            'url' => route('warehouse.distribution.index')
                        ],
                        [
                            'label' => $data['project']
                        ]
                    ]
                ];
                break;
            case 'warehouse.distribution.index':
                return [
                    'title' => 'Distribución',
                    'subtitle' => 'Distribución de equipos a sucursales',
                    'breadcrumb' => [
                        [
                            'label' => 'Almacén',
                        ],
                        [
                            'label' => 'Distribuciones',
                            'url' => route('warehouse.distribution.index')
                        ]
                    ]
                ];
                break;
            case 'logistic.pickup.one':
                return [
                    'title' => $data['branch'],
                    'subtitle' => 'Recolección de equipos y accesorios del cliente',
                    'breadcrumb' => [
                        [
                            'label' => __('Logística'),
                        ],
                        [
                            'label' => __('Recolecciones'),
                            'url' => route('logistic.pickup.index')
                        ],
                        [
                            'label' => $data['branch']
                        ]
                    ],
                ];
                break;
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

    protected function sendTelegramMessage($chatId, String $message)
    {
        try {
            $notification =  new SimpleMessage(
                $chatId,
                $message
            );

            Notification::send($notification, $notification);
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error al enviar el mensaje de Telegram, " . $e->getMessage());
        }
    }

    protected function sendTelegramFile($chatId, String $message = "", String $file, String $file_name)
    {
        try {
            $notification =  new FileMessage(
                $chatId,
                $message,
                $file,
                $file_name
            );

            Notification::send($notification, $notification);
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error al enviar el archivo en Telegram, " . $e->getMessage());
        }
    }

    public static function toRawSql($query)
    {
        return Str::replaceArray('?', $query->getBindings(), $query->toSql());
    }
}
