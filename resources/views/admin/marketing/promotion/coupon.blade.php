@extends('layouts.admin.layout')
@section('title') {{__('main.coupon_list')}} @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/swiper/swiper.min.css') }}" rel="stylesheet" type="text/css" />
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href={{asset("assets/css/dropify.css")}}>
@endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1') {{ __('main.coupon') }} @endslot
    @slot('li_2') {{ __('main.promotion') }} @endslot
    @slot('title') {{__('main.marketing')}} @endslot
    @slot('link') {{ route('admin.couponList')}} @endslot
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">{{__("main.coupon")}}</h5>
                <div>
                    <a type="button" class="btn btn-secondary add-btn" href="{{route('admin.createCoupon')}}" id="create-btn">
                        <i class="ri-add-line align-bottom me-1"></i> {{__('main.create_coupon')}}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="all_coupon_list" class="table table-nowrap dt-responsive table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th>{{ __('main.id') }}</th>
                            <th>{{ __('main.coupon_option') }}</th>
                            <th>{{ __('main.coupon_rule') }}</th>
                            <th>{{ __('main.coupon_code') }}</th>
                            <th>{{ __('main.uses_per_customer') }}</th>
                            <th>{{ __('main.amount_type') }}</th>
                            <th>{{ __('main.amount') }}</th>
                            <th>{{ __('main.expiry_date') }}</th>
                            <th>{{ __('main.status') }}</th>
                            <th>{{ __('main.action')}}</th>
                        </tr>
                    </thead>
                </table>
                <!--end modal -->
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@include('layouts.admin.scripts.Datatables_scripts')
<script>
  $(document).ready(function (){
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    $('#all_coupon_list').DataTable({
      'paging'            : true,
      'lengthChange'      : false,
      'searching'         : false,
      'ordering'          : true,
      'info'              : true,
      'autowidth'         : false,
      "processing"        : true,
      "serverSide"        : true,
      "ajax"              : {
                          "url": "{!! route('dataTable.dataTableAllCoupon') !!}",
                          "type": "GET",
                          "data": function ( d ) {
                              d.filterSearchKey = $("#filter_search_key").val();
                          }
                      },
      "columns"       : [
                          {   "data": "id"},

                          {   "data": "coupon_option"},

                          {   "data": "lockup",
                          "render":function(data,type,row){
                                  return data.name.en;
                              }
                        },

                          {   "data": "coupon_code"},

                          {   "data": "uses_per_customer"},

                          {   "data": "amount_type"},

                          {   "data": "amount"},

                          {   "data": "expiry_date"},

                          {
                            "data": "status",
                            "render": function ( data, type, row ) {
                            if(data == 1){
                                return '<a href="#"><span class="badge badge-soft-success text-uppercase">Active</span></a>';
                            }else{
                                return '<a href="#"><span class="badge badge-soft-danger text-uppercase">Not Active</span></a>';
                                }
                            },
                        },
                          {
                              "data":"id",
                              "render":function(data,type,row){
                                return '<li class="list-inline-item edit"><a href="{{route("admin.viewCoupon","")}}/'+row.id+'" data-id="'+row.id+'" class="text-primary d-inline-block edit-btn"><i class="ri-eye-fill fs-16"></i></a></li><li class="list-inline-item edit"><a href="{{ route('admin.editCoupon',"") }}/'+row.id+'" data-id="'+row.id+'" class="text-primary d-inline-block edit-btn"><i class="ri-pencil-fill fs-16"></i></a></li><li class="list-inline-item"><a class="text-danger d-inline-block remove-item-btn"  data-id="'+row.id+'" href="javascript:void(0)"><i class="ri-delete-bin-5-fill fs-16"></i></a></li>';
                              }
                          }

                        ],
      'columnDefs': [
                     {
                              "targets": 0,
                              "className": "text-center",
                              "width": "2%"
                      },
                      {
                              "targets": 1,
                              "className": "text-center",
                              "width": "2%"
                      },
                      {
                              "targets": 2,
                              "className": "text-center",
                              "width": "2%"
                      },
                      {
                              "targets": 3,
                              "className": "text-center",
                              "width": "2%"
                      },
                      {
                              "targets": 4,
                              "className": "text-center",
                              "width": "2%"
                      },
                      {
                              "targets": 5,
                              "className": "text-center",
                              "width": "2%"
                      },
                      {
                              "targets": 6,
                              "className": "text-center",
                              "width": "2%"
                      },
                      {
                              "targets": 7,
                              "className": "text-center",
                              "width": "2%"
                      },
                      {
                              "targets": 8,
                              "className": "text-center",
                              "width": "2%"
                      },
                      {
                              "targets": 9,
                              "className": "text-center",
                              "width": "2%"
                      },


                  ],

    });
  });
  $("#search_filter").click(function (d) {
        d.preventDefault();
        $('#attributeSetTable').DataTable().ajax.reload();
    });

    $("#reset_filter").click(function (d) {
        d.preventDefault();
        $('#filter_search_key').val('');
        $('#attributeSetTable').DataTable().ajax.reload();
    });

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
</script>
@endsection
