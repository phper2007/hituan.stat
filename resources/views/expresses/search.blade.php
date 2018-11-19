@extends('layouts.app')
@section('title', '快递查询列表')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    快递查询列表
                </div>
                <div class="alert alert-success" role="alert">
                    {{$address->contact_name}} {{$address->contact_phone}}<br>
                    {{$address->full_address}}
                </div>

                <h2>共查询到{{$expressData['count']}}条快递</h2>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>商品名称</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($expressData['data'])
                            @foreach($expressData['data'] as $item)
                                <tr>
                                    <td>{{ $item['text'] }}</td>
                                    <td>
                                        <a href="{{ $item['url'] }}" target="_blank" class="btn btn-info">打开新窗口</a>
                                        <button type="button" class="viewExpress" href="{{ $item['url'] }}">查看详细</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <iframe width="550" height="700" frameborder="0" allowfullscreen=""></iframe>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scriptsAfterJs')
    <script>
        $('.viewExpress').click(function () {
            var src = $(this).attr('href');
            $('#myModal').modal('show');
            $('#myModal iframe').attr('src', src);
        });

        $('#myModal button').click(function () {
            $('#myModal iframe').removeAttr('src');
        });
    </script>
@endsection