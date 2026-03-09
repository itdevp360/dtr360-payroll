<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirebaseHolidays extends Model
{
    use HasFactory;
    public $id;
    public $holidayDate;
    public $holidayType;

    public function __construct($id, $data)
    {
        $this->id = $id;
        $this->holidayDate       = $data['holidayDate'] ?? null;
        $this->holidayType       = $data['holidayType'] ?? null;
    }
}
