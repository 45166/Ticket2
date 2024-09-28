<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        return view('devices.index', compact('devices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Devicename' => 'required',
            'DeviceType' => 'required',
        ]);

        $device = Device::create($request->all());
        return response()->json(['success' => 'Device added successfully!', 'device' => $device]);
    }
    public function edit($id)
    {
        $device = Device::find($id);
        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }
        return response()->json($device);
    }
    
    public function update(Request $request, $id)
    {
        $device = Device::find($id);
        if (!$device) {
            return response()->json(['message' => 'Device not found'], 404);
        }
    
        // Validate request data
        $request->validate([
            'Devicename' => 'required|string|max:255',
            'DeviceType' => 'required|string|max:255',
        ]);
    
        // Update device
        $device->Devicename = $request->Devicename;
        $device->DeviceType = $request->DeviceType;
        $device->save();
    
        return response()->json(['success' => 'Device updated successfully.']);
    }

    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        return response()->json(['success' => 'Device deleted successfully!']);
    }
}


