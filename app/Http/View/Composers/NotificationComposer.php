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
        // Siapkan variabel kosong sebagai default
        $unreadNotifications = [];

        // Periksa apakah ada pengguna yang sedang login
        if (Auth::check()) {
            // Jika ada, ambil 5 notifikasi terbaru yang belum dibaca
            $unreadNotifications = Auth::user()->unreadNotifications()->take(5)->get();
        }

        // Kirim variabel $unreadNotifications ke view yang menggunakan composer ini
        $view->with('unreadNotifications', $unreadNotifications);
    }
}
