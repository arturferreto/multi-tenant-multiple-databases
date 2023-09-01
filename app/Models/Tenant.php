<?php

namespace App\Models;

use App\Models\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Tenant extends Model
{
    use HasFactory, SoftDeletes, MultiTenancy;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'landlord';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'database',
        'total_users',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_users' => 'integer',
    ];

    /**
     * Retrieve all tenants from the cache.
     */
    public static function allCached(): Collection
    {
        return Cache::rememberForever('tenants', function () {
            return Tenant::all('slug', 'database');
        });
    }
}
