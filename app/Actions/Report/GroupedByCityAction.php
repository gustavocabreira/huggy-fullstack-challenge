<?php

namespace App\Actions\Report;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GroupedByCityAction
{
    public function execute(): Collection
    {
        return Contact::query()
                ->select('city', DB::raw('count(city) as count'), 'state')
                ->groupBy('city')
                ->orderBy('count', 'desc')
                ->get();
    }
}
