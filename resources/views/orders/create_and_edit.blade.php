@extends('layouts.app')
@section('title', ($order->id ? '修改': '新增') . '订单')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center">
                        {{ $order->id ? '修改': '新增' }}订单
                    </h2>
                </div>
                <div class="panel-body">
                    @include('layouts._error')
                        @if($order->id)
                            <form class="form-horizontal" role="form" action="{{ route('orders.update', ['orders' => $order->id]) }}" method="post">
                                {{ method_field('PUT') }}
                        @else
                            <form class="form-horizontal" role="form" action="{{ route('orders.store') }}" method="post">
                        @endif
                        <!-- 引入 csrf token 字段 -->
                        {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-sm-2">收件人信息</label>
                                <div class="col-sm-9">
                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                    {{ $address->contact_name }} {{ $address->contact_phone }}<br />
                                    {{ $address->full_address }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">产品</label>
                                <div class="col-sm-9">
                                    {!! form_option($productRelate, old('product_id', $order->product_id), 'product_id') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">购买数量</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="sell_count" value="{{ old('sell_count', $order->sell_count) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">产品详细1</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="detail_name1" value="{{ old('detail_name1', $order->detail_name1) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">产品详细2</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="detail_name2" value="{{ old('detail_name2', $order->detail_name2) }}">
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">提交</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection