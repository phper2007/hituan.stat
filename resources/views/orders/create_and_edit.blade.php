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
                                <label class="control-label col-sm-2">购买份数</label>
                                <div class="col-sm-9">
                                    {!! form_option($orderCountDict, old('sell_count', $order->sell_count), 'sell_count') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">额外运费</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="freight" value="{{ old('freight', $order->freight) }}">
                                </div>
                            </div>
                            @foreach(range(1,5) as $num)
                                <div class="form-group">
                                    <label class="control-label col-sm-2">产品详细{{$num}}</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="detail_name{{$num}}" value="{{ old('detail_name'.$num, $order->{'detail_name'.$num}) }}">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="detail_count{{$num}}" value="{{ old('detail_count'.$num, $order->{'detail_count'.$num}) }}">
                                    </div>
                                </div>
                            @endforeach
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">提交</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection