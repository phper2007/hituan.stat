@extends('layouts.app')
@section('title', '修改业绩基数')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center">
                        修改业绩基数
                    </h2>
                </div>
                <div class="panel-body">
                    @include('layouts._error')
                        <form class="form-horizontal" role="form" action="{{ route('orders.stat') }}" method="post">
                        <!-- 引入 csrf token 字段 -->
                        {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-sm-2">业绩基数</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="month_price" value="{{ old('month_price', $monthStat->month_price) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">利润基数</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="month_profit" value="{{ old('month_profit', $monthStat->month_profit) }}">
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