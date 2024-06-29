@extends('layouts.admin.layout')
@section('title')
    {{ __('main.view_coupon')}}
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') {{__('main.view_coupon')}} @endslot
@slot('title') {{__('main.coupon')}} @endslot
@slot('link') {{ route('admin.couponList')}} @endslot
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center">
                <h6 class="m-0 font-weight-bold text-primary flex-grow-1">{{__('main.coupon_details')}}</h6>
                <div class="flex-shrink-0">
                    <a @if($couponDetails->id === Auth::user()->id) href="javascript:void(0)" class="btn btn-disable" @disabled(true) @else class="btn btn-primary edit-item-btn" href="{{route('admin.editCoupon',['id'=>$couponDetails->id])}}" @endif ><i class="ri-edit-line fs-16"></i></a>

                    <a href="javascript:void(0)" @if($couponDetails->id === Auth::user()->id) class="btn btn-disable" @disabled(true) @else class="btn btn-danger remove-item-btn" data-id="{{$couponDetails->id}}" @endif
                     ><i class="ri-delete-bin-2-line fs-16"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="">
                    <table class="table table-striped mb-0">
                        <tbody>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('main.coupon_code') }}</td>
                                <td class="">{{ $couponDetails->coupon_code }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('main.uses_per_customer') }}</td>
                                <td>{{ $couponDetails->uses_per_customer }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('main.coupon_option') }}</td>
                                <td>{{ $couponDetails->coupon_option }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('main.expiry_date') }}</td>
                                <td>{{!empty($couponDetails->expiry_date) ? date("F jS Y", strtotime($couponDetails->expiry_date)) : '-'}}</td>
                            </tr>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('main.amount_type') }}</td>
                                <td>{{ isset($couponDetails->amount_type) ? $couponDetails->amount_type : ''}}</td>
                            </tr>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('main.coupon_rule') }}</td>
                                    @foreach ($couponRules as $couponRule)
                                      @if ($couponRule['id'] == $couponDetails->coupon_rule)
                                      <td>{{$couponRule->name}}</td>
                                      @endif
                                    @endforeach
                                </td>
                            </tr>
                            @if($couponDetails->amount !== 0.00)
                            <tr>
                                <td class="font-bold-600">{{ __('main.amount') }}</td>
                                <td>{{ isset($couponDetails->amount) ? $couponDetails->amount : '' }}</td>
                            </tr>
                            @endif

                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('main.status') }}</td>
                                <td>
                                    <div class="large text-muted">
                                        <span class="badge rounded-pill badge-outline-{{$couponDetails->status==1?'success':'danger'}}">
                                            {{$couponDetails->status ?  __('main.active')  :  __('main.in_active') }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @if ($couponDetails->coupon_rule == 45)
                                @if ($couponDetails->coupon_rule_data != null)
                                    <td class="font-bold-600 align-middle">{{ __('main.category') }}</td>
                                    <td><select class="selectpicker" id="categories" name="coupon_rule_data[]" disabled (true) multiple>
                                            @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ isset($couponDetails->coupon_rule_data) && in_array($category->id, $couponDetails->coupon_rule_data) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select></td>
                                @endif
                            @elseif ($couponDetails->coupon_rule == 46)
                            @if ($couponDetails->coupon_rule_data != null)
                                        @php
                                            $rowIdx = 0;
                                        @endphp
                                       <td> <h4><span>{{__('Options(Range of Discount)')}}</span></h4></td>
                                        @foreach ($couponDetails->coupon_rule_data as $coupon_rule_data)
                                            <tr class="R" id="R{{$rowIdx++}}">
                                            <td class="row-index">
                                                <label class="small mb-1 @error('minimum_value') is-invalid @enderror" for="minimum_value">{{__('main.minimum_value')}}</label>
                                                <input class="form-control" readonly id="R{{$rowIdx}}" name="range[{{$rowIdx}}][]" type="number" min="0" placeholder="{{__('main.Enter Min. Value')}}" value="{{$coupon_rule_data[0]}}" required autofocus>
                                            </td>
                                            <td class="row-index">
                                                <label class="small mb-1 @error('maximum_vlaue') is-invalid @enderror" for="maximum_vlaue">{{__('main.maximum_value')}}</label>
                                                <input class="form-control" readonly id="R{{$rowIdx}}" name="range[{{$rowIdx}}][]" type="number" min="0" placeholder="{{__('main.Enter Max. Value')}}" value="{{$coupon_rule_data[1]}}" required autofocus>
                                            </td>
                                            <td class="row-index">
                                                <label class="small mb-1 @error('amount') is-invalid @enderror" for="amount">{{__('main.discount_value')}}</label>
                                                <input class="form-control" readonly id="R{{$rowIdx}}" name="range[{{$rowIdx}}][]" type="number" min="0" value="{{$coupon_rule_data[2]}}" required autofocus>
                                            </td>
                                            </tr>
                                        @endforeach
                                @endif
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/libs/select2/js/select2.full.min.js')}}"></script>
<script>
$(document).ready(function () {
    $('.dropify').dropify();
    $('.selectpicker').select2();
    $("body").on('click', '.remove-item-btn', function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this Coupon!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
            var data = {
                "id": id,
            }
            $.ajax({
                type: "DELETE",
                url: "{{route('admin.destroyCoupon',"")}}"+"/" + id,
                data: data,
                success: function(response) {
                swal(response.status, {
                    icon: "success",
                    timer: 3000
                })
                .then((result) => {
                    window.location = '{{ route('admin.couponList') }}';
                });
                }
            });
            }
        });
    });
});
</script>
@endsection
