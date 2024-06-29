<?php

namespace App\Traits;

use App\Observers\MyAuditingObs;

trait MyAutiting
{
    protected static function bootMyAutiting()
    {
        static::observe(new MyAuditingObs());
    }
}
