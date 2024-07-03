<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    function index()
    {
        return view('welcome');
    }

    function getSample(Request $request)
    {
        $samples = [];
        $keyword = $request->search;
        $keywords = explode(' ', $keyword);

        if ($keyword != null) {
            $user = User::find(Auth::user()->id);

            $samples = Sample::where('is_selected', '=', true)
                ->where('pml_id', '=', $user->id)
                ->where(function ($query) use ($keywords) {
                    foreach ($keywords as $word) {
                        $query->where(function ($subQuery) use ($word) {
                            $subQuery->where('sample_unique_code', 'like', '%' . $word . '%')
                                ->orWhere('sample_name', 'like', '%' . $word . '%')
                                ->orWhere('sample_address', 'like', '%' . $word . '%');
                        });
                    }
                })->get();
        }

        return json_encode($samples);
    }
}
