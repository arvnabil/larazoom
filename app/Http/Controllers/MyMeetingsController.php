<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyMeetingsController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $meetings = collect();
        $isZoomConnected = true; // Default true untuk guru atau jika tidak relevan

        if ($user->hasRole('teacher')) {
            // Guru: Ambil semua meeting dari mata pelajaran yang diajarkannya.
            $meetings = Meeting::whereHas('topicModel.chapter.subject', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })
            ->with(['topicModel.chapter.subject.teacher']) // Eager load relasi
            ->orderBy('start_time', 'desc')
            ->get();
        } elseif ($user->hasRole('student')) {
            // Cek apakah siswa sudah menghubungkan akun Zoom via OAuth
            $isZoomConnected = $user->zoomOauthToken()->exists();

            // Siswa: Ambil semua meeting dari mata pelajaran yang diikutinya.
            $enrolledSubjectIds = $user->enrolledSubjects->pluck('id');

            if ($enrolledSubjectIds->isNotEmpty()) {
                $meetings = Meeting::whereHas('topicModel.chapter.subject', function ($query) use ($enrolledSubjectIds) {
                    $query->whereIn('subjects.id', $enrolledSubjectIds);
                })
                ->with(['topicModel.chapter.subject.teacher']) // Eager load relasi
                ->orderBy('start_time', 'desc')
                ->get();
            }
        }

        return view('my-meetings.index', [
            'meetings' => $meetings,
            'isZoomConnected' => $isZoomConnected,
        ]);
    }
}

