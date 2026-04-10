@extends('user-front.seabbq-desifoodie-desices.layout')
@section('pageHeading')
    {{ $keywords['Home'] ?? __('Home') }}
@endsection
@section('style')
    @include('user-front.seabbq-desifoodie-desices.desifoodie.include.css')
@endsection
@section('content')
    @include('user-front.seabbq-desifoodie-desices.desifoodie.heroSeaction')

    @if ($userBs->menu_section == 1)
        @include('user-front.seabbq-desifoodie-desices.desifoodie.featured-item')
    @endif






@endsection
@section('script')
    @include('user-front.seabbq-desifoodie-desices.desifoodie.include.script')
@endsection