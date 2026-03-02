<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseAndLessonSeeder extends Seeder
{
    public function run(): void
    {
        // Crear (o reutilizar) una alumna de prueba
        $student = User::firstOrCreate(
            ['email' => 'alumna@example.com'],
            [
                'name' => 'Alumna de prueba',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]
        );

        // Definir algunos cursos de ejemplo
        $coursesData = [
            [
                'title' => 'Curso básico de bombones',
                'short_description' => 'Aprende a templar el chocolate y a crear tus primeros bombones caseros.',
                'description' => 'Curso pensado para empezar desde cero en el mundo del chocolate: tipos de chocolate, equipos básicos, temperado y rellenos fáciles.',
                'price_cents' => 4900,
                'currency' => 'EUR',
                'level' => 'beginner',
            ],
            [
                'title' => 'Curso intermedio de tabletas y toppings',
                'short_description' => 'Sube de nivel con tabletas rellenas y decoraciones crujientes.',
                'description' => 'Explora distintas técnicas para crear tabletas rellenas, toppings crujientes y combinaciones de sabores más avanzadas.',
                'price_cents' => 6900,
                'currency' => 'EUR',
                'level' => 'intermediate',
            ],
        ];

        foreach ($coursesData as $index => $data) {
            $slug = Str::slug($data['title']);

            /** @var Course $course */
            $course = Course::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $data['title'],
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'price_cents' => $data['price_cents'],
                    'currency' => $data['currency'],
                    'level' => $data['level'],
                    'is_active' => true,
                ]
            );

            // Crear algunas lecciones de ejemplo
            $lessons = [
                [
                    'title' => 'Bienvenida al curso',
                    'order' => 1,
                    'summary' => 'Introducción al curso y materiales que vas a necesitar.',
                    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                ],
                [
                    'title' => 'Temperado del chocolate',
                    'order' => 2,
                    'summary' => 'Teoría y práctica del temperado, con demostración paso a paso.',
                    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                ],
                [
                    'title' => 'Rellenos cremosos y crujientes',
                    'order' => 3,
                    'summary' => 'Cómo preparar rellenos sencillos pero resultones para tus bombones.',
                    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                ],
            ];

            foreach ($lessons as $lessonData) {
                Lesson::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'title' => $lessonData['title'],
                    ],
                    [
                        'order' => $lessonData['order'],
                        'summary' => $lessonData['summary'],
                        'video_url' => $lessonData['video_url'],
                    ]
                );
            }

            // Inscribir a la alumna en todos los cursos como "paid" para que puedas probar el campus
            Enrollment::updateOrCreate(
                [
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ],
                [
                    'status' => 'paid',
                    'paid_at' => now(),
                ]
            );
        }
    }
}
