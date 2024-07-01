@extends('layouts.admin.layout')
@section('title')
    {{ __('main.view_admin')}}
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') {{__('main.view')}} @endslot
@slot('title') {{__('main.admin')}} @endslot
@slot('link') {{ route('admin.subscription')}} @endslot
@endcomponent
<div class="container"> 
<div class="row justify-content-center">
    <div class="col-10">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center">
                <h6 class="m-0 font-weight-bold text-primary flex-grow-1">{{__('Subscrition Detail')}}</h6>
                <div class="flex-shrink-0">
                    <a href="javascript:void(0)" class="btn btn-disable"  class="btn btn-primary edit-item-btn" href="{{ route('admin.subscriptionEdit', $subscription->id) }}" ><i class="ri-edit-line fs-16"></i></a>

                    <a href="javascript:void(0)" class="btn btn-disable"   class="btn btn-danger remove-item-btn"  href="{{ route('admin.subscriptionDestroy', $subscription->id) }}"
                        ><i class="ri-delete-bin-2-line fs-16"></i></a> 
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('Subscription Name') }}</td>
                                <td>{{ isset($subscription->name) ? $subscription->name : '' }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('Duration') }}</td>
                                <td>{{ isset($subscription->duration) ? $subscription->duration : '' }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold-600" style="width: 25%;">{{ __('Amount') }}</td>
                                <td>{{ isset($subscription->amount) ? $subscription->amount : '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script>
$(document).ready(function () {
    $('.dropify').dropify();
});
</script>
<script>
    $(document).ready(function() {
        $('body').on('click','.remove-item-btn',function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this user!",
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
                url: "{{ route('admin.destroyAdmin', '') }}" + "/" + id,
                data: data,
                success: function(response) {
                    swal(response.status, {
                        icon: "success",
                        timer: 3000,
                    })
                    .then((result) => {
                        window.location =
                        '{{ route('admin.adminList') }}'
                    });
                }
                });
            }
            });
        });
    });
</script>
@endsection
