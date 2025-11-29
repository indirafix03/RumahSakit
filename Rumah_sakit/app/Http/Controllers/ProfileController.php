<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Load relationships based on role
        if ($user->isDokter()) {
            $user->load('poli');
            
            // Load basic counts
            $user->loadCount([
                'appointmentsAsDokter',
                'schedules',
                'feedbacksAsDokter'
            ]);
            
            // Manual counts for appointments with correct column names
            $user->active_appointments_count = $user->appointmentsAsDokter()
                ->where('status', 'active')
                ->count();
                
            $user->completed_appointments_count = $user->appointmentsAsDokter()
                ->where('status', 'completed')
                ->count();
            
            // Count unique patients
            $user->patients_count = $user->appointmentsAsDokter()
                ->distinct('pasien_id')
                ->count('pasien_id');
                
            // Count today's appointments - using created_at if appointment_date doesn't exist
            $user->today_appointments_count = $user->appointmentsAsDokter()
                ->whereDate('created_at', today())
                ->count();

        } else if ($user->isPasien()) {
            // Load basic counts for pasien
            $user->loadCount([
                'appointmentsAsPasien',
                'feedbacksAsPasien'
            ]);
            
            // Manual counts for appointments with correct column names
            $user->upcoming_appointments_count = $user->appointmentsAsPasien()
                ->where('status', 'active')
                ->whereDate('created_at', '>=', today())
                ->count();
                
            $user->completed_appointments_count = $user->appointmentsAsPasien()
                ->where('status', 'completed')
                ->count();
            
            // Count unique doctors
            $user->doctors_count = $user->appointmentsAsPasien()
                ->distinct('dokter_id')
                ->count('dokter_id');
                
            // Count today's appointments
            $user->today_appointments_count = $user->appointmentsAsPasien()
                ->whereDate('created_at', today())
                ->count();
        }
        
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Update user data
        $user->fill($request->validated());

        // Reset email verification if email was changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update the user's password.
     */
    public function password(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('profile.edit')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Get recent activities for the user
     */
    public function getRecentActivities(Request $request)
    {
        $user = $request->user();
        $activities = [];

        if ($user->isDokter()) {
            $activities = $user->appointmentsAsDokter()
                ->with('pasien')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($appointment) {
                    return [
                        'type' => 'appointment',
                        'title' => 'Janji dengan ' . $appointment->pasien->name,
                        'date' => $appointment->created_at,
                        'status' => $appointment->status,
                        'icon' => 'fas fa-calendar-check'
                    ];
                });
        } else if ($user->isPasien()) {
            $activities = $user->appointmentsAsPasien()
                ->with('dokter')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($appointment) {
                    return [
                        'type' => 'appointment',
                        'title' => 'Janji dengan Dr. ' . $appointment->dokter->name,
                        'date' => $appointment->created_at,
                        'status' => $appointment->status,
                        'icon' => 'fas fa-calendar-check'
                    ];
                });
        }

        return $activities;
    }

    /**
     * Get appointment statistics for charts
     */
    public function getAppointmentStats(Request $request)
    {
        $user = $request->user();
        $stats = [];

        if ($user->isDokter()) {
            $stats = $user->appointmentsAsDokter()
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
        } else if ($user->isPasien()) {
            $stats = $user->appointmentsAsPasien()
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
        }

        return response()->json($stats);
    }
}