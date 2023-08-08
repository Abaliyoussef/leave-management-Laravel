<?php

namespace App\Http\Controllers\Providers;

use App\Http\Controllers\Controller;



class FamilialeState extends Controller
{
    public static function getFamilialeStates()
    {
        
        $situationsFamiliales = [
            "Célibataire",
            "Marié(e)",
            "Divorcé(e)",
            "Veuf/Veuve",
        ];
        

        return $situationsFamiliales;
    }
}
  