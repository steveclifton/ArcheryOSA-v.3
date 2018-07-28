<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $table = 'rounds';
    protected $primaryKey = 'roundid';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);


    }

    public function getTypeName()
    {
        $typename = 'Outdoor';

        if ($this->type == 'i') {
            $typename = 'Indoor';
        }
        else if ($this->type == 'f') {
            $typename = 'Field';
        }
        else if ($this->type == 'c') {
            $typename = 'Clout';
        }

        return $typename;
    }

    public function getDistances()
    {
        $distances = [];
        if (!empty($this->dist1)) {
            $distances[] = $this->dist1;
        }
        if (!empty($this->dist2)) {
            $distances[] = $this->dist2;
        }
        if (!empty($this->dist3)) {
            $distances[] = $this->dist3;
        }
        if (!empty($this->dist4)) {
            $distances[] = $this->dist4;
        }

        return $distances;
    }


}
