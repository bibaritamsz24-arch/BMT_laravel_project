<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Response;

class PagesController extends Controller
{
    public function userProfile(): Response
    {
        $user = User::find(1);
        if (!$user) {
            return response('User not found.');
        }

        $bio = method_exists($user, 'profile') && $user->profile
            ? (string) $user->profile->bio
            : 'No profile relation configured';

        return response($user->name.' - '.$bio);
    }

    public function userPosts(): Response
    {
        $user = User::find(1);
        if (!$user) {
            return response('User not found.');
        }

        if (!method_exists($user, 'posts')) {
            return response('User posts relation is not configured.');
        }

        $lines = [];
        foreach ($user->posts as $post) {
            $lines[] = $user->name.': '.$post->content.' - '.$post->title;
        }

        if (empty($lines)) {
            return response($user->name.' has no posts.');
        }

        return response(implode('<br>', $lines));
    }

    public function studentCourse(): Response
    {
        $student = Student::query()
            ->with('degree')
            ->first();

        if (!$student) {
            return response('No students found yet.');
        }

        return response($student->full_name.' is enrolled in: '.$student->degree->title);
    }

    public function studentCourses(): Response
    {
        return $this->studentCourse();
    }
}
