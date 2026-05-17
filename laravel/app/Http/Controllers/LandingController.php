<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    private function content(): array
    {
        return config('landing');
    }

    public function index()
    {
        $content = $this->content();

        return view('landing', [
            'plans' => $content['plans'],
            'features' => $content['features'],
            'niches' => $content['niches'],
            'timeline' => $content['timeline'],
            'projects' => $content['projects'],
            'ticker' => $content['ticker'],
            'defaultPlan' => $content['plans']['core'],
            'supportEmail' => config('app.support_email', 'guildabyte@gmail.com'),
            'supportPhoneDisplay' => config('app.support_phone_display', '11 93377-3580'),
            'whatsappUrl' => 'https://wa.me/5511933773580',
        ]);
    }

    public function selectPlan(Request $request)
    {
        $request->validate(['plan' => ['required', 'string', 'in:start,core,custom']]);

        session(['selected_plan' => $request->plan]);

        return response()->json(['ok' => true, 'plan' => $request->plan]);
    }
}
