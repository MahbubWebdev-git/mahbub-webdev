<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorData; // মডেলের নাম SensorData

class SensorController extends Controller
{
    // আরডুইনো থেকে ডেটা সেভ করার মেথড
    public function storeDeviceData(Request $request)
    {
        // আরডুইনোর পাঠানো ডেটা ভ্যালিডেশন
        $validatedData = $request->validate([
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'device_id' => 'required|string',
        ]);

        // ডাটাবেজে সংরক্ষণ
        $data = SensorData::create($validatedData);

        return response()->json(['message' => 'Data saved successfully', 'data' => $data], 201);
    }

    // রিয়্যাক্ট ফ্রন্টএন্ডের জন্য ডেটা পাঠানোর মেথড
    public function getLiveChartData()
    {
        // সর্বশেষ ১০ বা ২০টি ডেটা তুলে আনা চার্টের জন্য
        $chartData = SensorData::latest()->take(20)->get()->reverse()->values();

        return response()->json($chartData);
    }
}
