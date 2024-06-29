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
                                            <form action="{{ route('login') }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label for="username" class="form-label fs-4">Username</label>
                                                    <input type="text"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        value="{{ old('email') }}" id="username" name="email"
                                                        placeholder="Enter username">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <div class="float-end">
                                                        <a href="{{ route('password.request') }}"
                                                            class="text-muted fs-5">{{ __('main.forgot_password?') }}</a>
                                                    </div>
                                                    <label class="form-label fs-4"
                                                        for="password-input">{{ __('main.password') }}</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password" style="z-index: 10"
                                                            class="form-control position-relative pe-5 @error('password') is-invalid @enderror"
                                                            name="password" placeholder="Enter password"
                                                            id="password-input">
                                                        <button
                                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted "
                                                            onclick=togglePasswordVisibility() style="z-index: 100"
                                                            type="button"><i class="ri-eye-fill align-middle closed"
                                                                id="toggle-password-btn"></i></button>
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="auth-remember-check">
                                                    <label class="form-check-label"
                                                        for="auth-remember-check">{{ __('main.remember_me') }}</label>
                                                </div>

                                                <div class="mt-4">
                                                    <button class="btn btn-success w-100 fs-4"
                                                        type="submit">{{ __('main.sign_in') }}</button>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <div class="signin-other-title">
                                                        {{-- <h5 class="fs-13 mb-4 title">Sign In with</h5> --}}
                                                        <h5 class="fs-13 mb-4 title"></h5>
                                                    </div>
                                                    <div class="mt-5 text-center">
                                                        {{-- <button type="button" class="btn"></button>
                                                        <button type="button" class="btn"></button>
                                                        <button type="button" class="btn"></button>
                                                        <button type="button" class="btn"></button> --}}
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        {{-- <div class="mt-5 text-center">
                                            <p class="mb-0">Don't have an account ? <a href="Signup"
                                                    class="fw-semibold text-primary text-decoration-underline"> Signup</a>
                                            </p>
                                        </div> --}}
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

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
                            @
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
                function togglePasswordVisibility() {
                var passwordInput = document.getElementById('password-input');
                if (passwordInput.type === 'text') {
                passwordInput.type = 'password';
                } else {
                passwordInput.type = 'text';
                }
                }
                </script>
          @endsection
