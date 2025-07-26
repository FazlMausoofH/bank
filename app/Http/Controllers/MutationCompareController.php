<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Centrall;
use Illuminate\Http\Request;

class MutationCompareController extends Controller
{
    public function index()
    {
        $centralls = Centrall::all();
        $banks = Bank::all();

        $matches = [];

        foreach ($centralls as $c) {
            foreach ($banks as $b) {
                if (
                    $c->date == $b->date &&
                    $c->amount == $b->amount &&
                    $c->account_holder == $b->account_holder
                ) {
                    $matches[] = [
                        'centrall' => $c,
                        'bank' => $b,
                    ];
                }
            }
        }

        return view('compare', compact('centralls', 'banks', 'matches'));
    }
}
