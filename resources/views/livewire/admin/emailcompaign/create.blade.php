<div>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/45.2.0/ckeditor5.css" crossorigin>
    <link rel="stylesheet"
        href="https://cdn.ckeditor.com/ckeditor5-premium-features/45.2.0/ckeditor5-premium-features.css" crossorigin>
    <style>
        .ck-editor__main {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
    <div class="row">
        <div class="col-md-6">
            <div class="col-10 mb-3 d-flex justify-content-between">
                <button class="btn btn-outline-dark" wire:click="$set('step', 1)" @disabled($step === 1)>
                    Step 1: Select Template
                </button>
                <button class="btn btn-outline-dark" wire:click="$set('step', 2)" @disabled($step === 2)>
                    Step 2: Compose Email
                </button>
            </div>

            {{-- Step 1: Template Selection --}}
            @if ($step === 1)
                <div class="col-12">
                    <label class="mb-2">Choose a Template</label>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach ($templates as $template)
                            <div class="p-2 border rounded template-card"
                                style="
                        border: 2px solid {{ $selectedTemplateId === $template->id ? '#007bff' : '#ccc' }};
                        cursor: pointer;
                        width: 45%;
                        transition: border 0.3s;
                        background-color: {{ $selectedTemplateId === $template->id ? '#e9f5ff' : '#fff' }};
                    "
                                wire:click="selectTemplate({{ $template->id }})">
                                <strong>{{ $template->name }}</strong>
                                <div class="small text-muted">Preview:</div>
                                <div style="max-height: 80px; overflow: hidden; font-size: 12px;">
                                    {!! $template->html !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Step 2: Email Form --}}
            @if ($step === 2)
                <div class="col-12">
                    @if ($errors->any())
                        <div class="alert alert-danger mt-2">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div wire:ignore class="mb-3">
                            <label for="customMessage">Body Text</label>
                            <div id="customMessage" contenteditable="true" class="form-control"
                                style="max-height: 200px;">
                                {!! $bodyText !!}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Subject</label>
                            <input type="text" wire:model.live="subject" class="form-control">
                        </div>

                        {{-- <div class="col-md-6 mb-3">
                            <label>Optional Button Text</label>
                            <input type="text" wire:model.live="buttonText" class="form-control"
                                placeholder="Leave blank to hide button">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Optional Button URL</label>
                            <input type="text" wire:model.live="buttonUrl" class="form-control"
                                placeholder="https://example.com">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Background Color</label>
                            <input type="color" wire:model.live="bgColor" class="form-control form-control-color">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Text Color</label>
                            <input type="color" wire:model.live="textColor" class="form-control form-control-color">
                        </div> --}}

                        <div class="row">
                            <div class="col-md-6 mb-3 d-flex align-items-center">
                                <label class="w-100">
                                    <input type="checkbox" wire:model.live="selectAll">
                                    Send to All Users
                                </label>
                            </div>

                            @unless ($selectAll)
                                <div class="col-md-6 mb-3">
                                    <input type="text" wire:model.live="search" class="form-control"
                                        placeholder="Search by name or email...">
                                </div>

                                <div class="col-12 mb-3">
                                    <div style="max-height: 200px; overflow-y: auto;" class="border p-2 rounded">
                                        @forelse ($this->filteredUsers as $user)
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"
                                                    wire:model.live="selectedUsers" value="{{ $user->id }}"
                                                    id="user-{{ $user->id }}">
                                                <label class="form-check-label" for="user-{{ $user->id }}">
                                                    {{ $user->name }} ({{ $user->email }})
                                                </label>
                                            </div>
                                        @empty
                                            <div>No users found.</div>
                                        @endforelse
                                    </div>
                                </div>
                            @endunless
                        </div>
                        <div class="col-12">
                            <button wire:click="sendEmail" class="btn btn-primary mt-3">Send Email</button>
                        </div>

                        @if (session()->has('message'))
                            <div class="col-12">
                                <div class="alert alert-success mt-2">{{ session('message') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6 mt-lg-5">
            <h3 class="mb-3">Email Preview</h3>
            <div class="p-4 border rounded editor-content" style="max-height: 500px; overflow-y: auto;">
                <div style="white-space: pre-line;">{!! $bodyText !!}</div>
                @if ($buttonText && $buttonUrl)
                    <div style="text-align: {{ $textAlign }};">
                        <a href="{{ $buttonUrl }}" class="btn btn-primary mt-2">{{ $buttonText }}</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Preview --}}

    </div>

    {{-- CKEditor Script --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/45.2.0/ckeditor5.umd.js" crossorigin></script>
    <script src="https://cdn.ckeditor.com/ckeditor5-premium-features/45.2.0/ckeditor5-premium-features.umd.js" crossorigin>
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let editorInstance;

            const initEditor = () => {
                const el = document.querySelector('#customMessage');
                if (!el) return;
                const {
                    ClassicEditor,
                    Alignment,
                    Autoformat,
                    AutoImage,
                    AutoLink,
                    Autosave,
                    Bold,
                    CKBox,
                    Base64UploadAdapter,
                    CKBoxImageEdit,
                    CloudServices,
                    Emoji,
                    Essentials,
                    FontBackgroundColor,
                    FontColor,
                    FontFamily,
                    FontSize,
                    GeneralHtmlSupport,
                    Heading,
                    ImageEditing,
                    ImageInline,
                    ImageInsert,
                    ImageInsertViaUrl,
                    ImageResize,
                    ImageStyle,
                    ImageTextAlternative,
                    ImageToolbar,
                    ImageUpload,
                    ImageUtils,
                    Indent,
                    IndentBlock,
                    Italic,
                    Link,
                    List,
                    ListProperties,
                    Mention,
                    Paragraph,
                    PasteFromOffice,
                    PictureEditing,
                    PlainTableOutput,
                    RemoveFormat,
                    Strikethrough,
                    Style,
                    Table,
                    TableCaption,
                    TableCellProperties,
                    TableColumnResize,
                    TableLayout,
                    TableProperties,
                    TableToolbar,
                    TextTransformation,
                    SourceEditing,
                    Underline
                } = CKEDITOR;

                ClassicEditor
                    .create(el, {
                        licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NTEzMjc5OTksImp0aSI6IjIzMjM4Yzk0LTY5MDItNGQwYS05NzRkLTc4ZjhjYzYwYWQ2MyIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6IjgyNzI4Njg1In0.jNt5hkQCi_oGTQbjZS7jO3murPjl-v0uF3Y_SQO_CYixv99NL1oBWbSH8kGDISihC8sw7GWn_iwDFUrq29DOWQ',
                        toolbar: ['bold', 'italic', 'link', '|', 'undo', 'redo', 'imageUpload',
                            'resizeImage', 'sourceEditing', '|', 'heading', 'alignment',
                            'fontBackgroundColor', 'fontColor', 'fontFamily', 'fontSize',
                            'bulletedList', 'numberedList', 'todoList', '|', 'insertTable',
                            'blockQuote', 'codeBlock', 'removeFormat', '|', 'strikethrough',
                            'underline', 'style', 'emoji', '|', 'ckboxImageEdit',
                            'ckbox', 'mention', 'pasteFromOffice', '|', 'textTransformation',
                            'indent', 'outdent', '|', 'tableColumn', 'tableRow',
                            'mergeTableCells', 'tableProperties', 'tableCellProperties',
                            'tableToolbar', 'tableCaption', 'tableLayout', 'tableColumnResize',
                            'imageStyle:inline', 'imageStyle:alignLeft', 'imageStyle:alignRight',
                            '|', 'imageTextAlternative', 'imageInsertViaUrl',
                            'imageInline', 'imageEditing', 'imageInsert', 'imageToolbar',
                            'imageUtils', 'pictureEditing', 'plainTableOutput'

                        ],
                        plugins: [
                            Alignment,
                            Autoformat,
                            AutoImage,
                            AutoLink,
                            Autosave,
                            Bold,
                            Base64UploadAdapter,
                            CKBox,
                            CKBoxImageEdit,
                            CloudServices,
                            Emoji,
                            Essentials,
                            SourceEditing,
                            FontBackgroundColor,
                            FontColor,
                            FontFamily,
                            FontSize,
                            GeneralHtmlSupport,
                            Heading,
                            ImageEditing,
                            ImageInline,
                            ImageInsert,
                            ImageInsertViaUrl,
                            ImageResize,
                            ImageStyle,
                            ImageTextAlternative,
                            ImageToolbar,
                            ImageUpload,
                            ImageUtils,
                            Indent,
                            IndentBlock,
                            Italic,
                            Link,
                            List,
                            ListProperties,
                            Mention,
                            Paragraph,
                            PictureEditing,
                            PlainTableOutput,
                            RemoveFormat,
                            Strikethrough,
                            Style,
                            Table,
                            TableCaption,
                            TableCellProperties,
                            TableColumnResize,
                            TableLayout,
                            TableProperties,
                            TableToolbar,
                            TextTransformation,
                            Underline
                        ],
                        htmlSupport: {
                            allow: [{
                                name: /^(div|table|tbody|tr|td|span|img|h1|h2|h3|p|a)$/,
                                styles: true,
                                attributes: true,
                                classes: true
                            }]
                        },
                        image: {
                            toolbar: [
                                'imageTextAlternative',
                                '|',
                                'imageStyle:inline',
                                'imageStyle:alignLeft',
                                'imageStyle:alignRight',
                                '|',
                                'resizeImage',
                                '|',
                                'ckboxImageEdit'
                            ],
                            styles: {
                                options: ['inline', 'alignLeft', 'alignRight']
                            }
                        },
                    })
                    .then(editor => {
                        editorInstance = editor;

                        editor.model.document.on('change:data', () => {
                            @this.set('bodyText', editor.getData());
                        });

                        window.addEventListener('refreshEditor', e => {
                            editor.setData(e.detail.content || '');
                        });

                        window.addEventListener('clearEditorContent', () => {
                            editor.setData('');
                        });
                    })
                    .catch(error => {
                        console.error('CKEditor init failed:', error);
                    });
            };

            initEditor();

            Livewire.hook('message.processed', () => {
                const el = document.querySelector('#customMessage');
                if (el && el.isConnected && (!editorInstance || !editorInstance.ui.view.editable.element
                        .isConnected)) {
                    initEditor();
                }
            });
        });

        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                icon: event.detail.type,
            });
        });
    </script>
</div>
