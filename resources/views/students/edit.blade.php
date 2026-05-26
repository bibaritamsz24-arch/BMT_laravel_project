@extends('layouts.app')

@section('title', 'Edit ' . $student->full_name)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card app-card border-0">
            <div class="card-header py-3">
                <h1 class="h4 mb-0 fw-bold">Edit Student</h1>
            </div>
            <div class="card-body p-4 p-md-5 bg-white">
                @if (session('success'))
                    <div class="alert alert-success mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('students.update', $student) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12 col-md-4 form-group add-student-span-full">
                            <label for="first_name" class="form-label fw-semibold">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $student->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" placeholder="Enter first name" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4 form-group add-student-span-full">
                            <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $student->middle_name) }}" class="form-control @error('middle_name') is-invalid @enderror" placeholder="Enter middle name">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4 form-group add-student-span-full">
                            <label for="last_name" class="form-label fw-semibold">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $student->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" placeholder="Enter last name" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 form-group add-student-span-full">
                            <label for="address" class="form-label fw-semibold">Address</label>
                            <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" placeholder="Enter address" required>{{ old('address', $student->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 form-group add-student-span-full">
                            <label for="contact" class="form-label fw-semibold">Contact</label>
                            <input type="text" name="contact" id="contact" value="{{ old('contact', $student->contact) }}" class="form-control @error('contact') is-invalid @enderror" placeholder="Enter contact number" required>
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6 form-group add-student-span-full">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <div class="border rounded-4 p-3 bg-light">
                                <h2 class="h6 fw-bold mb-3">Student Login Account</h2>
                                <p class="text-secondary small mb-3">The student logs in with this username. Enter a new password only if you want to reset the student's login password.</p>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="username" class="form-label fw-semibold">Username</label>
                                        <input type="text" name="username" id="username" value="{{ old('username', $student->userAccount->username) }}" class="form-control @error('username') is-invalid @enderror" placeholder="Enter username" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="password" class="form-label fw-semibold">New Password</label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm new password">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="degree_id" class="form-label fw-semibold">Degree</label>
                            <select name="degree_id" id="degree_id" class="form-select @error('degree_id') is-invalid @enderror" required>
                                <option value="">Select Degree</option>
                                @foreach ($degrees as $degree)
                                    <option value="{{ $degree->id }}" {{ old('degree_id', $student->degree_id) == $degree->id ? 'selected' : '' }}>{{ $degree->title }}</option>
                                @endforeach
                            </select>
                            @error('degree_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button type="submit" class="btn btn-brand px-4">Update Student</button>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-4 mb-0">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
