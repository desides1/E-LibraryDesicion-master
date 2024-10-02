<?php

namespace App\Exports;

use App\Models\Publisher;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PublisherCollectionExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function sheets(): array
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'Penerbit');
        })->whereIn('id', function ($query) {
            $query->select('user_id')
                ->from('publishers')
                ->where('status', 'aktif');
        })->get();

        $userIds = $users->pluck('id')->toArray();

        $data = Publisher::with(['category', 'user'])
            ->whereIn('user_id', $userIds)
            ->where('status', 'Aktif')
            ->latest('publication_date')
            ->orderBy('category_id', 'asc')
            ->get();

        $sheets = [];

        foreach ($users as $user) {
            $userData = $data->where('user_id', $user->id);
            $sheets[] = new PublisherSheetCollectionExport($user, $userData);
        }


        return $sheets;
    }
}
