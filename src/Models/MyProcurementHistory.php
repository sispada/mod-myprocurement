<?php

namespace Module\MyProcurement\Models;

use Illuminate\Database\Eloquent\Builder;

class MyProcurementHistory extends MyProcurementAuction
{
    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['myprocurement-history'];

    /**
     * booted function
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope('onlyFinished', function (Builder $query) {
            $query->whereIn('status', ['COMPLETED', 'ABORTED']);
        });
    }
}
