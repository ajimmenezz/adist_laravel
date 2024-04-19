<?php

namespace App\Http\Controllers\Outsourcing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Old\Facturacion\Outsourcing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reports extends Controller
{
    public function weekPendingInvoices(Request $request)
    {
        $dates = $this->getDates();
        if ($request->has('f'))
            $dates = $this->getDates($request->f);


        $result = Outsourcing::getWeekPendingInvoices($dates);

        foreach ($result as $key => $value) {
            $result[$key]->XMLData = $this->setXMLValues($value->XML);
            $result[$key]->XML = env('URL_ADIST_LEGACY') . $value->XML;
            $result[$key]->PDF = env('URL_ADIST_LEGACY') . $value->PDF;
        }

        $excel = $this->exportExcel($result, $dates);
        echo "<h2>Reporte generado exitosamente</h2><br><a href='$excel' target='_blank'>Descargar Reporte</a>";

        // return response()->json([
        //     'message' => 'success',
        //     // 'data' => $result,
        //     'excel' => $excel
        // ]);
    }

    private function getDates($timestamp = null)
    {

        date_default_timezone_set('America/Mexico_City');
        $fechaInicio = strtotime(date('Y-m-d'));

        if (!is_null($timestamp))
            $fechaInicio = strtotime($timestamp);

        $monday = strtotime('last Monday', $fechaInicio);
        $wednesday = $monday + (2 * 24 * 3600) + (13 * 3600);
        $lastWednesday = $wednesday - (7 * 24 * 3600) + 1;
        return [
            'begin' => date('Y-m-d H:i:s', $lastWednesday),
            'end' => date('Y-m-d H:i:s', $wednesday)
        ];
    }

    private function setXMLValues($xml)
    {
        $xmlData = $this->extractXMLData($xml);
        $obj = new \stdClass();
        $obj->Version = (string) $xmlData->General['Version'];
        $obj->Fecha = (string) $xmlData->General['Fecha'];
        $obj->Serie = (string) $xmlData->General['Serie'];
        $obj->Folio = (string) $xmlData->General['Folio'];
        $obj->Moneda = (string) $xmlData->General['Moneda'];
        $obj->SubTotal = (string) $xmlData->General['SubTotal'];
        $obj->Total = (string) $xmlData->General['Total'];
        $obj->Emisor = new \stdClass();
        $obj->Emisor->Rfc = (string) $xmlData->Emisor['Rfc'];
        $obj->Emisor->Nombre = (string) $xmlData->Emisor['Nombre'];
        $obj->Emisor->RegimenFiscal = (string) $xmlData->Emisor['RegimenFiscal'];
        $obj->Receptor = new \stdClass();
        $obj->Receptor->Rfc = (string) $xmlData->Receptor['Rfc'];
        $obj->Receptor->Nombre = (string) $xmlData->Receptor['Nombre'];
        $obj->UUID = (string) $xmlData->TimbreFiscalDigital['UUID'];

        return $obj;
    }

    private function extractXMLData($xml)
    {
        $obj = new \stdClass();

        $xmlFile = file_get_contents(env('URL_ADIST_LEGACY') . $xml);
        $xmlData = simplexml_load_string($xmlFile);
        $xmlData->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
        $xmlData->registerXPathNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');

        $obj->General = $xmlData->xpath('//cfdi:Comprobante')[0];
        $obj->Emisor = $xmlData->xpath('//cfdi:Emisor')[0];
        $obj->Receptor = $xmlData->xpath('//cfdi:Receptor')[0];

        $conceptos = $xmlData->xpath('//cfdi:Conceptos/cfdi:Concepto');
        $obj->Conceptos = [];
        foreach ($conceptos as $concepto) {
            $obj->Conceptos[] = $concepto;
        }

        $impuestosT = $xmlData->xpath('//cfdi:Impuestos/cfdi:Traslados/cfdi:Traslado');
        $obj->ImpuestosTraslados = [];
        foreach ($impuestosT as $impuestoT) {
            $obj->ImpuestosTraslados[] = $impuestoT;
        }

        $impuestosR = $xmlData->xpath('//cfdi:Impuestos/cfdi:Retenciones/cfdi:Retencion');
        $obj->ImpuestosRetenciones = [];
        foreach ($impuestosR as $impuestoR) {
            $obj->ImpuestosRetenciones[] = $impuestoR;
        }

        $obj->TimbreFiscalDigital = $xmlData->xpath('//tfd:TimbreFiscalDigital')[0];

        return $obj;
    }

