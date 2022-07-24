<?php

namespace App\Export;

use App\Application;
use App\Interfaces\ExportInterface;

class WordExport implements ExportInterface
{
	private $file;
	public function export($params) {
		$application = $params['application'];
		if (class_exists('ZipArchive')) {
        	$zip = new \PhpOffice\PhpWord\Shared\ZipArchive();

			//This is the main document in a .docx file.
			$fileToModify = 'word/document.xml';

			$file = storage_path('sample_act_1.docx');
			$temp_file = storage_path('temp.docx');
			copy($file, $temp_file);

			if ($zip->open($temp_file) === TRUE) {
			    //Read contents into memory
			    $oldContents = $zip->getFromName($fileToModify);

			    $newContents = $oldContents;
			    //echo $newContents;
			    //Modify contents:
			    $newContents = str_replace('storagecompanyname', $application->car_title, $newContents);
                $newContents = str_replace('arrivedat', $application->formated_arrived_at, $newContents);
                $newContents = str_replace('storageaddress', $application->parking->address, $newContents);
                $newContents = str_replace('issuedat', $application->formated_issued_at, $newContents);
			    $newContents = str_replace('carmark', ($application->carMark->name ?? ''), $newContents);
			    $newContents = str_replace('carmodel', ($application->carModel->name ?? ''), $newContents);
			    $newContents = str_replace('vinplaceholder', $application->vin, $newContents);
			    $newContents = str_replace('yearplaceholder', $application->year, $newContents);
			    $newContents = str_replace('lisencenumber', $application->license_plate, $newContents);
			    $newContents = str_replace('acceptedby', $application->acceptedBy ? $application->acceptedBy->name : "", $newContents);


			    //Delete the old...
			    $zip->deleteName($fileToModify);
			    //Write the new...
			    $zip->addFromString($fileToModify, $newContents);
			    //And write back to the filesystem.
			    $return =$zip->close();
			    if ($return==TRUE){
			        return response()->download(storage_path('temp.docx'));
			    }
			} else {
			    echo 'failed';
			    die;
			}
		}

		return response()->stream(
			$callback,
			200,
			$headers
		);
	}
	public function message() {
		echo 'WordExport message';
	}
}
