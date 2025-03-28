<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;

class LeadsExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function view(): View
    {
        // Fetch the same data from your getData() method
        $leads = DB::table('leads')->select(
            'leads.id',
            'leads.name',
            'leads.email',
            'leads.phone',
            'leads.note',
            'leads.type',
            'leads.viewing_id',
            'leads.viewer_id',
            'leads.created_at',
            DB::raw('COALESCE(viewingProfile.username, "No Viewing") as viewing_username'),
            DB::raw('COALESCE(viewerProfile.username, "No Viewer") as viewer_username'),
        )
            ->leftJoin('profiles as viewingProfile', 'leads.viewing_id', '=', 'viewingProfile.id')
            ->leftJoin('profiles as viewerProfile', 'leads.viewer_id', '=', 'viewerProfile.id')
            ->leftJoin('users as viewingUser', 'viewingProfile.user_id', '=', 'viewingUser.id')
            ->where('viewingProfile.enterprise_id', auth()->id())
            ->orderBy('leads.created_at', 'desc')
            ->get();

        return view('exports.leads', compact('leads'));
    }
}

