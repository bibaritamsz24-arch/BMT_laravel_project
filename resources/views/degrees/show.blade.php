@extends('layouts.app')

@section('title', $degree->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card app-card border-0">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0 fw-bold">{{ $degree->title }}</h1>
                <span class="badge badge-soft">Degree</span>
            </div>
            <div class="card-body p-4 bg-white">
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Students Enrolled</div>
                            <div class="fs-5 fw-bold text-danger">{{ $degree->students_count }}</div>
                        </div>
                    </div>
                </div>

                @if ($degree->students->isNotEmpty())
                    <h2 class="h6 fw-bold mb-3">Enrolled Students</h2>
                    <div class="table-responsive mb-4">
                        <table class="table table-theme align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($degree->students as $student)
                                    <tr>
                                        <td class="fw-semibold">{{ $student->full_name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->contact }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('degrees.edit', $degree) }}" class="btn btn-brand px-4">Edit Degree</a>
                    <a href="{{ route('degrees.index') }}" class="btn btn-outline-secondary px-4">Back to Degrees</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
