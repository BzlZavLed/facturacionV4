<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombreCliente', 'razonCliente', 'rfcCliente', 'emailCliente', 'usoCfdiCliente','personaFisicaCliente','bunit','DomicilioFiscalReceptor','RegimenFiscalReceptor'
      ];
}
