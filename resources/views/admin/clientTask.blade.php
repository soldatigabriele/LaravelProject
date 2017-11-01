@extends('layouts.app')

@section('content')

    <form action="/admin/user/{{$user_id}}" method="get">
        <input type="submit" class="btn btn-default" value="Go Back">
        {{csrf_field()}}
    </form>

    @if(isset($payment))
        <form action="/updatetag" method="post">
            {{csrf_field()}}
{{--            <input type="hidden" value="{{$uer_id}}" name="user_id">--}}
            <input type="hidden" value="payment" name="tag">
            <input type="hidden" value="{{$payment['id']}}" name="id">
            Description:
            <input type="text" value="{{$payment['description']}}" name="description">
            Amount:
            <input type="text" value="{{$payment['amount']}}" name="amount">
            <input type="submit" class="btn btn-success" name="update" value="update">
        </form>
    @endif

@endsection
