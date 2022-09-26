<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankidIntegration extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'label',
        'description',
        'active',
        'pkcs',
        'password',
        'type',
        'url_prefix',
        'success_url',
        'error_url',
        'environment',
        'layout',
        'languages',
        'extra_html',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'bankid_integrations';

    protected $hidden = ['password'];

    protected $casts = [
        'active' => 'boolean',
        'layout' => 'array',
        'languages' => 'array',
    ];

    public function allBankidRequests()
    {
        return $this->hasMany(BankidRequests::class);
    }
}
