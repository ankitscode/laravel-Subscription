@extends('layouts.admin.layout')
@section('title')
  {{__('main.edit_coupon')}}
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') {{__('main.edit')}} @endslot
@slot('title') {{__('main.coupon')}} @endslot
@slot('link') {{ route('admin.couponList')}} @endslot
@endcomponent
<section class="content">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <div class="d-grid gap-2 d-md-flex justify-content-between">
        <h4 class="m-0 font-weight-bold">
          {{__('main.edit_coupon')}}
        </h4>
        <a type="button" class="btn btn-danger" id="deleteCoupon" data-id="{{ $couponDetails->id }}"><i class=" ri-delete-bin-line"></i> {{ __('main.delete') }}</a>
      </div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{route('admin.updateCoupon', ['id'=> $couponDetails->id])}}" id="updateCouponForm" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-4 row">
          <label class="col-sm-2 col-form-label" for="uses_per_customer">{{__('main.uses_per_customer')}}</label>
          <div class="col-sm-10">
            <input class="form-control @error('uses_per_customer') is-invalid @enderror" id="uses_per_customer" name="uses_per_customer" type="number" min="0" value="{{$couponDetails->uses_per_customer}}" required autofocus>
          </div>
        </div>
        <div class="form-group mb-4 row " id="coupon_code">
          <label class="col-sm-2 col-form-label" for="coupon_code">{{__('main.coupon_code')}}</label>
          <div class="col-sm-10">
            <input class="form-control @error('coupon_code') is-invalid @enderror" name="coupon_code" type="text" placeholder="{{__('main.Enter coupon code')}}" value="{{$couponDetails->coupon_code}}" disabled>
          </div>
        </div>
        <div class='form-group mb-4 row d-none'>
          <label class="col-sm-2 col-form-label" for="coupon_rule">{{__('main.coupon_rule')}}</label>
          <div class="col-sm-10">
            <select class="form-control" id="coupon_rule" name="coupon_rule" disabled>
              @foreach ($couponRules as $couponRule)
                @if ($couponRule->id == $couponDetails->coupon_rule)
                  <option value="{{$couponRule->id}}" selected>{{$couponRule->name}}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
        <div class="demo-radio-button ">
            <div class="row form-group mb-4">
                <label class="col-sm-2 col-form-label">{{__('main.amount_type')}}</label>
              <div class="col-sm-10 d-flex align-items-center">
                  <div class="form-check ps-0">
                    <input name="amount_type" type="radio" class="with-gap" id="percentage" value="percentage" @if ($couponDetails->amount_type == "percentage") checked @endif/>
                    <label for="percentage">{{__('main.percentage_(in_%)')}}</label>
                  </div>
                  <div class="form-check ">
                    <input name="amount_type" type="radio" class="with-gap" id="fixed" value="fixed" @if ($couponDetails->amount_type == "fixed") checked @endif/>
                    <label for="fixed">{{__('main.fixed')}}</label>
                  </div>
              </div>
            </div>
        </div>

        @if($couponDetails->amount !== 0.00)
          <div class="form-group mb-4 row">
            <label class="col-sm-2 col-form-label" for="amount">{{__('main.amount')}}</label>
            <div class="col-sm-10">
              <input class="form-control @error('amount') is-invalid @enderror" name="amount" type="text" placeholder="{{__('main.Enter amount')}}" value="{{$couponDetails->amount}}" required autofocus>
            </div>
          </div>
        @endif
        @if ($couponDetails->coupon_rule == 45)
            @if ($couponDetails->coupon_rule_data != null)
                <div class="form-group mb-4 row">
                    <label class="col-sm-2 col-form-label" for="categories">{{__('main.category')}}</label>
                    <div class="col-sm-10">
                    <select class="selectpicker" id="categories" name="coupon_rule_data[]" multiple>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ isset($couponDetails->coupon_rule_data) && in_array($category->id, $couponDetails->coupon_rule_data) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>

                        @endforeach
                    </select>
                    </div>
                </div>
            @endif

        @elseif ($couponDetails->coupon_rule == 46)
          @if ($couponDetails->coupon_rule_data != null)
            <div class='form-group mb-4 row couponRuleData'>
              <fieldset class="box-body" id="attributeField">
                <legend class="small mb-1">
                  <h4><span>{{__('Options(Range of Discount)')}}</span></h4>
                </legend>
                <br />
                <div class="table-responsive rounded card-table">
                  <table class="table border-no">
                    <thead>
                    </thead>
                    <tbody id="tbody">
                      @php
                        $rowIdx = 0;
                      @endphp
                      @foreach ($couponDetails->coupon_rule_data as $coupon_rule_data)
                        <tr class="R" id="R{{$rowIdx++}}">
                          <td class="row-index">
                            <label class="small mb-1 @error('minimum_value') is-invalid @enderror" for="minimum_value">{{__('main.minimum_value')}}</label>
                            <input class="form-control" id="R{{$rowIdx}}" name="range[{{$rowIdx}}][]" type="number" min="0" placeholder="{{__('main.Enter Min. Value')}}" value="{{$coupon_rule_data[0]}}" required autofocus>
                          </td>
                          <td class="row-index">
                            <label class="small mb-1 @error('maximum_vlaue') is-invalid @enderror" for="maximum_vlaue">{{__('main.maximum_value')}}</label>
                            <input class="form-control" id="R{{$rowIdx}}" name="range[{{$rowIdx}}][]" type="number" min="0" placeholder="{{__('main.Enter Max. Value')}}" value="{{$coupon_rule_data[1]}}" required autofocus>
                          </td>
                          <td class="row-index">
                            <label class="small mb-1 @error('amount') is-invalid @enderror" for="amount">{{__('main.discount_value')}}</label>
                            <input class="form-control" id="R{{$rowIdx}}" name="range[{{$rowIdx}}][]" type="number" min="0" value="{{$coupon_rule_data[2]}}" required autofocus>
                          </td>
                          <td class="text-center align-bottom" id="{{$rowIdx}}">
                            <button class="btn btn-danger remove" type="button">{{__('main.remove')}}</button>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="5" class="">
                          <button id="add_new_option_button" data-action="add_new_row" title="Add Option" type="button" class="btn btn-primary">
                            <span>{{__('main.add_option')}}</span>
                          </button>
                        </th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </fieldset>
            </div>
          @endif

        @elseif ($couponDetails->coupon_rule == 11)
          <div class="form-group mb-4 row">
            <label class="col-sm-2 col-form-label" for="user_type">{{__('main.user_type')}}</label>
            <div class="col-sm-10">
              <select class="selectpicker" name="coupon_rule_data[]" id="user_type" multiple>
                @if (count($userTypes))
                  @foreach ($userTypes as $type)
                    <option value="{{$type->id}}" {{ in_array($type->id, $couponDetails->coupon_rule_data) ? 'selected' : ''}}>{{$type->name}}</option>
                  @endforeach
                @endif
              </select>
            </div>
          </div>
        @endif

        <div class="form-group mb-4 row">
          <label class="col-sm-2 col-form-label" for="expiry_date">{{__('main.expiry_date')}}</label>
          <div class="col-sm-10">
            <input class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date" name="expiry_date" type="date" min="{{date("Y-m-d")}}" placeholder="{{__('main.Enter Expiry Date')}}" value="{{$couponDetails->expiry_date}}" required autofocus>
          </div>
        </div>
        <div class="form-group mb-4 row">
            <label class="col-sm-2 col-form-label" for="status">{{__('main.status')}}</label>
            <div class="col-sm-10">
                <div class="form-check form-switch">

                <input type="checkbox" class="form-check-input" id="status" name="status" value="{{ old($couponDetails->status) }}" @if (old('status', $couponDetails->status)) checked @endif />

            </div>
            </div>
        </div>
        <div class="col-6 mb-3">
          <a class="btn btn-secondary mr-2" href="{{route('admin.couponList')}}">{{__('main.cancel')}}</a>
          <button class="btn btn-primary" type="submit">{{__('main.save_changes')}}</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@section('script')
