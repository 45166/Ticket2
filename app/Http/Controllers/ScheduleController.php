<?php

namespace App\Http\Controllers;

use App\Models\Schedule; 
use App\Models\RepairRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('schedule.index');
    }

    public function create(Request $request)
    {
        $item = new Schedule();
        $item->user_id = Auth::id();
        $item->title = $request->title;
        $item->start = Carbon::parse($request->start)->setTimezone('Asia/Bangkok');
        $item->end = Carbon::parse($request->end)->setTimezone('Asia/Bangkok');
        $item->description = $request->description;
        $item->color = $request->color;
        $item->save();
    
        return redirect('/fullcalender');
    }

    public function showAddScheduleForm($ticketID)
    {
        $ticket = RepairRequest::where('TicketID', $ticketID)->first();
        return view('schedule.add', compact('ticket'));
    }

    public function getEvents()
    {
        // ส่งข้อมูลในรูปแบบ JSON ที่ FullCalendar ต้องการ
        $schedules = Schedule::select('id', 'title', 'start', 'end', 'color')->get();
        return response()->json($schedules);
    }

    public function deleteEvent($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'start' => Carbon::parse($request->input('start_date'))->setTimezone('Asia/Bangkok'),
            'end' => Carbon::parse($request->input('end_date'))->setTimezone('Asia/Bangkok'),
        ]);
        return response()->json(['message' => 'Event moved successfully']);
    }

    public function resize(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $newEndDate = Carbon::parse($request->input('end_date'))->setTimezone('Asia/Bangkok');
        $schedule->update(['end' => $newEndDate]);
        return response()->json(['message' => 'Event resized successfully.']);
    }
}
