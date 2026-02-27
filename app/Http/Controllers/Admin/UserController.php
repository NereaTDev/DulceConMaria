<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();
        return view('admin.users.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required','string','max:255'],
            'email'                 => ['required','email','max:255','unique:users,email'],
            'phone'                 => ['nullable','string','max:30'],
            'city'                  => ['nullable','string','max:100'],
            'country'               => ['nullable','string','max:100'],
            'instagram'             => ['nullable','string','max:100'],
            'password'              => ['required','string','min:8','confirmed'],
            'role'                  => ['required','in:user,admin'],
            'course_id'             => ['nullable','exists:courses,id'],
            'enrollment_status'     => ['nullable','in:pending,paid,cancelled'],
            'grant_all_courses'     => ['nullable','boolean'],
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'city'      => $data['city'] ?? null,
            'country'   => $data['country'] ?? null,
            'instagram' => $data['instagram'] ?? null,
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
        ]);

        // Si se selecciona curso, crear inscripción opcionalmente
        if (! empty($data['course_id'])) {
            $status = $data['enrollment_status'] ?? 'pending';

            $enrollment = new Enrollment([
                'user_id'   => $user->id,
                'course_id' => $data['course_id'],
                'status'    => $status,
            ]);

            if ($status === 'paid') {
                $enrollment->paid_at = now();
            }

            $enrollment->save();
        }

        // Si se marca "acceso a todos los cursos", crear inscripciones pagadas
        if (! empty($data['grant_all_courses'])) {
            $courses = Course::all();
            foreach ($courses as $course) {
                Enrollment::firstOrCreate(
                    ['user_id' => $user->id, 'course_id' => $course->id],
                    ['status' => 'paid', 'paid_at' => now()]
                );
            }
        }

        return redirect()->route('admin.users.show', $user)->with('status', 'Usuario creado correctamente');
    }

    public function show(User $user)
    {
        $enrollments = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users.show', compact('user','enrollments'));
    }

    public function edit(User $user)
    {
        $courses = Course::orderBy('title')->get();
        $enrollments = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users.edit', compact('user','courses','enrollments'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email,' . $user->id],
            'phone'    => ['nullable','string','max:30'],
            'city'     => ['nullable','string','max:100'],
            'country'  => ['nullable','string','max:100'],
            'instagram'=> ['nullable','string','max:100'],
            'notes'    => ['nullable','string'],
            'password' => ['nullable','string','min:8','confirmed'],
            'role'     => ['required','in:user,admin'],
            'grant_all_courses' => ['nullable','boolean'],
        ]);

        $update = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'city'      => $data['city'] ?? null,
            'country'   => $data['country'] ?? null,
            'instagram' => $data['instagram'] ?? null,
            'notes'     => $data['notes'] ?? null,
            'role'      => $data['role'],
        ];

        if (! empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $user->update($update);

        // Si se marca "acceso a todos los cursos", asegurar inscripciones para todos los cursos
        if (! empty($data['grant_all_courses'])) {
            $courses = Course::all();
            foreach ($courses as $course) {
                Enrollment::firstOrCreate(
                    ['user_id' => $user->id, 'course_id' => $course->id],
                    ['status' => 'paid', 'paid_at' => now()]
                );
            }
        }

        return redirect()->route('admin.users.show', $user)->with('status', 'Usuario actualizado');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('status', 'Usuario eliminado');
    }
}
