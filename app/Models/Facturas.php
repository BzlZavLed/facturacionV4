<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturas extends Model
{
    use HasFactory;
    protected $fillable = [
        'cadenaOriginalSAT',
        'cfdi', 
        'fechaTimbrado', 
        'noCertificadoCFDI',
         'noCertificadoSAT', 
         'qrCode', 
         'selloCFDI',
         'selloSAT',
         'uuid',
         'fondo',
         'bunit',
         'filepath'
      ];
}
