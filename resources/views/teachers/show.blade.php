@extends('layouts.app')

@section('title', $teacher->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card app-card">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0 fw-bold">{{ $teacher->name }}</h1>
                <span class="badge badge-soft">Teacher</span>
            </div>
            <div class="card-body p-4 bg-white">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Username</div>
                            <div class="fw-semibold">{{ $teacher->username ?: str($teacher->email)->before('@') }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Email</div>
                            <div class="fw-semibold">{{ $teacher->email }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Contact Number</div>
                            <div class="fw-semibold">{{ $teacher->contact ?: 'Not set' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Address</div>
                            <div class="fw-semibold">{{ $teacher->address ?: 'Not set' }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="text-secondary small text-uppercase fw-semibold">Created</div>
                            <div class="fw-semibold">{{ $teacher->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-brand px-4">Edit Teacher</a>
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary px-4">Back to Teachers</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
