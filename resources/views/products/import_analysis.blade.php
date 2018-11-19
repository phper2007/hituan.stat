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
                            <div class="row @if($key % 2 === 0) bg-success @endif">
                            <div class="form-group">
                                <label class="control-label col-sm-2">产品名称:{{ $key }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name[{{ $key }}]" value="{{ $name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">经销价</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" name="cost_price[{{ $key }}]" value="{{ $costPrice[$key] }}" pattern="[0-9]*">
                                </div>
                                <label class="control-label col-sm-2">团购价</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" name="sell_price[{{ $key }}]" value="{{ $sellPrice[$key] }}" pattern="[0-9]*">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">报单格式</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="order_format[{{ $key }}]" value="{{ $orderFormat[$key] }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">每单数量</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" name="product_number[{{ $key }}]" value="{{ $productNumber[$key] }}" pattern="[0-9]*">
                                </div>
                                <label class="control-label col-sm-2">商品单位</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="product_unit[{{ $key }}]" value="{{ $productUnit[$key] }}">
                                </div>
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