<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelExport implements FromCollection
{
    protected $data;
    protected $collectionData;

    public function __construct($data)
    {
        $this->data = $data;
//        dump($this->data = $data);
        $this->prepareCollection();
    }

    public function prepareCollection()
    {
        $params = $this->data;
        $arr[] = array_values($params['columns']);
        $columnKeys = array_keys($params['columns']);
        foreach ($params['data'] as $key => $task) {
            $row = [];
            foreach ($columnKeys as $columnKey) {
                if ($columnKey === 'status_name' && isset($task['status']['name'])) {
                    $row[$columnKey] = $task['status']['name'];
                } else {
                    if (isset($task[$columnKey])) {
                        $row[$columnKey] = trim($task[$columnKey]);
                    } else {
                        $row[$columnKey] = null;
                    }
                }

            }
            if(count($params['data'])-1>$key){
                $row['id'] = $key+1;
            }

            $arr[] = $row;

        }
        $this->collectionData = collect($arr);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->collectionData;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return array_values($this->data['columns']);
    }
}
