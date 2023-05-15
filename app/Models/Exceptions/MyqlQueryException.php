<?php

namespace App\Models\Exceptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyqlQueryException extends Model
{
    use HasFactory;
    
    private array $exceptions = [
        1062 => [
            'label' => 'Atributo único duplicado'
        ]
    ];
    public function getByCode ( int $code ) 
    {
        return $this->exceptions[$code];
    }
}
