<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Str;

class SubscribeController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->activeTheme = active_theme();
    }

    /**
     * Display the page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function mySubscription()
    {
        $user = request()->user();
        return view($this->activeTheme . '.user.subscription', compact('user'));
    }

    /**
     * Cancel recurring subscription
     *
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function cancelSubscription()
    {

        try {
            request()->user()->cancelRecurringSubscription();
        } catch (\Exception $e) {

            Log::info($e->getMessage());
            quick_alert_error($e->getMessage());
            return back();
        }

        quick_alert_success(___('Cancelled Successfully'));
        return back();
    }
}
