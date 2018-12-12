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
                    <div class="alert alert-warning">本月总业绩基数：{{ $monthStat['month_price'] }}，本月总利润基数：{{ $monthStat['month_profit'] }}，最后更新时间：{{ $monthStat['updated_at'] }}</div>
                        <p class="text-muted">
                            @foreach($productSum as $productId => $count)
                                {{$productRelate[$productId]}}。{{$count}}<br />
                            @endforeach
                                <br />
                            {{date('m月d日', strtotime($maxDate))}}<br />
                            合计金额：{{$today['costPrice']}}<br />
                            合计单数：{{$today['sellCount']}}<br />
                            今日利润：{{$today['profit']}}<br />
                            -----------------------------------<br />
                            本月总业绩：{{$month['price']}}<br />
                            本月总利润：{{$month['profit']}}
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
                            <form class="form-horizontal" role="form" action="{{ route('orders.stat') }}" method="post">
                                <!-- 引入 csrf token 字段 -->
                                {{ csrf_field() }}
                                <input type="hidden" class="form-control" name="month_price" value="{{$month['price']}}">
                                <input type="hidden" class="form-control" name="month_profit" value="{{$month['profit']}}">
                                <a href="{{ route('orders.index') }}" class="btn btn-info">返回订单信息</a>
                                <button type="submit" class="btn btn-success">更新统计基数</button>
                            </form>
                        </div>
                </div>


            </div>


            </div>
        </div>
    </div>
@endsection