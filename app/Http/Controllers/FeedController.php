<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        // Mendapatkan waktu saat ini
        $current_time = Carbon::now();

        // Mengatur jadwal relay
        $isRelayActive = false;
        $status = 'Relay tidak aktif';

        // Logika untuk mengaktifkan relay pukul 8 pagi dan 4 sore
        if ($current_time->format('H:i') == '08:00' || $current_time->format('H:i') == '16:00') {
            $isRelayActive = true;
            $status = 'Relay aktif memberikan makan ayam selama 2 menit';
        }

        return view('dashboard', compact('isRelayActive', 'status'));
    }
}
