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
                ->select(
                    DB::raw('CASE WHEN city IS NULL THEN "Não informado" ELSE city END as city'),
                    DB::raw('count(city) as count'),
                    DB::raw('CASE WHEN state IS NULL THEN "Não informado" ELSE state END as state'),
                )
                ->groupBy('city', 'state')
                ->orderBy('count', 'desc')
                ->get();
    }
}
