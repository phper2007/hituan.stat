@extends('layouts.app')
@section('title', '导入内部报价信息')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center">
                        导入内部报价信息
                    </h2>
                </div>
                <div class="panel-body">
                    @include('layouts._error')
                    <form class="form-horizontal" role="form" action="{{ route('products.import_offer_analysis') }}" target="_blank" method="post">
                    <!-- 引入 csrf token 字段 -->
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-sm-2">内部报价信息</label>
                            <div class="col-sm-9">
                                <textarea name="content" class="form-control" rows="15"></textarea>
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