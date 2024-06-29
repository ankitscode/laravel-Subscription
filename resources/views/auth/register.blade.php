@extends('layouts.auth')
@section('title')
    @lang('main.sign_in')
@endsection
@section('content')
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-row justify-content-center">
                                            <div class="mt-5">
                                                <img src="{{ URL::asset('assets/images/logo.jpg') }}" alt=""
                                                    height="auto" width="auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h2 class="text-primary">Welcome Back !</h2>
                                            <p class="text-muted fs-4"><strong>Sign in to continue to
                                                    {{ config('app.name') }}.</strong></p>
                                        </div>

                                        <div class="mt-4">
                                            <form action="{{ route('register') }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Username <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name') }}" id="username"
                                                        placeholder="Enter username" required>
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback">
                                                        Please enter username
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="useremail" class="form-label">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}" id="useremail"
                                                        placeholder="Enter email address" required>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="invalid-feedback">
                                                        Please enter email
                                                    </div>
                                                </div>
        
                                                <div class="mb-2">
                                                    <label for="userpassword" class="form-label">Password <span class="text-danger">*</span></label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="userpassword" placeholder="Enter password" required>
                                                        @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="togglePasswordBtn1"><i class="ri-eye-fill align-middle"></i></button>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-4">
                                                    <label class="form-label" for="input-password">Confirm Password</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="input-password" placeholder="Enter Confirm Password" required>
                                                        @error('password_confirmation')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="togglePasswordBtn2"><i class="ri-eye-fill align-middle"></i></button>
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <p class="mb-0 fs-12 text-muted fst-italic">By registering you agree to the
                                                        HangingPanda <a href="#"
                                                            class="text-primary text-decoration-underline fst-normal fw-medium">Terms
                                                            of Use</a></p>
                                                </div>
        
                                                <div class="mt-4">
                                                    <button class="btn btn-success w-100" type="submit">Sign Up</button>
                                                </div>
        
                                                <div class="mt-4 text-center">
                                                    <div class="signin-other-title">
                                                        <h5 class="fs-13 mb-4 title text-muted">Create account with</h5>
                                                    </div>
        
                                                    <div>
                                                        <button type="button"
                                                            class="btn btn-primary btn-icon waves-effect waves-light"><i
                                                                class="ri-facebook-fill fs-16"></i></button>
                                                        <button type="button"
                                                            class="btn btn-danger btn-icon waves-effect waves-light"><i
                                                                class="ri-google-fill fs-16"></i></button>
                                                        <button type="button"
                                                            class="btn btn-dark btn-icon waves-effect waves-light"><i
                                                                class="ri-github-fill fs-16"></i></button>
                                                        <button type="button"
                                                            class="btn btn-info btn-icon waves-effect waves-light"><i
                                                                class="ri-twitter-fill fs-16"></i></button>
                                                    </div>
                                                </div>
                                            </form>
        
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end container -->
                </div>
                                                
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> Admin Panel Crafted by HangingPanda</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const togglePasswordButtons = document.querySelectorAll('#togglePasswordBtn1, #togglePasswordBtn2');

    togglePasswordButtons.forEach(function(button) {
        button.addEventListener('click', function () {
            const inputField = this.previousElementSibling;
            const type = inputField.getAttribute('type') === 'password'? 'text' : 'password';
            inputField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('ri-eye-fill');
            this.querySelector('i').classList.toggle('ri-eye-off-fill');
        });
    });
});
    </script>
@endsection
