<?php

namespace App\Exports;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class Reports implements FromCollection
{
    public function collection()
    {
        return User::all();
    }
}