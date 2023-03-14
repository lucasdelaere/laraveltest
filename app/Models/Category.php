<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes; // not using softDeletes here would hard delete upon calling the '->delete()' method on an object of class 'Category'

    protected $fillable = ["name"];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function scopeFilter($query)
    {
        if (request("search")) {
            //dd($query)
            //verbinden met ons inputveld
            $query->where("name", "like", "%" . request("search") . "%");
        }
    }
}
