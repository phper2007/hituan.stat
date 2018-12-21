@extends('layouts.app')
@section('title', '订单列表')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    订单列表
                    <a href="{{ route('orders.bill') }}" class="pull-right">报单&nbsp;&nbsp;</a>
                    <a href="{{ route('orders.stat') }}" class="pull-right">本月业绩&nbsp;&nbsp;</a>
                </div>
                <div class="panel-body">
                    <form class="form-inline" method="get" action="{{route('orders.index')}}">
                        <div class="form-group">
                            <input type="text" class="form-control" name="keywords" value="{{$keywords}}">
                        </div>
                        <button type="submit" class="btn btn-default">搜索</button>
                    </form>
                </div>

                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>收货人信息</th>
                            <th>产品</th>
                            <th>数量</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->contact_name }} {{ $order->contact_phone }}<br />{{ $order->full_address }}</td>
                                <td>{{ $order->product_name }}</td>
                                <td>{{ $order->sell_count }}</td>
                                <td>
                                    <a href="{{ route('orders.check', ['order' => $order->id]) }}" class="btn btn-primary">报单信息</a>
                                    {{--<a href="{{ route('orders.edit', ['user_address' => $order->id]) }}" class="btn btn-primary">修改</a>--}}
                                    <button class="btn btn-danger btn-del-address" type="button" data-id="{{ $order->id }}">删除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row" align="center">{{ $orders->links() }}</div>
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
                        axios.delete('/orders/' + id)
                            .then(function () {
                                // 请求成功之后重新加载页面
                                location.reload();
                            })
                    });
            });
        });
    </script>
@endsection