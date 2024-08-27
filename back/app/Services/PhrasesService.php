<?php
/**
 * Created by PhpStorm.
 * User: alink
 * Date: 26.08.2024
 * Time: 16:37
 */

namespace App\Services;


use App\Models\Phrases;

class PhrasesService
{

   public function getSomePhrases($alias,$count)
   {
       $phrases = Phrases::query()
           ->where('alias', $alias)
           ->inRandomOrder()
           ->take($count)
           ->get()
           ->toArray()
       ;

       return $phrases;
   }
}