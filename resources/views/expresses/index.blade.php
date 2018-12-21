@extends('layouts.app')
@section('title', '快递列表')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    快递列表
                </div>
                <div class="panel-body">
                    <form class="form-inline" method="get" action="{{route('expresses.index')}}">
                        <div class="form-group">
                            <input type="text" class="form-control" name="keywords" value="{{$keywords}}">
                        </div>
                        <div class="form-group">
                            {!! form_option($expressStatusDict, $status, 'status', '全部') !!}
                        </div>
                        <button type="submit" class="btn btn-default">搜索</button>
                    </form>
                </div>

                @include('expresses._list', ['expressList' => $expresses])

                <div class="row" align="center">{{ $expresses->links() }}</div>
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

@include('expresses._after_js')