@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
@endphp

@extends('user-front.layout')
@section('pageHeading')
    {{ $keywords['Success'] ?? __('Success') }}
@endsection
@section('content')



    <div class="checkout-message">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="checkout-success">
                        <div class="icon text-success"><i class="far fa-check-circle"></i></div>
                        <h2>{{$keywords['Success'] ?? __('Success')}}!</h2>
                        @if (!empty($order->token_no))
                            <p class="mb-0">{{$keywords['Token No'] ?? __('Token No')}}:
                                <strong class="text-danger">
                                    {{$order->token_no}}
                                </strong>
                            </p>
                        @endif
                        <p class="mb-0">{{$keywords['Order Number'] ?? __('Order Number')}}: <strong
                                class="text-danger">#{{$orderNum ?? ''}}</strong></p>
                        <p class="mb-0">
                            {{$keywords['We have sent you a mail with an invoice'] ?? __('We have sent you a mail with an invoice.')}}
                        </p>
                        <p class="mt-3">{{$keywords['Thank you '] ?? __('Thank you')}}</p>
                        <a class="main-btn mt-4"
                            style="background-color: #0D5C53 !important; color: #fff !important; border-color: #0D5C53 !important;"
                            href="{{route('user.front.index', getParam())}}">{{$keywords['Return_to_Website'] ?? __('Return to Website')}}</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection