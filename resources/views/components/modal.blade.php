<!-- start page title -->
<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog {{isset($modalSize) ? $modalSize : "modal-xl" }} modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom">
          <h5 class="modal-title" id="exampleModalLabel">{{ $modalTitle }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
            {{$modalFormBody}}
        </div>
        <div class="modal-footer">
            {{$modalFormFooter}}
        </div>
      </div>
    </div>
  </div>
<!-- end page title -->

{{--
<!-- Scrollable modal -->
<div class="modal-dialog modal-dialog-scrollable">
modal-dialog modal-xl
  </div> --}}
