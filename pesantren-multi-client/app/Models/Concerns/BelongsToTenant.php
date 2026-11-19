<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BelongsToTenant implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Mengambil tenant_id dari user yang sedang login atau dari session
        $tenantId = Auth::user()->tenant_id ?? session('tenant_id');

        if ($tenantId) {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }
}