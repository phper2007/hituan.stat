@extends('layouts.app')
@section('title', '导入地址信息')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center">
                        导入地址信息
                    </h2>
                </div>
                <div class="panel-body">
                    @if($errors)
                    <div class="alert alert-danger">
                        <h4>有错误发生：</h4>
                        <ul>
                            @foreach ($errors as $error)
                                <li><i class="glyphicon glyphicon-remove"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form class="form-horizontal" role="form" action="{{ route('user_addresses.standard') }}" method="post">
                    <!-- 引入 csrf token 字段 -->
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-sm-2">地址信息</label>
                            <div class="col-sm-9">
                                <textarea name="content" class="form-control" rows="10">{{$content}}</textarea>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button name="save" type="submit" class="btn btn-primary">保存</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection