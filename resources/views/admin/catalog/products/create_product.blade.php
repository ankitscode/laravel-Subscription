@extends('layouts.admin.layout')
@section('title')
{{ __('main.create_product')}}
@endsection
@section('css')
<link rel="stylesheet" href={{asset("assets/css/dropify.css")}}>
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') {{__('main.create')}} @endslot
@slot('title') {{__('main.product')}} @endslot
@slot('link') {{route('admin.productList')}} @endslot
@endcomponent
<div class="row">
	<div class="col-xxl-3">
		<div class="card shadow-sm">
			<div class="card-body p-4">
				<div class="text-center">
					<div class="profile-user position-relative d-inline-block mx-auto">
                        <div class="mx-auto">
                            <input class="dropify" type="file" id="profile-img-upload" name="image" accept="image/png, image/jpeg, image/jpg" name="image" form="storeProduct" data-show-remove="false">
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xxl-9">
		<div class="card shadow-sm">
			<div class="card-body">
                <form method="POST" action="{{ route('admin.storeProduct') }}" enctype="multipart/form-data" id="storeProduct">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">{{__('main.product_name')}}</label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name') }}" placeholder="{{__('main.Enter Product Name')}}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">{{__('main.price')}}</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" placeholder="{{ __('main.Enter Product Price') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('main.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" cols="30" rows="10">{{ old('description') }}</textarea>
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
