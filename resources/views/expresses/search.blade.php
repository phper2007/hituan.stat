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

                <h2>共查询到{{$expressList->count()}}条快递</h2>

                @include('expresses._list', ['expressList' => $expressList])

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