<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Statistic;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index() {
        $members = Member::query()
            ->where('is_active', true)
            ->latest()
            ->get();
        
            $statistics = Statistic::where('status', true)
                ->orderBy('sort_order')
                ->get();

        return Inertia::render('Frontend/AboutPage', [
            'members' => $members,
            'statistics' => $statistics
        ]);
    }

}
