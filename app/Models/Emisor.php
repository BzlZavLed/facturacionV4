<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emisor extends Model
{
    use HasFactory;
    protected $fillable = [
        'razon_emisor',
        'rfc_emisor', 
        'regimen_emisor',
         'c_postal', 
         'bunit', 
         'email_emisor',
         'zona',
         'versionDonataria',
         'leyendaDonataria',
         'fechaDonataria',
         'permisoDonataria',
         'numeroCertificado',
         'nombreColegio'
      ];
      protected $table = 'emisor';
}
