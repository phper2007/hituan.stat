@extends('layouts.app')
@section('title', '报单信息')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center">
                        报单信息
                    </h2>
                </div>
                <div class="panel-body">
                        <div class="form-group">
                            {{ $order->check_info }}
                        </div>
                        <div class="form-group text-center">
                            <a href="{{ route('orders.index') }}" class="btn btn-primary">返回订单信息</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection