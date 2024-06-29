@extends('layouts.admin.layout')
@section('title')
    {{ __('main.admin')}}
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') {{__('main.create')}} @endslot
@slot('title') {{__('main.admin')}} @endslot
@slot('link') {{ route('admin.adminList')}} @endslot
@endcomponent
<div class="row">
	<div class="col-xxl-3">
		<div class="card shadow-sm">
			<div class="card-body p-4">
				<div class="text-center">
					<div class="profile-user position-relative d-inline-block mx-auto">
                        <div class="mx-auto">
                            <input class="dropify" type="file" id="profile-img-upload" name="image" accept="image/png, image/jpeg, image/jpg" name="image" form="storeAdmin" data-show-remove="false">
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xxl-9">
		<div class="card shadow-sm">
			<div class="card-body">
                <form method="POST" action="{{ route('admin.storeAdmin') }}" enctype="multipart/form-data" id="storeAdmin">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">{{__('main.full_name')}}</label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name') }}" placeholder="{{__('main.Enter your full name')}}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">{{__('main.email')}}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('main.Enter email adderss') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('main.password') }}</label>
                                <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('main.enter_password') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="birthdate">{{__('main.birthday')}}</label>
                                <input class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" type="date" max="{{date("Y-m-d")}}" placeholder="{{__('main.Enter your birthday')}}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="gender_type" class="form-label">{{ __('main.gender') }}</label>
                                <select class="form-select mb-3 @error('gender_type') is-invalid @enderror" name="gender_type" id="gender_type"  data-choices data-choices-search-false>
                                    <option value="">{{__('main.select')}}</option>
                                    @foreach (\App\Models\Lockup::getByTypeKey('genderType') as $key => $gender)
                                        <option value="{{ $key }}" {{ old('gender_type') == $key ? 'selected' : '' }}>{{ $gender }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="phone">{{__('main.phone_number')}}</label>
                                <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" type="tel" placeholder="{{__('main.Enter your phone number')}}"  required autofocus>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="role_ids" class="form-label">{{ __('main.role') }}</label>
                                <select class="form-select mb-3 @error('role_ids') is-invalid @enderror" name="role_ids[]" id="role_ids" data-choices>
                                    @if (count($roles))
                                        <option value="">{{__('main.select')}}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-check form-switch form-check-right mt-4">
                                <input type="checkbox" id="is_active" name="is_active" role="switch" class="form-check-input" checked />
                                <label class="form-check-label" for="is_active">{{__('main.is_active')}}</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                                <a type="button" class="btn btn-danger" href="{{ url()->previous() }}">{{ __('main.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
$(function () {
    $('#profile-img-upload').dropify({
        messages: {
            'default': '<span style="font-size:12px;">{{__('message.Drag & Drop your picture here or click to Browse')}}</span>',
            'replace': '{{__('message.Drag and drop or click to replace')}}',
            'remove':  '{{__('main.remove')}}',
            'error':   '{{__('message.Ooops something wrong happended.')}}'
        }
    });
});
</script>
@endsection

