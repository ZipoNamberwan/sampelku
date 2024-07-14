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

    // Recursive method to fetch the replacement chain
    public function getReplacementChain()
    {
        $chain = collect([$this]);

        $current = $this;
        while ($current->replacement) {
            $current = $current->replacement;
            $chain->push($current);
        }

        return $chain;
    }

    // public static function sortByLastReplacementModified()
    // {
    //     $samples = self::with('replacement')->get();
    
    //     $sortedSamples = $samples->sortBy(function ($sample) {
    //         return $sample->getLastReplacement()->modified;
    //     });
    
    //     return $sortedSamples;
    // }

    public function getLastReplacement()
    {
        $current = $this;

        while ($current->replacement) {
            $current = $current->replacement;
        }

        return $current;
    }
}
