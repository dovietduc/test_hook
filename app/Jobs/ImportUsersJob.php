<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ImportUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath; // Đường dẫn file CSV trong storage

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Lấy đường dẫn tuyệt đối
        // storage_path('app') => thư mục storage/app
        $absolutePath = storage_path('app/' . $this->filePath);
       

        // Mở file
        if (!file_exists($absolutePath)) {
            // Nếu không tìm thấy file, có thể throw exception hoặc return
            return;
        }

        if (($handle = fopen($absolutePath, 'r')) !== false) {

            $chunkSize = 1000; // mỗi chunk 1000 dòng
            $insertData = [];
            $count = 0;
            $lineNumber = 0;

            while (($data = fgetcsv($handle, 0, ',')) !== false) {
                // Giả sử CSV có header ở dòng đầu
                if ($lineNumber === 0) {
                    $lineNumber++;
                    continue; // bỏ qua header
                }

                if (count($data) >= 3) {
                    $insertData[] = [
                        'name'            => $data[0],
                        'email'           => $data[1],
                        'password'        => Hash::make($data[2]),
                        'remember_token'  => Str::random(10),
                        'created_at'      => Carbon::now(),
                        'updated_at'      => Carbon::now(),
                    ];
                    $count++;
                }

                // Khi đã gom đủ chunkSize -> insert 1 lần
                if ($count >= $chunkSize) {
                    User::insert($insertData);
                    $insertData = [];
                    $count = 0;
                }

                $lineNumber++;
            }

            fclose($handle);

            // Insert nốt phần còn lại (nếu có)
            if (!empty($insertData)) {
                User::insert($insertData);
            }
        }

        // Nếu muốn, ta có thể gửi thông báo qua mail/log/v.v. rằng import đã hoàn tất
    }
}
