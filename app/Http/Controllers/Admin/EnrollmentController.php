<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['user', 'course']);

        // Filtros por usuario y curso
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->integer('course_id'));
        }

        // Ordenación por estado o fecha de pago/creación
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc') === 'asc' ? 'asc' : 'desc';

        if ($sort === 'status') {
            $query->orderBy('status', $direction)->orderBy('created_at', 'desc');
        } elseif ($sort === 'paid_at') {
            $query->orderBy('paid_at', $direction)->orderBy('created_at', 'desc');
        } else { // fallback created_at
            $query->orderBy('created_at', $direction);
        }

        $enrollments = $query->get();
        $users = User::orderBy('name')->get();
        $courses = Course::orderBy('title')->get();

        return view('admin.enrollments.index', [
            'enrollments' => $enrollments,
            'users'       => $users,
            'courses'     => $courses,
            'sort'        => $sort,
            'direction'   => $direction,
        ]);
    }

    public function create(Request $request)
    {
        $users = User::orderBy('name')->get();

        $coursesQuery = Course::orderBy('title');

        // Si venimos con un user_id preseleccionado, ocultamos los cursos que ya tenga inscritos
        if ($request->filled('user_id')) {
            $user = User::with('enrollments')->find($request->integer('user_id'));

            if ($user) {
                $enrolledCourseIds = $user->enrollments()->pluck('course_id');
                if ($enrolledCourseIds->isNotEmpty()) {
                    $coursesQuery->whereNotIn('id', $enrolledCourseIds);
                }
            }
        }

        $courses = $coursesQuery->get();

        return view('admin.enrollments.create', [
            'users'   => $users,
            'courses' => $courses,
        ]);
    }

    public function show(Enrollment $enrollment)
    {
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'   => ['required','exists:users,id'],
            'course_id' => ['required','exists:courses,id'],
            'status'    => ['required','in:paid,pending,cancelled'],
        ]);

        $enrollment = Enrollment::firstOrNew([
            'user_id'   => $data['user_id'],
            'course_id' => $data['course_id'],
        ]);

        $enrollment->status  = $data['status'];
        $enrollment->paid_at = $data['status'] === 'paid' ? now() : null;
        $enrollment->save();

        return redirect()->route('admin.enrollments.index')
            ->with('status', 'Inscripción guardada');
    }

    public function edit(Enrollment $enrollment)
    {
        $users = User::orderBy('name')->get();

        // Para el usuario de esta inscripción, excluimos cursos que ya tenga en otras inscripciones
        $coursesQuery = Course::orderBy('title');
        $user = $enrollment->user()->with('enrollments')->first();

        if ($user) {
            $enrolledCourseIds = $user->enrollments()
                ->where('id', '!=', $enrollment->id)
                ->pluck('course_id');

            if ($enrolledCourseIds->isNotEmpty()) {
                $coursesQuery->whereNotIn('id', $enrolledCourseIds);
            }
        }

        $courses = $coursesQuery->get();

        return view('admin.enrollments.edit', [
            'enrollment' => $enrollment,
            'users'      => $users,
            'courses'    => $courses,
        ]);
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $data = $request->validate([
            'user_id'   => ['required','exists:users,id'],
            'course_id' => ['required','exists:courses,id'],
            'status'    => ['required','in:paid,pending,cancelled'],
        ]);

        $enrollment->user_id   = $data['user_id'];
        $enrollment->course_id = $data['course_id'];
        $enrollment->status    = $data['status'];
        $enrollment->paid_at   = $data['status'] === 'paid' ? now() : null;
        $enrollment->save();

        return redirect()->route('admin.enrollments.index')->with('status', 'Inscripción actualizada');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return back()->with('status', 'Inscripción eliminada');
    }
}
