<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'samples';

    public function pcl()
    {
        return $this->belongsTo(User::class, 'pcl_id');
    }

    public function pml()
    {
        return $this->belongsTo(User::class, 'pml_id');
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function replacement()
    {
        return $this->belongsTo(Sample::class, 'replacement_id');
    }

    public function replacing()
    {
        return $this->hasMany(Sample::class, 'replacement_id');
    }
}
