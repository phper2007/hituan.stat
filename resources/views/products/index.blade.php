@extends('layouts.app')
@section('title', '产品信息列表')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    产品信息列表
                    {{--<a href="{{ route('products.create') }}" class="pull-right">新增产品&nbsp;&nbsp;</a>--}}
                    <a href="{{ route('products.import') }}" class="pull-right">导入接龙信息&nbsp;&nbsp;</a>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>团购日期</th>
                            <th>产品名称</th>
                            <th>成本价</th>
                            <th>售价</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productList as $product)
                            <tr>
                                <td>{{ $product->product_date }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->cost_price }}</td>
                                <td>{{ $product->sell_price }}</td><td>
                                    <a href="{{ route('products.edit', ['user_address' => $product->id]) }}" class="btn btn-primary">修改</a>
                                    <!-- 把之前删除按钮的表单替换成这个按钮，data-id 属性保存了这个地址的 id，在 js 里会用到 -->
                                    <button class="btn btn-danger btn-del-address" type="button" data-id="{{ $product->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scriptsAfterJs')
    <script>
        $(document).ready(function() {
            // 删除按钮点击事件
            $('.btn-del-address').click(function() {
                // 获取按钮上 data-id 属性的值，也就是地址 ID
                var id = $(this).data('id');
                // 调用 sweetalert
                swal({
                    title: "确认要删除该地址？",
                    icon: "warning",
                    buttons: ['取消', '确定'],
                    dangerMode: true,
                })
                    .then(function(willDelete) { // 用户点击按钮后会触发这个回调函数
                        // 用户点击确定 willDelete 值为 true， 否则为 false
                        // 用户点了取消，啥也不做
                        if (!willDelete) {
                            return;
                        }
                        // 调用删除接口，用 id 来拼接出请求的 url
                        axios.delete('/products/' + id)
                            .then(function () {
                                // 请求成功之后重新加载页面
                                location.reload();
                            })
                    });
            });
        });
    </script>
@endsection