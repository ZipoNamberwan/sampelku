<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

            foreach ($samples as $sample) {
                $sample->replacement_name = $sample->replacement != null ? $sample->replacement->sample_name : null;
                $sample->replacement_unique_code = $sample->replacement != null ? $sample->replacement->sample_unique_code : null;

                foreach ($sample->replacing as $replacing) {
                    $replacing->sample_name = $replacing->sample_name;
                }
            }
        }

        return json_encode($samples);
    }

    function getSampleChangeStatus(Request $request)
    {
        if (Auth::user() == null) {
            return abort(401);
        }

        $user = User::find(Auth::user()->id);
        $records = null;
        if ($user->hasRole('admin')) {
            $records = Sample::where('is_selected', '=', true)
                ->where('status', '=', 'TIdak Aktif');
        } else if ($user->hasRole('pml')) {
            $records = Sample::where(['pml_id' => $user->id])
                ->where('is_selected', '=', true)
                ->where('status', '=', 'TIdak Aktif');
        } else {
            $records = Sample::where(['pcl_id' => $user->id])
                ->where('is_selected', '=', true)
                ->where('status', '=', 'TIdak Aktif');
        }

        $recordsTotal = $records->count();

        $orderColumn = 'modified_at';
        $orderDir = 'desc';
        if ($request->order != null) {
            if ($request->order[0]['dir'] == 'asc') {
                $orderDir = 'asc';
            } else {
                $orderDir = 'desc';
            }
            if ($request->order[0]['column'] == '0') {
                $orderColumn = 'sample_name';
            } else if ($request->order[0]['column'] == '1') {
                $orderColumn = 'replacement_id';
            }
        }

        $searchkeyword = $request->search['value'];
        $samples = $records->get();
        if ($searchkeyword != null) {
            $samples = $samples->filter(function ($q) use (
                $searchkeyword
            ) {
                return Str::contains(strtolower($q->sample_name), strtolower($searchkeyword)) ||
                    Str::contains(strtolower($q->sample_address), strtolower($searchkeyword));
            });
        }
        $recordsFiltered = $samples->count();

        if ($orderDir == 'asc') {
            $samples = $samples->sortBy($orderColumn);
        } else {
            $samples = $samples->sortByDesc($orderColumn);
        }

        if ($request->length != -1) {
            $samples = $samples->skip($request->start)
                ->take($request->length);
        }

        $samplesArray = array();
        $i = $request->start + 1;

        foreach ($samples as $sample) {
            $sampleData = array();
            $sampleData["index"] = $i;
            $sampleData["id"] = $sample->id;
            $sampleData["sample_unique_code"] = $sample->sample_unique_code;
            $sampleData["sample_name"] = $sample->sample_name;
            $sampleData["sample_address"] = $sample->sample_address;
            $sampleData["type"] = $sample->type;

            $sampleData["replacement_id"] = $sample->replacement_id;
            $sampleData["replacement_unique_code"] = $sample->replacement != null ? $sample->replacement->sample_unique_code : '';
            $sampleData["replacement_name"] = $sample->replacement != null ? $sample->replacement->sample_name : '';
            $sampleData["replacement_address"] = $sample->replacement != null ? $sample->replacement->sample_address : '';

            $samplesArray[] = $sampleData;
            $i++;
        }

        return json_encode([
            "draw" => $request->draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $samplesArray
        ]);
    }

    function setNotActive(Request $request)
    {
        $response = Sample::find($request->id)->update(['status' => $request->status]);
        return $response;
    }
}
