<?php

namespace App\Actions\Report;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GenerateGroupedByStateAction
{
    public function execute(): Collection
    {
        return Contact::query()
                ->select('state', DB::raw('count(state) as count'))
                ->groupBy('state')
                ->orderBy('count', 'desc')
                ->get();
    }
}
