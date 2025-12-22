<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'tbl_customer';
    protected $primaryKey = 'cid';
    protected $fillable = ['name', 'tel'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'cid_fk', 'cid');
    }
}
