@extends('layouts.app')

@section('title', $student->full_name)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card app-card border-0">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0 fw-bold">{{ $student->full_name }}</h1>
                <span class="badge badge-soft">Student</span>
            </div>
            <div class="card-body p-4 bg-white">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Email</div>
                            <div class="fw-semibold">{{ $student->email }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Username</div>
                            <div class="fw-semibold">{{ $student->userAccount->username }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Contact</div>
                            <div class="fw-semibold">{{ $student->contact }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Address</div>
                            <div class="fw-semibold">{{ $student->address }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Degree</div>
                            <div class="fw-semibold text-danger">{{ $student->degree->title }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Created</div>
                            <div class="fw-semibold">{{ $student->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-brand px-4">Edit Student</a>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary px-4">Back to Students</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
