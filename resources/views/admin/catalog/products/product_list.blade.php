@extends('layouts.admin.layout')
@section('title')
    {{__('main.all_products')}}
@endsection
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
@slot('li_1') {{__('main.index')}} @endslot
@slot('title') {{__('main.all_products')}} @endslot
@slot('link') {{ route('admin.productList')}} @endslot
@endcomponent
    <x-list_view>
        <x-slot name="card_heard"> {{__('main.all_products')}} </x-slot>
        <x-slot name="create_button_href"> {{route('admin.createProduct')}} </x-slot>
        <x-slot name="create_button_title"> {{__('main.create_product')}} </x-slot>
        <x-slot name="table_id"> product_table </x-slot>
        <x-slot name="table_th" id="product_table">
            <th>{{ __('main.id') }}</th>
            <th>{{ __('main.image') }}</th>
            <th>{{ __('main.product_name') }}</th>
            <th>{{ __('main.original_price') }}</th>
            <th>{{ __('main.is_active') }}</th>
            <th>{{ __('main.action') }}</th>
        </x-slot>
    </x-list_view>
@endsection
@section('script')
@include('layouts.admin.scripts.Datatables_scripts')
<script>
    // const lang = sessionStorage.getItem('lang');
    $(document).ready(function () {
        $('#product_table').DataTable({
            'paging'        : true,
            'lengthChange'  : false,
            'searching'     : false,
            'ordering'      : true,
            'info'          : true,
            'autoWidth'     : false,
            "processing"    : true,
            "serverSide"    : true,
            "ajax"          : {
                                "url": "{!! route('dataTable.dataTableProductTable') !!}",
                                "type": "GET",
                                "data": function ( d ) {
                                        d.filterSearchKey = $("#filter_search_key").val();
                                        // d.filterStatus = $("#filter_status").val();
                                        // d.filterUserType = $("#filter_user_type").val();
                                }
                            },
            "columns"       : [
                                    {   "data": "id",
                                        "render": function(data,type,row)
                                        {
                                            return data;
                                        }
                                    },
                                    {   "data": "media_id",
                                        "render": function(data,type,row)
                                        {
                                            return (row.media && row.media !== null) ? '<img src='+row.product_image+' alt='+row.name+' class="img-fluid d-block">' : '';
                                        }
                                    },
                                    {   "data": "name",
                                        "render": function(data,type,row)
                                        {
                                            return data;

                                        }
                                    },
                                    {   "data": "price",
                                        "render": function(data,type,row)
                                        {return data;
                                        }
                                    },
                                    {
                                        "data": "is_active",
                                        "render": function ( data, type, row ) {
                                            return data;
                                        },
                                    },
                                    {   "data": "Action",
                                    "render": function(data,type,row)
                                    {
                                        return data;
                                    }
                                },
                            ],
            'columnDefs': [
                            {
                                    "targets": 0,
                                    "className": "text-center",
                            },
                            {
                                    "targets": 1,
                                    "className": "text-center avatar-sm bg-light rounded p-1",
                            },
                            {
                                    "targets": 2,
                                    "className": "text-center",
                            },
                            {
                                    "targets": 3,
                                    "className": "text-center",
                            },
                        ],
            // language: {
            //                 url: '@if (session()->get('locale') == 'ar') {{asset('js/Arabic.json')}} @elseif(session()->get('locale') == 'fr') {{asset('js/French.json')}} @endif'
            //             }
        });
    });

    $("#search_filter").click(function (e) {
        e.preventDefault();
        $('#usersTable').DataTable().ajax.reload();
    });

    $("#reset_filter").click(function (e) {
        e.preventDefault();
        $('#filter_search_key').val('');
        $('#filter_status').prop('selectedIndex',0);
        $('#filter_user_type').prop('selectedIndex',0);
        $('#usersTable').DataTable().ajax.reload();
    });
</script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $('body').on('click','.remove-item-btn',function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this Product!",
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
                type: "GET",
                url: "{{ route('admin.destroyProduct', '') }}" + "/" + id,
                data: data,
                success: function(response) {
                    swal(response.status, {
                        icon: "success",
                        timer: 3000,
                    })
                    .then((result) => {
                        window.location =
                        '{{ route('admin.productList') }}'
                    });
                }
                });
            }
            });
        });


    });

</script>
@endsection
