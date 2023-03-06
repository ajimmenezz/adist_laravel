<div class="modal fade" id="{{ $id }}" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title fw-bold fs-5">{{ $title }}</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                {!! $body !!}
                <div class="alert alert-success d-none" role="alert">
                    <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                    <span class="alert-inner--text"><strong>Success!</strong> <span
                            class="modal_success_message">{{ __('') }}</span></span>
                </div>

                <div class="alert alert-danger mb-0 d-none" role="alert">
                    <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                    <span class="alert-inner--text"><strong>Error!</strong> <span
                            class="modal_error_message">{{ __('') }}</span></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="{{ $buttonAcceptId }}">{{ $buttonAcceptLabel }}</button>
                <button class="btn btn-light" data-bs-dismiss="modal">{{ $buttonCloseLabel }}</button>
            </div>
        </div>
    </div>
</div>