    private function exportExcel($data, $dates)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Folio SD');
        $sheet->setCellValue('B1', 'Ticket');
        $sheet->setCellValue('C1', 'Servicio');
        $sheet->setCellValue('D1', 'Tecnico');
        $sheet->setCellValue('E1', 'Estatus Servicio');
        $sheet->setCellValue('F1', 'Sucursal');
        $sheet->setCellValue('G1', 'Tipo Servicio');
        $sheet->setCellValue('H1', 'Vuelta');
        $sheet->setCellValue('I1', 'Fecha Vuelta');
        $sheet->setCellValue('J1', 'Estatus Vuelta');
        $sheet->setCellValue('K1', 'Monto');
        $sheet->setCellValue('L1', 'Viatico');
        $sheet->setCellValue('M1', 'Autorizado');

        $sheet->setCellValue('N1', 'Version');
        $sheet->setCellValue('O1', 'Fecha');
        $sheet->setCellValue('P1', 'Serie');
        $sheet->setCellValue('Q1', 'Folio');
        $sheet->setCellValue('R1', 'Moneda');
        $sheet->setCellValue('S1', 'SubTotal');
        $sheet->setCellValue('T1', 'Total');
        $sheet->setCellValue('U1', 'RFC Emisor');
        $sheet->setCellValue('V1', 'Nombre Emisor');
        $sheet->setCellValue('W1', 'Regimen Fiscal');
        $sheet->setCellValue('X1', 'RFC Receptor');
        $sheet->setCellValue('Y1', 'Nombre Receptor');
        $sheet->setCellValue('Z1', 'UUID');

        $row = 2;
        foreach ($data as $key => $value) {
            $sheet->setCellValue('A' . $row, $value->FolioSD);
            $sheet->setCellValue('B' . $row, $value->Ticket);
            $sheet->setCellValue('C' . $row, $value->Servicio);
            $sheet->setCellValue('D' . $row, $value->Tecnico);
            $sheet->setCellValue('E' . $row, $value->EstatusServicio);
            $sheet->setCellValue('F' . $row, $value->Sucursal);
            $sheet->setCellValue('G' . $row, $value->TipoServicio);
            $sheet->setCellValue('H' . $row, $value->Vuelta);
            $sheet->setCellValue('I' . $row, $value->FechaVuelta);
            $sheet->setCellValue('J' . $row, $value->EstatusVuelta);
            $sheet->setCellValue('K' . $row, $value->Monto);
            $sheet->setCellValue('L' . $row, $value->Viatico);
            $sheet->setCellValue('M' . $row, $value->Autorizado);

            $sheet->setCellValue('N' . $row, $value->XMLData->Version);
            $sheet->setCellValue('O' . $row, $value->XMLData->Fecha);
            $sheet->setCellValue('P' . $row, $value->XMLData->Serie);
            $sheet->setCellValue('Q' . $row, $value->XMLData->Folio);
            $sheet->setCellValue('R' . $row, $value->XMLData->Moneda);
            $sheet->setCellValue('S' . $row, $value->XMLData->SubTotal);
            $sheet->setCellValue('T' . $row, $value->XMLData->Total);
            $sheet->setCellValue('U' . $row, $value->XMLData->Emisor->Rfc);
            $sheet->setCellValue('V' . $row, $value->XMLData->Emisor->Nombre);
            $sheet->setCellValue('W' . $row, $value->XMLData->Emisor->RegimenFiscal);
            $sheet->setCellValue('X' . $row, $value->XMLData->Receptor->Rfc);
            $sheet->setCellValue('Y' . $row, $value->XMLData->Receptor->Nombre);
            $sheet->setCellValue('Z' . $row, $value->XMLData->UUID);
            $row++;
        }


        for ($l = 'A', $lMax = 'Z'; $l <= $lMax; $l++) {
            $sheet->getColumnDimension($l)->setAutoSize(true);
        }

        $filterRange = 'A1:Z1';
        $sheet->setAutoFilter($filterRange);

        $writer = new Xlsx($spreadsheet);

        if (!is_dir(public_path('/Reportes/Asociados')))
            mkdir(public_path('/Reportes/Asociados'), 0755, true);

        $dates['begin'] = str_replace(':', '', $dates['begin']);
        $dates['begin'] = str_replace(' ', '', $dates['begin']);
        $dates['end'] = str_replace(':', '', $dates['end']);
        $dates['end'] = str_replace(' ', '', $dates['end']);

        $writer->save(public_path("/Reportes/Asociados/FacturasPendientes_{$dates['begin']}_{$dates['end']}.xlsx"));

        return asset("/Reportes/Asociados/FacturasPendientes_{$dates['begin']}_{$dates['end']}.xlsx");
    }
}