<script src="{{asset('assets/libs/jquery-validation-1.17.0/dist/jquery.validate.js')}}"></script>
<script src="{{asset('assets/libs/select2/js/select2.full.min.js')}}"></script>
<script>
  $(document).ready(function() {
    $('.selectpicker').select2();
    var rowIdx = $('#tbody tr:last td:last').attr("id");
    $('#add_new_option_button').on('click', function () {
      $('#tbody').append(`<tr class="R" id="R${rowIdx++}">
          <td class="row-index">
            <label class="small mb-1 @error('minimum_value') is-invalid @enderror" for="minimum_value">{{__('main.minimum_value')}}</label>
            <input class="form-control" id="R${rowIdx}" name="range[${rowIdx}][]" type="number" min="0" placeholder="{{__('main.Enter Min. Value')}}" required autofocus>
          </td>
          <td class="row-index">
            <label class="small mb-1 @error('maximum_vlaue') is-invalid @enderror" for="maximum_vlaue">{{__('main.maximum_value')}}</label>
            <input class="form-control" id="R${rowIdx}" name="range[${rowIdx}][]" type="number" min="0" placeholder="{{__('main.Enter Max. Value')}}" required autofocus>
          </td>
          <td class="row-index">
            <label class="small mb-1 @error('amount') is-invalid @enderror" for="amount">{{__('main.discount_value')}}</label>
            <input class="form-control" id="amount" name="range[${rowIdx}][]" type="number" min="0" required autofocus>
          </td>
          <td class="text-center align-bottom" id="${rowIdx}">
            <button class="btn btn-danger remove" type="button">{{__('main.remove')}}</button>
          </td>
        </tr>`);
    });

    $('#tbody').on('click', '.remove', function () {
      var child = $(this).closest('tr').nextAll();
      child.each(function () {
        var id = $(this).attr('id');
        var idx = $(this).children('.row-index').children('p');
        var dig = parseInt(id.substring(1));
        $(this).attr('id', `R${dig - 1}`);
      });
      $(this).closest('tr').remove();
      rowIdx--;
    });

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#deleteCoupon').click(function(e) {
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
            "_token": $('a[name="csrf-token"]').val(),
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

    $("#updateCouponForm").validate({
      rules: {
        "amount": {
          required: true,
          minlength: 1,
          maxlength: 10,
          number: true
        },
        "categories": {
          required: true,
        },
        "user_type": {
          required: true,
        },
        "expiry_date" : {
          required: true,
        }
      },
      messages: {
        "amount": {
          required: "Enter Vaild Amount",
        },
        "categories": {
          required: "Select at least one Category",
        },
        "user_type": {
          required: "Select any one option",
        },
        "expiry_date": {
          required: "Enter Expiry Date",
        }
      }
    });
  });
</script>
@endsection
