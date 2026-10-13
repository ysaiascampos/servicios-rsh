<?php

namespace App\Models;

use Eloquent;

class QrScanCodigosInstituciones extends Eloquent
{
    protected $table = 'qr_scan_codigos_instituciones';

    protected $primaryKey = 'ID';

    public $timestamps = false;

}
