<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index() {
        $maintenance = DB::table('settings')->where('key', 'maintenance')->value('value') === 'on';
        return view('admin.settings.index', compact('maintenance'));
    }
    public function update(Request $request) {
        $request->validate([
            'maintenance' => 'required|in:on,off',
        ]);
        $current = DB::table('settings')->where('key', 'maintenance')->value('value');
        if ($request->maintenance === 'on' && $current !== 'on') {
            // Generate a secret for admin bypass
            $secret = Str::random(32);
            DB::table('settings')->updateOrInsert(['key' => 'maintenance_secret'], ['value' => $secret]);
            Artisan::call('down', [
                '--render' => 'errors::503',
                '--secret' => $secret,
            ]);
        } elseif ($request->maintenance === 'off' && $current !== 'off') {
            Artisan::call('up');
            DB::table('settings')->where('key', 'maintenance_secret')->delete();
        }
        DB::table('settings')->updateOrInsert(
            ['key' => 'maintenance'],
            ['value' => $request->maintenance]
        );
        return back()->with('status', 'Maintenance mode updated!');
    }
} 