<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\MigranteParentesco
 *
 * @property int $PAREN_ID
 * @property string $PAREN_DESCN
 * @property int $ACTIVO
 *
 * @method static Builder|MigranteParentesco newModelQuery()
 * @method static Builder|MigranteParentesco newQuery()
 * @method static Builder|MigranteParentesco query()
 *
 * @method static Builder|MigranteParentesco wherePAREN_ID($value)
 * @method static Builder|MigranteParentesco wherePAREN_DESCN($value)
 * @method static Builder|MigranteParentesco whereACTIVO($value)
 *
 * @mixin Eloquent
 */
class MigranteParentesco extends Eloquent
{
    protected $table = 'MIGRANTE_PAREN';
    protected $primaryKey = 'PAREN_ID';
    public $timestamps = false;

    protected $fillable = [
        'PAREN_DESCN',
        'ACTIVO', 
    ];
}

