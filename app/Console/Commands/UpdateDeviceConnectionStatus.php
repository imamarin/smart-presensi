<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;
use Carbon\Carbon;

class UpdateDeviceConnectionStatus extends Command
{
    protected $signature = 'device:update-connection-status';
    protected $description = 'Update device status based on last ping time';

    public function handle()
    {
        $threshold = Carbon::now()->subMinutes(10);

        // Set devices yang koneksi lama jadi tidak aktif
        Device::where('status_koneksi', '<', $threshold)
            ->update(['is_active' => 0]);

        // Set devices yang baru kirim ping jadi aktif
        Device::where('status_koneksi', '>=', $threshold)
            ->update(['is_active' => 1]);

        $this->info('Device connection status updated.');
    }
}
