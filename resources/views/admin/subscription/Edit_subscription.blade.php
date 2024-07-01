@extends('layouts.admin.layout')
@section('title')
    {{ __('main.edit_user') }}
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('main.edit') }}
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
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <form method="POST" action="{{ route('admin.subscriptionUpdate', ['id' => $subscription->id]) }}">
                        <div class="row card-header py-3 d-flex align-items-center" style="background: none">
                            <h6 class="col-10 m-0 font-weight-bold text-primary flex-grow-1">{{ __('Subscription Details') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            @csrf
                            <div class="row gx-3 mb-3">
                                {{-- <div class="col-md-6"> --}}
                                    <label class="small mb-1" for="name">{{ __('Subscription Name') }}</label>
                                    <input class="form-control @error('name') is-invalid @enderror" id="name"
                                        name="name" type="text" placeholder="{{ __('Enter your subscription name') }}"
                                        value="{{ $subscription->name }}" required autofocus>
                            </div>
                            <div class="row gx-3 mb-3">
                                {{-- <div class="col-md-6"> --}}
                                    <label class="small mb-1" for="email">{{ __('Duration') }}</label>
                                    <input class="form-control @error('duration') is-invalid @enderror" id="duration"
                                        name="duration" type="text"
                                        placeholder="{{ __('Enter your subbscription duration') }} "
                                        value="{{ $subscription->duration }}">
                            </div>
                            {{-- </div> --}}
                            <div class="row gx-3 mb-3">
                                {{-- <div class="col-md-6"> --}}
                                    <label class="small mb-1" for="amount">{{ __('Amount') }}</label>
                                    <input class="form-control @error('amount') is-invalid @enderror" id="Amount"
                                        name="amount" type="tel" placeholder="{{ __('Enter your amount') }}"
                                        value="{{ $subscription->amount }}">
                                {{-- </div> --}}
                            </div>
                            <div>
                                <button class="btn btn-primary" type="submit">{{ __('main.save_changes') }}</button>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
