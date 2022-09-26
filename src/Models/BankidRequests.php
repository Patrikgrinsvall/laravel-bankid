<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankidRequests extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'response',
        'orderRef',
        'bankid_integration_id',
        'status',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'bankid_requests';

    public function bankidIntegration()
    {
        return $this->belongsTo(BankidIntegration::class);
    }
}
