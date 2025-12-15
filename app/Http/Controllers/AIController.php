<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketDtl;

class AIController extends Controller
{
    public function ticketVolumeForecast()
    {
        // Get ticket counts per day for the last 30 days
        $tickets = TicketDtl::selectRaw('DATE(created_at) as day, count(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $data = $tickets->pluck('count')->toArray();

        // Simple moving average forecast for next 7 days
        $window = 7;
        $forecast = [];
        for ($i = 0; $i < 7; $i++) {
            $sum = array_sum(array_slice($data, -$window));
            $next = round($sum / $window);
            $forecast[] = $next;
            $data[] = $next; // roll forward
        }

        return view('forecast.ticket_volume', [
            'tickets' => $tickets,
            'forecast' => $forecast
        ]);
    }
}
