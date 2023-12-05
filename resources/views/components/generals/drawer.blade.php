<div class="offcanvas offcanvas-end" tabindex="-1" id="{{ $id }}" aria-labelledby="{{ $id }}Label">
    <div class="offcanvas-header border border-1">
        <h5 class="offcanvas-title" id="{{ $id }}Label">{{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body overflow-y-auto">
        {!! $content !!}
    </div>
</div>
