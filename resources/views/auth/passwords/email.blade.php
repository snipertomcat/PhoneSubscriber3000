@extends('layouts.full_page')

@section('title', trans('messages.email'))

@push('styles')
    <style type="text/css">
        .page-content {
            background-color: white;
        }
    </style>
@endpush

@section('body')
    <recovery-password>
        <template slot="token">
            {{ csrf_field() }}
        </template>
    </recovery-password>
@endsection
