<?php
declare(strict_types=1);
namespace App\Reports;

use agoalofalife\Reports\Contracts\HandlerReport;
use agoalofalife\Reports\Report;
use agoalofalife\Reports\Contracts\NotificationReport;
use App\Shipment;

class ShipmentReport extends Report implements HandlerReport
{
    /**
     * Disk for filesystem
     * @var string
     */
    public $disk = 'public';

  /**
     * Format export : xls, xlsx, pdf, csv
     * @var string
     */
    public $extension = 'xlsx';

     /**
     * Get file name
     * @return string
     */
    public function getFilename() : string
    {
        return 'Shipment Reports';
    }

    /**
     * Get title report
     * @return string
     */
    public function getTitle() : string
    {
        return 'Shipment Reports';
    }

    /**
     * Get description report
     * @return string
     */
    public function getDescription() : string
    {
        return 'This Month Shipment Reports';
    }

    /**
     * @param $excel
     * @return bool
     */
    public function handler($excel) : bool
    {
        $excel->sheet('Sheetname', function ($sheet) {
            // $model =
            $sheet->fromModel(Shipment::all());

        });
    //   $excel->sheet('Sheetname', function ($sheet) {
    //         $sheet->rows(array(
    //             array('test1', 'test2'),
    //             array('test3', 'test4')
    //         ));
    //     });
      return true;
    }
}
