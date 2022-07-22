<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoInterno extends Model
{
    use HasFactory;
    protected $fillable = [
        'claveProductoServicio', 'descripcionConcepto', 'cuentasContables', 'claveUnidadFacturacion','numeroIdent'];
}
