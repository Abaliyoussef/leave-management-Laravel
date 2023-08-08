<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'last_name' ,
        'first_name' ,
        'last_name_ar' ,
        'first_name_ar' ,
        'cin' ,
        'email' ,
        'num_de_som' ,
        'situation' ,
        'nationalite' ,
        'nationalite_ar' ,
        'genre' ,
        'role' ,
        'poste' ,
        'poste_ar' ,
        'date_naissance' ,
        'phone' ,
        'image' ,
        'score' ,
        'verifie' ,
        'user_active' ,
        'departement_id' ,
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        
        'password' ,
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function conges()
    {
   return $this->hasMany(Conge::class);
    }
    
    public function departement()
    {
     return $this->belongsTo(Departement::class);
    }  
}
