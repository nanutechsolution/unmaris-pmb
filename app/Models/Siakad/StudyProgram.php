<?php

namespace App\Models\Siakad;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $connection = 'siakad'; 

    protected $table = 'study_programs';

    protected $guarded = ['id'];
}