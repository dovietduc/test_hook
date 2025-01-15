<?php

namespace App\Http\Controllers;

use App\Jobs\ImportUsersJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\JandiLogger;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

// import model User (nếu ở namespace khác thì dùng App\Models\User,...)

class CsvImportController extends Controller
{
    // Hàm hiển thị form
    public function showForm()
    {
        echo "Hello D111";
        return view('import_csv');
    }

    // Hàm xử lý upload CSV và import
    public function import(Request $request)
    {

        // 1. Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt,doc'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. Lưu file vào storage/app/imports
        $file = $request->file('file');
        $path = $file->store('imports');

        // 3. Dispatch Job - đẩy vào hàng đợi
        ImportUsersJob::dispatch($path);

        // 4. Trả về ngay cho user
        return back()->with('success', 'File đã được upload. Hệ thống sẽ xử lý trong nền!');
    }

    public function callExternalApi()
    {
        Log::channel('jandi')->error('API call failed.', [
            'extra' => [
                'order_id' => 1,
                'user_id' => 1,
            ],
            'context' => [
                'endpoint' => 'https://api.example.com',
                'status' => 500,
            ]
        ]);
    }

}
