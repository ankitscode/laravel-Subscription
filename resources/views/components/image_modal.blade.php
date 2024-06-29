<!-- start page title -->
<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ $modalTitle }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <form class="form" method="POST" action="{{ $modalFormAction }}" id="{{ $modalFormId }}" enctype="multipart/form-data">
              @csrf
              {{$modalFormData}}
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" form="{{ $modalFormId }}">{{ $modalFormSumbitText }}</button>
        </div>
      </div>
    </div>
</div>
<!-- end page title -->
