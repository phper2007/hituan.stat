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
                        <p class="text-muted">
                            @foreach($productSum as $productId => $count)
                                {{$productRelate[$productId]}}。{{$count}}<br />
                            @endforeach
                                <br />
                            {{date('m月d日', strtotime($maxDate))}}<br />
                            合计金额： {{$today['sellPrice']}}<br />
                            合计单数:{{$today['sellCount']}}<br />
                            今日利润:{{$today['profit']}}<br />
                            -----------------------------------<br />
                            本月总业绩<br />
                            本月总利润
                        </p>

                    @foreach($orderGroup as $orders)
                        <blockquote>
                            <p>
                        @foreach($orders as $order)
                            {{$order->check_info}}<br />
                        @endforeach
                            </p>
                        </blockquote>
                    @endforeach

                    <p class="text-warning">
                        今日运费合计：{{$today['freight']}}，未计算到合计金额中。
                    </p>
                        <div class="form-group text-center">
                            <a href="{{ route('orders.index') }}" class="btn btn-primary">返回订单信息</a>
                        </div>
                </div>


            </div>
        </div>
    </div>
@endsection