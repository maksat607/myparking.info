<?php

namespace App\Export;

use App\Interfaces\ExportInterface;

class CsvExport implements ExportInterface
{
	private $file;
	public function export($params) {
		// echo 'CsvExport';
		// die;

		$fileName = 'report.csv';

		$headers = array(
			"Content-type"        => "text/csv; charset=utf-8",
			"Content-Disposition" => "attachment; filename=$fileName",
			"Pragma"              => "no-cache",
			"Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
			"Expires"             => "0"
		);

		$callback = function() use ($params) {
            $file = fopen('php://output', 'w');
            print "\xEF\xBB\xBF";

            fputcsv($file, $params['columns']);
            $columnKeys = array_keys($params['columns']);
            foreach ($params['data'] as $task) {
				$row = [];
				foreach ($columnKeys as $columnKey) {
					if ($columnKey === 'status_name' && isset($task['status']['name'])) {
						$row[$columnKey] = $task['status']['name'];
					}
					else {
						$row[$columnKey] = trim($task[$columnKey]);
					}
				}

				fputcsv($file, $row);
			}
            fclose($file);
        };

		return response()->stream(
			$callback,
			200,
			$headers
		);
	}
}
