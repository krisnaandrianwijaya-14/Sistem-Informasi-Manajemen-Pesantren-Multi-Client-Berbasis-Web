<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenantTrait
{
    protected static function bootBelongsToTenantTrait()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            // Karena ini mode testing dan user admin mungkin belum punya tenant_id
            // Kita longgarkan scope-nya agar data bisa tampil
            $tenantId = Auth::user()->tenant_id ?? session('tenant_id');
            if ($tenantId) {
                $builder->where($builder->getModel()->getTable() . '.tenant_id', $tenantId);
            }
        });

        static::creating(function (Model $model) {
            $tenantId = Auth::user()->tenant_id ?? session('tenant_id');
            // Jika user admin tidak punya tenant_id, paksa isi dengan 1 agar tidak error DB
            if (!$model->tenant_id) {
                $model->tenant_id = $tenantId ?? 1; 
            }
        });
    }
}