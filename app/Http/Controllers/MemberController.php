<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Inertia\Inertia;

class MemberController extends Controller
{
    public function teamMembers() {
        $members = Member::query()
            ->where('is_active', true)
            ->latest()
            ->paginate(6);

        return Inertia::render('Frontend/TeamMemberPage', [
            'members' => $members
        ]);
    }

    public function loadMore() {
        $members = Member::where('is_active', true)
            ->paginate(6);

        return response()->json([
            'members' => $members->items(),
            'next_page' => $members->currentPage() < $members->lastPage() 
                ? $members->currentPage + 1
                :null
                    
        ]);
    }
}
