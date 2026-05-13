<?php

namespace App\Models;
// para sa mga kupal na mag babasa ng code ko 
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;// this is the one that makes tokens baka tanungin mopa kung ano ang tokens search mo sa laravel page nandun pre.

class Admin extends Model// dito naman diko alam basta may ginaya lang ako sa internet basta ti inbagana ni admin model ket agusar ti 
//toke bahala kay mang awaten  
{
    use HasApiTokens; // if this line of code is without here wala baka down na siguro yung system mo pre this can not work to create token 
    protected $table = 'admin';// this is the one how use table  admin  
    protected $fillable = [ // this to hide/agilemmeng not to incluide pasword and admin_id sa pag create ng admin 
        'admin_id',
        'password',
    ];

    protected $hidden = ['password'];
}