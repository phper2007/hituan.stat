@extends('layouts.app')
@section('title', ($product->id ? '修改': '新增') . '产品信息')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center">
                        {{ $product->id ? '修改': '新增' }}产品信息
                    </h2>
                </div>
                <div class="panel-body">
                    @include('layouts._error')
                        @if($product->id)
                            <form class="form-horizontal" role="form" action="{{ route('products.update', ['product' => $product->id]) }}" method="post">
                                {{ method_field('PUT') }}
                        @else
                            <form class="form-horizontal" role="form" action="{{ route('products.store') }}" method="post">
                        @endif
                        <!-- 引入 csrf token 字段 -->
                        {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-sm-2">团购日期</label>
                                <div class="col-sm-9">
                                    {{$product->group_date}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">产品名称:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">成本价</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">售价</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">报单格式</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="order_format" value="{{ old('order_format', $product->order_format) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">每单数量</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" name="product_number" value="{{ old('product_number', $product->product_number) }}" pattern="[0-9]*">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">商品单位</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="product_unit" value="{{ old('product_unit', $product->product_unit) }}">
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