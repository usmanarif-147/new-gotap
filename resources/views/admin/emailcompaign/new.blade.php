@extends('layouts.admin.layout')

@section('content')
    <div id="root"></div>
    <div class="email-preview" id="emailContainer"></div>
@endsection

@section('script')
    <script type="module">
        const CONFIGURATION = {
            root: {
                type: 'EmailLayout',
                data: {
                    backdropColor: '#F8F8F8',
                    canvasColor: '#FFFFFF',
                    textColor: '#242424',
                    fontFamily: 'MODERN_SANS',
                    childrenIds: ['block-1709578146127'],
                },
            },
            'block-1709578146127': {
                type: 'Text',
                data: {
                    style: {
                        fontWeight: 'normal',
                        padding: {
                            top: 16,
                            bottom: 16,
                            right: 24,
                            left: 24,
                        },
                    },
                    props: {
                        text: 'Hello world',
                    },
                },
            },
        };



        const html = renderToStaticMarkup(CONFIGURATION, {
            rootBlockId: 'root'
        });
        document.getElementById('emailContainer').innerHTML = html;
    </script>
@endsection
