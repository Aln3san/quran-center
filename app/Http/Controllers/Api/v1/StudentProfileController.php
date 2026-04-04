<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\StudentProfile;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    use ResponseTrait;

    public function showProfile(Request $request, string $id)
    {
        // 1. هات البروفايل مع الر و المجموعات واتأكد إنه موجود
        $profile = StudentProfile::with(['user.dailyLogs.group', 'user.evaluations.group'])->find($id);
        if (!$profile) {
            return $this->errorResponse(null, 'User not found', 404);
        }

        // 2. استخراج اليوزر والمغيرات من الـ Request
        $user = $profile->user; // الوصول للموديل نفسه مش مجرد الـ ID
        $month = $request->query('month', now()->month); // لو مبعتش شهر، هات الشهر الحالي
        $year = $request->query('year', now()->year);   // لو مبعتش سنة، هات السنة الحالية


        // 3. جلب المربعات (Attendance Grid)
        $allLogs = $user->dailyLogs()
            ->with('group')
            ->whereMonth('log_date', $month)
            ->whereYear('log_date', $year)
            ->get();

        // 4. معالجة البيانات لكل مادة (Group) على حدة
        // 3. معالجة البيانات لكل مادة (Group) على حدة
        $subjectsStats = $allLogs->groupBy('group.name')->map(function ($subjectLogs, $subjectName) use ($user) {

            // أ- حساب شبكة الحضور للمادة (Attendance Grid)
            $grid = $subjectLogs->pluck('attendance_status', 'log_date');

            // ب- حساب نسبة الحضور لهذه المادة فقط
            $totalDays = $subjectLogs->count();
            $presentDays = $subjectLogs->where('attendance_status', 'present')->count();
            $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

            // ج- حساب التقدم الأكاديمي لهذه المادة فقط (Evaluations)
            // بنجيب التقييمات الخاصة بالمجموعة دي بس
            $groupId = $subjectLogs->first()->group_id;
            $evals = $user->evaluations()
                ->where('group_id', $groupId)
                ->selectRaw('SUM(score) as scored, SUM(max_score) as max')
                ->first();

            $progressRate = ($evals && $evals->max > 0) ? round(($evals->scored / $evals->max) * 100, 1) : 0;

            return [
                'subject_name'      => $subjectName,
                'attendance_grid'   => $grid,
                'attendance_rate'   => $attendanceRate . '%',
                'academic_progress' => $progressRate . '%',
            ];
        })->values(); // استخدام values() لتحويلها لـ Array بسيطة للفرونت إند

        // تجميع كل الداتا العظمة دي
        $data = [
            'profile' => $profile,
            'subjects_statistics' => $subjectsStats
        ];

        return $this->successResponse($data, 'Profile Retrieved Successfully', 200);
    }

    public function updateProfile(RegisterRequest $request)
    {
        // لو مش موجود ادي error
        $profile = User::find(auth()->user()->id);
        if (!$profile) {
            return $this->errorResponse($data = null, 'User not found', 404);
        }
        $profile->update($request->validated());
        $data = [
            'profile' => $profile,
        ];
        return $this->successResponse($data, 'Profile Updated Successfully', 200);
    }
}
