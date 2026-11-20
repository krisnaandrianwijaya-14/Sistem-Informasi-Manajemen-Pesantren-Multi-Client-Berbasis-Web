<?php

namespace App\Models\Concerns;

use App\Modules\Platform\Support\ClientContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait untuk model yang datanya milik satu client.
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static void creating(\Closure|string $callback)
 */
trait BelongsToClient
{
    public static function bootBelongsToClient(): void
    {
        // Auto-filter saat BACA — hanya tampil data client aktif
        /** @var class-string<Model> $modelClass */
        $modelClass = static::class;
        $modelClass::addGlobalScope('client', function (Builder $builder): void {
            $client = app(ClientContext::class)->get();

            if ($client) {
                $builder->where(
                    $builder->qualifyColumn('client_id'),
                    $client->id
                );
            }
        });

        // Auto-fill client_id saat TULIS — tidak perlu isi manual
        static::creating(function (Model $model): void {
            $client = app(ClientContext::class)->get();

            if ($client && empty($model->getAttribute('client_id'))) {
                $model->setAttribute('client_id', $client->id);
            }
        });
    }
}
