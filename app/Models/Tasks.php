<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = [
        'Title',
        'Description',
        'ProjectID',
        'CreaterID',
        'EstimatedDate',
        'EstimatedTime',
        'Priority',
        'CurrentStatus',
        'InitiallyAssignedToID',
        'CurrentlyAssignedToID',
        'CompletedDate',
        'CompletedTime',
        'ParentID',
    ];

    protected $appends = [
        'projectname',
        'name',
    ];
    public function getProjectnameAttribute()
    {
        //join projects table and get Description
        $projectname = DB::table('projects')
            ->select('Description')
            ->where('id', $this->ProjectID)
            ->get();
        return $projectname->first()->Description;
    }
    public function getNameAttribute()
    {
        //join users table and get name
        $name = DB::table('users')
            ->select('name')
            ->where('id', $this->CreaterID)
            ->get();
        return $name->first()->name;
    }
}
