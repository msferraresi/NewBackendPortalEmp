<?php

namespace arsatapi\Exports;



use arsatapi\datanetchange_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class datanetDetailExport implements FromCollection
{

    /**
    * @return \Illuminate\Support\Collection
    */
    private $id;

    function __construct($pid)
    {
        // $this->id = $pid;
    }

    public function collection()
    {
        // $detail = DB::table('datanetchange_detail')
        // ->where('id_header', $this->id)->get();
        // return $detail;

        //return datanetchange_detail::all();
    }
}
