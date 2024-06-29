@extends('layouts.admin.layout')
@section('title')
{{ __('main.edit_user')}}
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') {{__('main.edit')}} @endslot
@slot('title') {{__('main.admin')}} @endslot
@slot('link') {{ route('admin.adminList')}} @endslot
@endcomponent
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <form method="POST" action="{{route('admin.updateAdmin',['uuid'=>$adminDetails->uuid])}}"
                enctype="multipart/form-data">
                <div class="row card-header py-3 d-flex align-items-center" style="background: none">
                    <h6 class="col-10 m-0 font-weight-bold text-primary flex-grow-1">{{__('main.admin_details')}}</h6>
                    <div class="col-2 form-check form-switch form-check-right flex-shrink-0">
                        <input class="form-check-input mx-1" name="is_active" type="checkbox" role="switch"
                            id="is_active" value="{{ old($adminDetails->is_active) }}" @if (old('is_active',
                            $adminDetails->is_active)) checked @endif>
                        <label class="form-check-label mx-1" for="is_active">{{__('main.is_active')}}</label>
                    </div>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="full_name">{{__('main.full_name')}}</label>
                            <input class="form-control @error('full_name') is-invalid @enderror" id="full_name"
                                name="full_name" type="text" placeholder="{{__('main.Enter your full name')}}"
                                value="{{ $adminDetails->full_name }}" required autofocus>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="email">{{__('main.email')}}</label>
                            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                type="email" placeholder="{{__('main.Enter email adderss')}} "
                                value="{{ $adminDetails->email }}" readonly>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="phone">{{__('main.phone_number')}}</label>
                            <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                type="tel" placeholder="{{__('main.Enter your phone number')}}"
                                value="{{ $adminDetails->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="birthdate">{{__('main.birthday')}}</label>
                            <input class="form-control @error('birthdate') is-invalid @enderror" id="birthdate"
                                name="birthdate" type="date" max="{{date(" Y-m-d")}}"
                                placeholder="{{__('main.Enter your birthday')}}"
                                value="{{ $adminDetails->birthdate }}">
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1">{{__('main.gender')}}</label>
                            <select class="form-select @error('gender_type') is-invalid @enderror" name="gender_type" data-choices>
                                @php
                                $genders = \App\Models\Lockup::getByTypeKey('genderType');
                                @endphp
                                @foreach ($genders as $key=>$gender)
                                <option value="{{$key}}" {{isset($adminDetails->gender_type) &&
                                    $adminDetails->gender_type == $key? 'selected':''}}>{{$gender}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1">{{__('main.role')}}</label>
                            <select class="form-select mb-3 @error('role_ids') is-invalid @enderror" name="role_ids[]" id="role_ids" data-choices>
                                @php
                                    if($adminDetails->getRoleNames() !== null){
                                        $assignedRole = $adminDetails->getRoleNames()->toArray();
                                    }
                                @endphp
                                @if (count($roles))
                                <option value="">{{__('main.select')}}</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{isset($assignedRole) && in_array($role->name,$assignedRole) ? 'selected':''}}>{{ $role->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">{{__('main.save_changes')}}</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{__('main.user_profile_picture')}}</h6>
            </div>
            <div class="card-body">
                <div class="card-body text-center">
                    <img class="rounded-circle mb-2 avater-ext"
                        src="{{!empty($adminDetails->media->name) ? asset(config('image.profile_image_path_view').$adminDetails->media->name) : asset("assets/images/users/user-dummy-img.jpg")}}" style="height: 10rem;width: 10rem;">
                    <div class="large text-muted mb-4">
                        <span
                            class="badge rounded-pill badge-outline-{{$adminDetails->is_active==1?'success':'danger'}}">
                            {{$adminDetails->is_active ? __('main.active') : __('main.in_active') }}
                        </span>
                    </div>
                    <button class="btn btn-soft-primary" data-bs-toggle="modal"
                        data-bs-target="#profileImageModal">{{__('main.update_profile_image')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<x-image_modal>
    <x-slot name="id">profileImageModal</x-slot>
    <x-slot name="modalTitle"> {{__('main.update_profile_image')}} </x-slot>
    <x-slot name="modalFormAction">{{ route('admin.updateImage',['uuid'=>$adminDetails->uuid]) }}</x-slot>
    <x-slot name="modalFormData">
        <div class="form-group">
            <input type="file" name="image" required class="dropify" accept="image/png, image/jpeg, image/jpg"
                data-default-file="{{!empty($adminDetails->media->name) ? asset(config('image.profile_image_path_view').$adminDetails->media->name) : asset("assets/images/users/user-dummy-img.jpg")}}">
        </div>
    </x-slot>
    <x-slot name="modalFormId">profileImageUpdate</x-slot>
    <x-slot name="modalFormSumbitText"> {{ __('main.update') }} </x-slot>
</x-image_modal>
@endsection
@section('script')
<script>
    $(document).ready(function () {
    $('.dropify').dropify();
});
</script>
@endsection
