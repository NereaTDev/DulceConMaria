<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['user', 'course'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user', 'course']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'   => ['required','exists:users,id'],
            'course_id' => ['required','exists:courses,id'],
            'status'    => ['required','in:pending,paid,cancelled'],
        ]);

        $enrollment = new Enrollment($data);

        if ($data['status'] === 'paid') {
            $enrollment->paid_at = now();
        }

        $enrollment->save();

        return back()->with('status', 'Inscripción creada correctamente');
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $data = $request->validate([
            'status' => ['required','in:pending,paid,cancelled'],
        ]);

        $enrollment->status = $data['status'];
        if ($data['status'] === 'paid' && ! $enrollment->paid_at) {
            $enrollment->paid_at = now();
        }
        if ($data['status'] !== 'paid') {
            $enrollment->paid_at = null;
        }

        $enrollment->save();

        return redirect()->route('admin.enrollments.show', $enrollment)->with('status', 'Inscripción actualizada');
    }
}
