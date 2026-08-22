<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Setting::class);

        $settings = Setting::getSettingsByGroup('setting', 'system');
        // dd($settings);

        return Inertia::render('SuperAdmin/Setting/Index', [
            'settings' => $settings,
        ]);

    }

    public function update(Request $request)
    {
        Gate::authorize('viewAny', Setting::class);

        $request->validate([
            'title' => 'required',
        ]);

        $this->updateSettings($request);

        return redirect()->back()->with('success', __('Successfully updated!'));
    }

    private function updateSettings($request)
    {

        foreach ($request->all() as $key => $setting) {
            $exist = Setting::where('group', 'system')->where('type', 'setting')->where('key', $key)->first();
            if(!$exist){
                $exist = Setting::create([
                    'group' => 'system',
                    'type'  => 'setting',
                    'key'   => $key
                ]);
            }
            
            if ($exist) {
                if (in_array($key, ['logo', 'favicon'])) {
                    if ($key == 'logo' && $request->hasFile('logo')) {
                        $exist->updatePhoto($request->logo, 'settings/logo');
                        // $exist->update([
                        //     'value' =>
                        // ])
                    } elseif ($key == 'favicon' && $request->hasFile('favicon')) {
                        $exist->updatePhoto($request->favicon, 'settings/favicon');
                    }
                } else {
                    $exist->update([
                        'value' => $setting,
                    ]);
                }
            }
        }

        return true;
    }
}
