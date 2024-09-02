<div>
    <div wire:ignore.self class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">{{ $c_modal_heading }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body text-center">
                    {{ $c_modal_body }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn {{ $c_modal_btn_color }}" wire:click="{{ $c_modal_method }}">
                        {{ $c_modal_btn_text }}
                    </button>
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
