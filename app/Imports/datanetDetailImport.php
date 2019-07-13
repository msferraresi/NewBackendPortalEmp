<?php

namespace arsatapi\Imports;

use arsatapi\datanetchange_detail;
use Maatwebsite\Excel\Concerns\ToModel;

class datanetDetailImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return new datanetchange_detail([
        //     'Dato1' => $row[0],
        //     'Dato2' => $row[1],
        //     'Dato3' => $row[2],
        //     'Dato4' => $row[3],
        //     'Dato5' => $row[4],
        //     'Dato6' => $row[5],
        //     'Dato7' => $row[6],
        //     'Dato8' => $row[7],
        // ]);
    }
}
