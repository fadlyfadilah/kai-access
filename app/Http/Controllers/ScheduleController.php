<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json') {
            $Schedules = Schedule::paginate(5)->toArray();

            if ($acceptHeader === 'application/json') {
                $response = [
                    "total_count" => $Schedules["total"],
                    "limit" => $Schedules["per_page"],
                    "pagination" => [
                        "next_page" => $Schedules["next_page_url"],
                        "current_page" => $Schedules["current_page"]
                    ],
                    "data" => $Schedules["data"],
                ];
                return response()->json($response, 200);
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function show($id)
    {
        $acceptHeader = request()->header('Accept');

        if ($acceptHeader === 'application/json') {
            $Schedule = Schedule::where(['id' => $id])->get();

            if (!$Schedule) {
                abort(404);
            }

            return response()->json($Schedule, 200);
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function store()
    {
        $acceptHeader = request()->header('Accept');

        if ($acceptHeader === 'application/json') {
            $contentTypeHeader = request()->header('Content-Type');

            if ($contentTypeHeader === 'multipart/form-data; boundary=<calculated when request is sent>') {
                $attr = request()->all();

                $Schedule = Schedule::create($attr);

                return response()->json($Schedule, 200);
            } else {
                return response('Unsupported Media Type', 415);
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function update($id, Request $request)
    {
        $acceptHeader = request()->header('Accept');

        if ($acceptHeader === 'application/json') {
            $contentTypeHeader = request()->header('Content-Type');

            if ($contentTypeHeader === 'application/x-www-form-urlencoded') {
                $input = $request->all();
                $Schedule = Schedule::find($id);

                if (!$Schedule) {
                    abort(404);
                }

                $Schedule->fill($input);
                $Schedule->save();

                return response()->json($Schedule, 200);
            } else {
                return response('Unsupported Media Type', 415);
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function destroy($id)
    {
        $acceptHeader = request()->header('Accept');

        if ($acceptHeader === 'application/json') {
            $Schedule = Schedule::where(['id' => $id])->firstOrFail();

            if (!$Schedule) {
                abort(404);
            }

            $Schedule->delete();

            $message = ['message' => 'delete successfully', 'Schedule_id' => $id];
            return response()->json($message, 200);
        } else {
            return response('Not Acceptable!', 406);
        }
    }
}
