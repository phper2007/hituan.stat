@extends('layouts.app')
@section('title', ($address->id ? '修改': '新增') . '收货地址')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center">
                        {{ $address->id ? '修改': '新增' }}收货地址
                    </h2>
                </div>
                <div class="panel-body">
                    @include('layouts._error')
                        @if($address->id)
                            <form class="form-horizontal" role="form" action="{{ route('user_addresses.update', ['user_address' => $address->id]) }}" method="post">
                                {{ method_field('PUT') }}
                        @else
                            <form class="form-horizontal" role="form" action="{{ route('user_addresses.store') }}" method="post">
                        @endif
                        <!-- 引入 csrf token 字段 -->
                        {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label col-sm-2">省</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="province" value="{{ old('province', $address->province) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">市</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="city" value="{{ old('city', $address->city) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">县、区</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="district" value="{{ old('district', $address->district) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">详细地址</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="address" value="{{ old('address', $address->address) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="contact_name" value="{{ old('contact_name', $address->contact_name) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">电话</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="contact_phone" value="{{ old('contact_phone', $address->contact_phone) }}">
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button name="save" type="submit" class="btn btn-info">保存</button>
                                <button name="saveAndOrder" type="submit" class="btn btn-primary">保存并下单</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection