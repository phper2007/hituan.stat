@extends('layouts.app')
@section('title', '导入产品信息')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center">
                        {{date('Y年m月d日', $groupDate)}} 解析后数据
                    </h2>
                </div>
                <div class="panel-body">
                    @include('layouts._error')
                    <form class="form-horizontal" role="form" action="{{ route('products.store_import') }}" method="post">
                        <!-- 引入 csrf token 字段 -->
                        {{ csrf_field() }}
                        <input type="hidden" name="groupDate" value="{{$groupDate}}">
                        @foreach($productList as $key => $name)
                            <div class="form-group">
                                <label class="control-label col-sm-2">产品名称:{{ $key }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name[{{ $key }}]" value="{{ $name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">成本价</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="cost_price[{{ $key }}]" value="">
                                </div>
                                <label class="control-label col-sm-2">售价</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="sell_price[{{ $key }}]" value="">
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