@extends('layouts.admin.layout')
@section('title')
    {{ __('main.admin') }}
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('main.create') }}
        @endslot
        @slot('title')
            {{ __('Subscription') }}
        @endslot
        @slot('link')
            {{ route('admin.subscription') }}
        @endslot
    @endcomponent
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.subscriptionStore') }}">
                        @csrf
                        <div class="row">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Subscription Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="{{ __('Enter your Subscription Name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="duration" class="form-label">{{ __('Duration') }}</label>
                                <input type="duration" class="form-control @error('duration') is-invalid @enderror"
                                    id="duration" name="duration" value="{{ old('duration') }}"
                                    placeholder="{{ __('Enter Plan Duration ') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">{{ __('Amount') }}</label>
                                <input type="text" class="form-control @error('amount') is-invalid @enderror"
                                    id="amount" name="amount" placeholder="{{ __('Enter Amount') }}" required>
                            </div>
                            {{-- <div class="col-lg-6">
                            <div class="form-check form-switch form-check-right mt-4">
                                <input type="checkbox" id="is_active" name="is_active" role="switch" class="form-check-input" checked />
                                <label class="form-check-label" for="is_active">{{__('main.is_active')}}</label>
                            </div>
                        </div> --}}
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                                    <a type="button" class="btn btn-danger"
                                        href="{{ url()->previous() }}">{{ __('main.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

