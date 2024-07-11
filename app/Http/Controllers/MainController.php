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

    function getMySample()
    {
        return view('my-sample');
    }

    function getSample(Request $request)
    {
        $samples = [];
        $keyword = $request->search;
        $keywords = explode(' ', $keyword);

        if ($keyword != null) {
            $user = User::find(Auth::user()->id);

            $samples = Sample::where('is_selected', '=', true)
                ->where(function ($query) use ($keywords) {
                    foreach ($keywords as $word) {
                        $query->where(function ($subQuery) use ($word) {
                            $subQuery->where('sample_unique_code', 'like', '%' . $word . '%')
                                ->orWhere('sample_name', 'like', '%' . $word . '%')
                                ->orWhere('sample_address', 'like', '%' . $word . '%');
                        });
                    }
                });

            if ($user->hasRole('admin')) {
            } else if ($user->hasRole('pml')) {
                $samples->where('pml_id', '=', $user->id);
            }

            $samples = $samples->get();

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

    function getSampleFull(Request $request)
    {
        if (Auth::user() == null) {
            return abort(401);
        }

        $user = User::find(Auth::user()->id);
        $records = Sample::where(function ($query) {
            $query->where('is_selected', true)
                ->orWhere('type', 'Utama');
        });

        if ($user->hasRole('admin')) {
        } else if ($user->hasRole('pml')) {
            $records = $records->where(['pml_id' => $user->id]);
        } else {
            $records = $records->where(['pcl_id' => $user->id]);
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
                    Str::contains(strtolower($q->sample_address), strtolower($searchkeyword)) ||
                    Str::contains(strtolower($q->sample_unique_code), strtolower($searchkeyword));
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
            $sampleData["status"] = $sample->status;
            $sampleData["kbli"] = $sample->kbli;
            $sampleData["strata"] = $sample->strata;
            $sampleData["category"] = $sample->category;

            $sampleData["replacement_id"] = $sample->replacement_id;

            $sampleData['replacement_chain'] = $sample->getReplacementChain();

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

    function getSampleChangeStatus(Request $request)
    {
        if (Auth::user() == null) {
            return abort(401);
        }

        $user = User::find(Auth::user()->id);
        $records = Sample::where('type', '=', 'Utama')
            ->where('status', '=', 'Tidak Aktif');

        if ($user->hasRole('admin')) {
        } else if ($user->hasRole('pml')) {
            $records = $records->where(['pml_id' => $user->id]);
        } else {
            $records = $records->where(['pcl_id' => $user->id]);
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
                    Str::contains(strtolower($q->sample_address), strtolower($searchkeyword)) ||
                    Str::contains(strtolower($q->sample_unique_code), strtolower($searchkeyword));
            });
        }
        if ($request->status == 'no') {
            $samples = $samples->filter(function ($sample) {
                $lastSample = $sample->getLastReplacement();
                return $lastSample->replacement_id === null && $lastSample->status === 'Tidak Aktif';
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
            $sampleData["pcl"] = $sample->pcl->email;
            $sampleData["pml"] = $sample->pml->email;
            $sampleData["subdistrict_code"] = $sample->subdistrict_code;
            $sampleData["subdistrict_name"] = $sample->subdistrict_name;
            $sampleData["village_code"] = $sample->village_code;
            $sampleData["village_name"] = $sample->village_name;

            $sampleData['replacement_chain'] = $sample->getReplacementChain();
            // $sampleData["replacement_unique_code"] = $sample->replacement != null ? $sample->replacement->sample_unique_code : '';
            // $sampleData["replacement_name"] = $sample->replacement != null ? $sample->replacement->sample_name : '';
            // $sampleData["replacement_address"] = $sample->replacement != null ? $sample->replacement->sample_address : '';

            $samplesArray[] = $sampleData;
            $i++;
        }

        // Sample::where(['sample_unique_code' => ''])->first()->update([
        //     'village_code' => '',
        //     'village_name' => '',
        //     'bs_code' => ''
        // ])

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

    function getBackupSample(Request $request)
    {
        if (Auth::user() == null) {
            return abort(401);
        }

        $records = $records = Sample::where('type', '=', 'Cadangan');
        if ($request->kbli != null) {
            $records->where('kbli', 'LIKE', '%' . $request->kbli . '%');
        }
        if ($request->strata != null) {
            $records->where('strata', 'LIKE', '%' . $request->strata . '%');
        }
        if ($request->category != null) {
            $records->where('category', 'LIKE', '%' . $request->category . '%');
        }
        if ($request->subdistrict != null) {
            $subdistrict = $request->subdistrict;
            $records->where(function ($subQuery) use ($subdistrict) {
                $subQuery->where('subdistrict_code', 'like', '%' . $subdistrict . '%')
                    ->orWhere('subdistrict_name', 'like', '%' . $subdistrict . '%');
            });
        }
        if ($request->village != null) {
            $village = $request->village;
            $records->where(function ($subQuery) use ($village) {
                $subQuery->where('village_code', 'like', '%' . $village . '%')
                    ->orWhere('village_name', 'like', '%' . $village . '%');
            });
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
                    Str::contains(strtolower($q->sample_address), strtolower($searchkeyword)) ||
                    Str::contains(strtolower($q->sample_unique_code), strtolower($searchkeyword));
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
            $sampleData["subdistrict_code"] = $sample->subdistrict_code;
            $sampleData["subdistrict_name"] = $sample->subdistrict_name;
            $sampleData["village_code"] = $sample->village_code;
            $sampleData["village_name"] = $sample->village_name;
            $sampleData["type"] = $sample->type;
            $sampleData["replacement_id"] = $sample->replacement_id;
            $sampleData["strata"] = $sample->strata;
            $sampleData["kbli"] = $sample->kbli;
            $sampleData["category"] = $sample->category;
            $sampleData["job"] = $sample->job;
            $sampleData["is_selected"] = $sample->is_selected;
            $sampleData["available"] = count($sample->replacing) == 0;
            $sampleData["subdistrict_code"] = $sample->subdistrict_code;
            $sampleData["subdistrict_name"] = $sample->subdistrict_name;
            $sampleData["village_code"] = $sample->village_code;
            $sampleData["village_name"] = $sample->village_name;

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

    function changeSample(Request $request)
    {
        $replaced = Sample::find($request->replaced);
        if ($replaced->replacement != null) {
            //clear sampel cadangan yang sudah terpilih sebelumnya jika ada
            Sample::find($replaced->replacement->id)->update([
                'is_selected' => false,
                'pcl_id' => null,
                'pml_id' => null,
                'status' => null,
            ]);
        }
        $replacement = Sample::find($request->replacement);
        $replaced->update([
            'replacement_id' => $replacement->id
        ]);
        $replacement->update([
            'is_selected' => true,
            'pcl_id' => $replaced->pcl->id,
            'pml_id' => $replaced->pml->id,
        ]);

        return true;
    }

    function deleteChangeSample(Request $request)
    {
        $replacement = Sample::find($request->delete);
        $replaced = $replacement->replacing[0];

        $replacement->update([
            'is_selected' => false,
            'pcl_id' => null,
            'pml_id' => null,
            'status' => null,
        ]);

        $replaced->update([
            'replacement_id' => null
        ]);
    }
}
