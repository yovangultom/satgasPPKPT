<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $unreadNotifications = [];

        if (Auth::check()) {
            $unreadNotifications = Auth::user()->unreadNotifications()->take(5)->get();
        }

        $view->with('unreadNotifications', $unreadNotifications);
    }
}
