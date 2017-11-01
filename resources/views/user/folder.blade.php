@extends('layouts.app')
@section('style')
    <style>
        html,body{
            background:#f5f5f5;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="col-md-10 col-lg-10 col-lg-offset-1 col-md-offset-1">
            @if(isset($folders))
                @if(count($folders))
                    <div class="col-md-12">
                        {{--<div class="col-md-2">--}}
                        <form action="/home" method="post">
                            {{--<input type="submit" class="btn btn-default" value="Go Back">--}}
                            {{csrf_field()}}
                        </form>
                        {{--</div>--}}
                        {{--Project name and id--}}
                        {{--<div class="col-md-10">--}}
                        <span class="project-name-title"> PROJECT NAME: {{  $project['name'] }}</span>
                        <div id="project-data" project-id="{{$project['id']}}" project-name="{{$project['name']}}"
                             class="hidden">{
                        </div>
                        {{--</div>--}}
                    </div>
                @endif
                <div class="clearfix"></div>
                <br>
        @endif
        <!-- Modal -->
            <div class="modal fade" id="tag-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xs-12 navigation-group">
                <a href="/project/{{$project['id']}}">
                    <div class="col-md-6 navigation-tab navigation-tab-inactive">
                        <span>TASKS</span>
                    </div>
                </a>
                <div class="col-md-6 navigation-tab navigation-tab-active">
                    <span>UPLOAD FOLDER</span>
                </div>
            </div>
            <div class="col-md-12 col-xs-12" id="content">
                <div class="col-md-12">

                    <div id="upload-group">
                        <form action="/upload" method="post" enctype="multipart/form-data">
                            <div class="col-md-3">
                                <div style="position:relative;">
                                   <span class='btn btn-primary'>Choose File
                                <a href='javascript:;'>
                                    <input type="file"
                                           class="btn btn-primary"
                                           value="Choose a File"
                                           style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;'
                                           name="fileToUpload" id="fileToUpload" size="40"
                                           onchange='$("#upload-file-info").html($(this).val());'>
                                </a>
                                   </span>
                                    &nbsp;
                                    <span class='label label-info' id="upload-file-info"></span>
                                </div>
                            </div>
                            <div class="form-group col-md-1">
                                <label for="name" style="position:relative;top:7px;">Name: </label>
                            </div>
                            <div class="form-group col-md-4">
                                <input type="text" class="form-control" value="default" name="name">
                            </div>
                            <input type="hidden" name="project_id" value="{{$project['id']}}">
                            <input type="hidden" name="folder_id" value="{{$data['folder_id']}}">
                            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                            {{csrf_field()}}
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-primary" name="upload" value="Upload">
                            </div>
                        </form>
                        <div class="col-md-1">
                        <span class="btn btn-default list" id="grid-button"><span
                                    class="glyphicon glyphicon-th-large"></span></span>
                            <span class="btn btn-default grid" id="list-button"><span
                                        class="glyphicon glyphicon-th-list"></span></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="next">
                            <div class="col-md-12 col-md-offset-0">
                                <div class="alert alert-success col-md-11">
                                    <div class="col-md-12">
                                        <h4>{{session('upload')}}</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <span class="btn btn-primary"
                                              id="other-upload-button"> Upload another file </span>
                                        or <span class="btn btn-success"
                                                 id="task_completed">
                                                I've finished: mark the task as completed</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br>

                @if(session('error'))
                    <div class="col-md-12 col-md-offset-0">
                        <div class="alert alert-danger">
                            {{session('error')}}
                        </div>
                    </div>
                @endif

                @if(isset($results))

                    <div class="col-md-12 grid">

                        @foreach($results->chunk(4) as $chunk)
                            <div class="row" style="border-top:1px solid #dedede;">
                                <br>
                                @foreach($chunk as $result)
                                    <div class="col-md-3" style="border:1px solid #fff;height:170px;">
                                        <div class="col-md-12" style="text-align: center;height:140px">
                                            <img src="{{$result['src']}}" alt=""
                                                 style="max-width:150px;max-height: 150px">
                                        </div>
                                        <div class="clearfix"></div>
                                        <br>
                                        <div class="col-md-12" style="text-align: center;height:30px;">
                                            {{$result['name']}}.{{$result['mime']}}
                                            {{--<img src="{{$result['src']}}" alt="" style="max-width:auto"/>--}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="clearfix"></div><br><br>

                        @endforeach
                    </div>
                @endif
                @if(isset($results))
                    <div class="list">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <th>Image</th>
                            </tr>
                            @foreach($results as $result)
                                <tr >
                                    <th style="height:10px"><img src="{{$result['icon']}}" alt="">
                                        {{$result['name']}}.{{$result['mime']}} </th>
                                    <th style="height:10px"><img src="{{$result['src']}}" alt="" style="height:80px;"/></th>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                @endif
                <div class="clearfix"></div>
                <br>
            </div>
        </div>
    </div>

    @if(isset($client_tasks))
        <div id="task-select" class="hidden">
            @foreach($client_tasks as $task)
                <div class="radio">
                    <label>
                        <input type="radio" value="{{$task->id}}" name="tag" @if($loop->first) checked="checked" @endif>
                        {{$task->content}}</label>
                </div>
            @endforeach
        </div>
    @endif

@endsection
@section('jquery')
    <script>
        jQuery(document).ready(function () {

            $('#next').hide();
            $('#other-upload-button').on('click', function () {
                $('#next').hide();
                $('#upload-group').show();
            });

//            confirm task completed
            $('#task_completed').on('click', function () {
                var content = $('#task-select').html();
                var $modal_content = $('.modal-content');
                $modal_content.html('');
                $modal_content.load('/modals/driveTaskConfirm.php', function () {
                    console.log($('#radio'));
                    $('#ot_description').html(content);

                    $('#ot_confirm').on('click', function () {
                        completeUploadTask();
                    });
                });
                $('#tag-modal').modal('show');

            });

            function completeUploadTask() {
                var task_id = $('input[name=tag]:checked').val(),
                        project_id = $('#project-data').attr('project-id');
                $.ajax(
                        {
                            method: "get",
                            url: '/completetask',
                            dataType: "json",
                            data: {
                                "task_id": task_id,
                            }
                        }).done(
                        function (data) {
                            console.log(data);
                            if(data.msg === 'ok'){
                                window.location.replace("/project/"+project_id);
                            }
                        }
                );
//                $('#tag-modal').modal('hide');
            }

            $('.list').hide();
            $('#grid-button').on('click', function () {
                $('.list').hide();
                $('.grid').show();
            });
            $('#list-button').on('click', function () {
                $('.grid').hide();
                $('.list').show();
            });

            @if(session('upload'))
                $('#upload-group').hide();
                $('#next').show();
            @endif
        });
    </script>
@endsection
