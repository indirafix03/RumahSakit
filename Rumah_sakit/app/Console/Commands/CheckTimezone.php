<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class CheckTimezone extends Command
{
    protected $signature = 'check:timezone';
    protected $description = 'Check timezone and appointment data';

    public function handle()
    {
        $this->info('=== TIMEZONE DEBUG INFO ===');
        $this->info('PHP Timezone: ' . date_default_timezone_get());
        $this->info('Laravel Config Timezone: ' . config('app.timezone'));
        $this->info('Current Time (UTC): ' . now()->format('Y-m-d H:i:s'));
        $this->info('Current Time (Jakarta): ' . now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'));
        $this->info('Today Date (Jakarta): ' . now()->timezone('Asia/Jakarta')->format('Y-m-d'));
        
        $this->info('');
        $this->info('=== TODAY APPOINTMENTS ===');
        
        $today = now()->timezone('Asia/Jakarta')->format('Y-m-d');
        $appointments = Appointment::with(['pasien', 'dokter'])
            ->whereDate('tanggal_booking', $today)
            ->get();
            
        if ($appointments->count() === 0) {
            $this->warn('No appointments found for today: ' . $today);
            
            // Show all appointments for debugging
            $this->info('');
            $this->info('=== ALL APPOINTMENTS ===');
            $allAppointments = Appointment::with(['pasien', 'dokter'])
                ->orderBy('tanggal_booking', 'desc')
                ->take(10)
                ->get();
                
            $this->table(
                ['ID', 'Pasien', 'Dokter', 'Tanggal', 'Status'],
                $allAppointments->map(function($appt) {
                    return [
                        $appt->id,
                        $appt->pasien->name,
                        $appt->dokter->name,
                        $appt->tanggal_booking->format('Y-m-d'),
                        $appt->status
                    ];
                })->toArray()
            );
        } else {
            $this->table(
                ['ID', 'Pasien', 'Dokter', 'Tanggal', 'Status'],
                $appointments->map(function($appt) {
                    return [
                        $appt->id,
                        $appt->pasien->name,
                        $appt->dokter->name,
                        $appt->tanggal_booking->format('Y-m-d'),
                        $appt->status
                    ];
                })->toArray()
            );
        }
        
        return Command::SUCCESS;
    }
}