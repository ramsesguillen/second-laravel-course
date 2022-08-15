<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $links = Link::where('user_id', $user->id)->get();

        return $links->map(function (Link $link) {
            $orders = Order::where('code', $link->code)->where('complete', 0)->get();
            return [
                'code' => $link->code,
                'count' => $orders->count(),
                'revenue' => $orders->sum(function (Order $order) {
                    return $order->ambassador_revenue;
                })
            ];
        });
    }

    public function rankings()
    {
        $ambassadors = User::ambassadors()->get();

        $rankings = $ambassadors->map(function (User $user) {
            return [
                'name' => $user->name,
                'revenue' => $user->revenue
            ];
        });

        return $rankings->sortByDesc('revenue')->values();
    }
}
