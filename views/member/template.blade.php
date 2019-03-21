@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="user group icon"></i>
        <div class="content">
            {{ ___('Member Management') }}
            <div class="sub header">{{ ___('Approve, delete and edit members.') }}</div>
        </div>
    </h1>
@endsection
