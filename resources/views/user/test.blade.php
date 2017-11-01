@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="spin" id="loading_spin"><img src="/img/spin.gif" alt=""></div>
        <div id="darkLayer" class="darkClass" style="display:none;"></div>
        <div class="col-md-10 col-lg-10 col-lg-offset-1 col-md-offset-1" style="">
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
                {{--</div>--}}
            </div>
            <div class="hidden" id="data" user-id="{{Auth::user()->id}}" data-project="{{$project['id']}}"
                 data-folder="{{$folder}}">
            </div>
            @if(session('gocardless'))
                <div class="alert alert-success">
                    {{session('gocardless')}}
                </div>
        @endif
        <!-- Modal -->
            <div class="modal fade" id="tag-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <br>
            <div class="col-md-12 col-xs-12 navigation-group">
                <div class="col-md-6 navigation-tab navigation-tab-active">
                    <span>TASKS</span>
                </div>
                <a href="/project/{{$project['id']}}/user/{{Auth::user()->id}}/folder/{{$folder}}">
                    <div class="col-md-6 navigation-tab navigation-tab-inactive">
                        <span>UPLOAD FOLDER</span>
                    </div>
                </a>
                {{--<ul class="nav nav-tabs">--}}
                {{--<li class="active"><a href="#"><span class="task">TASKS</span></a></li>--}}
                {{--<li><a href="/project/{{$project['id']}}/user/{{Auth::user()->id}}/folder/{{$folder}}">UPLOAD FOLDER</a></li>--}}
                {{--</ul>--}}
            </div>

            <div class="col-md-12 col-xs-12" id="content">
                <div class="col-md-12 col-xs-12">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                           href="#otherTasks" aria-expanded="true"
                           aria-controls="collapseOne">
                            <div class="col-md-6" style="">
                                {{--Tasklist Name--}}
                                <span class="title">Other Client Tasks</span>
                            </div>
                            {{--Progress Bar--}}
                            <div class="col-md-6" style="">
                                <div class="col-md-1">
                                    @if($otherList['uncompletedClientTasks'])
                                        <span
                                                class="glyphicon glyphicon-warning-sign big-sign-danger"> </span>
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <div class="progress">
                                        @if($otherList['uncompletedClientTasks'])
                                            <div class="progress-bar progress-bar-danger"
                                                 style="width:{{$otherList['percent']}}%;min-width:40px;">
                                                @else
                                                    <div class="progress-bar progress-bar-success"
                                                         style="width:{{$otherList['percent']}}%;min-width:40px;">
                                                        @endif
                                                        {{$otherList['percent']}}%
                                                    </div>
                                            </div>
                                    </div>
                                    <div class="col-md-1">
                                        <span class="glyphicon glyphicon-menu-down"> </span>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                        </a>
                    </div>
                    <div id="otherTasks"
                         @if($otherList['uncompletedClientTasks'])
                         class="panel-collapse collapse in"
                         @else
                         class="panel-collapse collapse"
                         @endif
                         role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <div class="col-md-12" style="">
                                <br>
                                <table class="table">

                                    @foreach($otherTasks as $otherTask)
                                        @if(isset($otherTask['content']))
                                            <tr>
                                                <input type="hidden" id="ot_id" name="id" value="{{$otherTask['ot_id']}}">
                                                <th class="ot_th">
                                                    @if(!$otherTask['completed'])
                                                        @if($otherTask['active'])
                                                            <span style="font-size:17px;color:#bf5329;" class="glyphicon glyphicon-alert"></span>
                                                            <span class="task client-uncompleted">{{$otherTask['content']}}</span>
                                                        @else
                                                            <span style="font-size:17px;color:darkgray;"
                                                                  class="glyphicon glyphicon-remove-sign"> </span>
                                                            <span class="task studio-uncompleted">
                                                        {{$otherTask['content']}}</span>
                                                        @endif
                                                </th>
                                                @else
                                                    <span style="font-size:17px;color:#2cb27b;" class="glyphicon glyphicon-ok-sign"> </span>
                                                    <span class="task client-completed"> {{$otherTask['content']}} </span>
                                                    </th>
                                                @endif
                                                @if(!$otherTask['completed'] && $otherTask['active'])
                                                    <th>
                                                        <input type="submit"
                                                               class="btn btn-xs btn-danger taskDetails"
                                                               data-task-description="{{$otherTask['description']}}"
                                                               data-task-content="{{$otherTask['content']}}"
                                                               data-task-amount="{{$otherTask['amount']}}"
                                                               data-task-tag="{{$otherTask['fk_tag']}}"
                                                               data-task-id="{{$otherTask['ot_id']}}"
                                                               data-task-url="{{$otherTask['url']}}"
                                                               value="Details"/>
                                                    </th>

                                                @else
                                                    <th></th>
                                                @endif
                                                @endif
                                                {{--</div>--}}
                                            </tr>
                                            {{--</div>--}}

                                            @endforeach
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>

                @foreach($lists as $list)
                    <div class="col-md-12 col-xs-12" style="border-top:1px solid #ccc;">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <a role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#{{$list['tasklist_id']}}" aria-expanded="true"
                               aria-controls="collapseOne">
                                <div class="col-md-6">
                                    {{--Tasklist Name--}}
                                    <span class="title"> {{$list['name']}} </span>
                                </div>
                                {{--Progress Bar--}}
                                <div class="col-md-6">
                                    @if($list['uncompletedClientTasks'])
                                        <div class="col-md-1">
                                                <span id="pb_icon_{{$list['tasklist_id']}}"
                                                      class="glyphicon glyphicon-warning-sign big-sign-danger"> </span>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-danger "
                                                     id="pb_{{$list['tasklist_id']}}"
                                                     style="width:{{ $list['percent'] }}%;min-width:40px;">
                                                    {{ $list['percent'] }}%
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success "
                                                     style="width:{{ $list['percent'] }}%;min-width:40px;">
                                                    {{ $list['percent'] }}%
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-1">
                                        <span class="glyphicon glyphicon-menu-down"> </span>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                            </a>
                        </div>
                        <div id="{{$list['tasklist_id']}}"

                             @if($list['uncompletedClientTasks'])
                             class="panel-collapse collapse in"
                             @else
                             class="panel-collapse collapse"
                             @endif

                             role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                {{--Tasklist description--}}
                                {{--@foreach($studioTasks as $tasklist)--}}
                                {{--@foreach($tasklist as $task)--}er--}}
                                {{--@if($task['content'] === 'description' && $task['fk_tasklist'] === $list['tasklist_id'])--}}
                                {{--<div class="col-md-12 description">--}}
                                {{--<h5>Description: {{$task['description']}} </h5>--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--@endforeach--}}
                                {{--@endforeach--}}
                                <div class="col-md-12 col-xs-12" style="">

                                    <div class="col-md-6 col-xs-6"><span class="subtitle">CLIENT TASKS:</span></div>
                                    <div class="col-md-6 col-xs-6"><span class="subtitle">STUDIO TASKS:</span></div>
                                    <table class="table ">
                                        <tbody>

                                        @foreach($studioTasks as $tasklist)
                                            @foreach($tasklist as $task)
                                                @if( $task['fk_tasklist'] === $list['tasklist_id'])
                                                    <tr>
                                                        <th class="task_th">
                                                            @if(!$task['completed'])
                                                                <span style="font-size:17px;color:darkgray;"
                                                                      class="glyphicon glyphicon-remove-sign"> </span>
                                                                <span class="task studio-uncompleted"> {{$task['content']}} </span>
                                                            @else
                                                                <span style="font-size:17px;color:#2cb27b;"
                                                                      class="glyphicon glyphicon-ok-sign"> </span>
                                                                <span class="task client-completed">{{$task['content']}} </span>
                                                            @endif
                                                        </th>
                                                        <th>

                                                        @if(isset($clientTasks[$task['fk_tasklist']][$task['task_id']]['content']))
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                        </th>
                                                        <th>
                                                            @if($clientTasks[$task['fk_tasklist']][$task['task_id']]['completed'] === 1)

                                                                <div class="col-md-12">
                                                                         <span style=""
                                                                               class="client-completed glyphicon glyphicon-ok-sign"> </span>
                                                                    <span class="task client-completed">{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['content']}} </span>
                                                                </div>
                                                            @elseif($clientTasks[$task['fk_tasklist']][$task['task_id']]['completed'] === 0  )
                                                                {{--TASK NOT COMPLETED--}}
                                                                @if(' '!==$clientTasks[$task['fk_tasklist']][$task['task_id']]['tags'])
                                                                    ({{$clientTasks[$task['fk_tasklist']][$task['task_id']]['tags']}}
                                                                    )
                                                                    @if('Upload'===$clientTasks[$task['fk_tasklist']][$task['task_id']]['tags'])
                                                                        <span class="btn btn-xxs btn-default">Upload</span>
                                                                    @endif
                                                                    @if('Payment'===$clientTasks[$task['fk_tasklist']][$task['task_id']]['tags'])
                                                                        <span class="btn btn-xxs btn-default">Payment</span>
                                                                    @endif
                                                                @endif
                                                                @if($clientTasks[$task['fk_tasklist']][$task['task_id']]['active'] === 1  )
                                                                    <div class="col-md-1"
                                                                         id="form_{{$task['task_id']}}">
                                                                        <form action="/project/{{$project['id']}}/task/{{$task['task_id']}}"
                                                                              method="post" id="checkTaskForm"
                                                                              name="{{$task['task_id']}}">
                                                                            {{csrf_field()}}
                                                                            <input type="submit" value="✖"
                                                                                   id="{{$task['task_id']}}"
                                                                                   name="✖"
                                                                                   onmouseout="untoggle(this.id)"
                                                                                   onmouseover="toggle(this.id)"
                                                                                   class="btn btn-xxs btn-uncompleted btn-confirm">
                                                                        </form>
                                                                    </div>
                                                                    <div class="col-md-11">
                                                                            <span id="span_{{$task['task_id']}}"
                                                                                  class="hidden"></span>
                                                                        <span id="content_{{$task['task_id']}}"
                                                                              class="task client-uncompleted sign-danger"> {{$clientTasks[$task['fk_tasklist']][$task['task_id']]['content']}} </span>
                                                                    </div>

                                                                @else
                                                                    <div class="col-md-12" style="">
                                                                        <span style="font-size:17px;color:darkgray;"
                                                                              class="glyphicon glyphicon-remove-sign"> </span>
                                                                        <span class="task studio-uncompleted "> {{$clientTasks[$task['fk_tasklist']][$task['task_id']]['content']}} </span>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </th>

                                                    </tr>

                                                    @endif
                                                    @endif
                                                    </th>

                                                    </tr>

                                                    @endforeach
                                                    @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
    <div class="clearfix"></div>

    <br><br>
@endsection

@section('jquery')
    <script>
        jQuery(document).ready(function () {
//    change "completed" "uncompleted" button on mouse over
//        if ($(".btn-confirm").val() == "Completed") {
//            $(".btn-confirm").mouseover(function () {
//                $(".btn-confirm").val("Uncompleted");
//            });
//        }

            $('.spin').hide();
            $(document)
                    .ajaxStart(function () {
                        $('#darkLayer').show();
                        $("#loading_spin").show();
                    })
                    .ajaxStop(function () {
                        $("#loading_spin").hide();
                        $("#darkLayer").hide();
                    });

            $('.btn-confirm').on('click', function (event) {
//            var newName = $("#update-settings").find('input[id=input_' + this.name + ']').val();
                var value = this.name;
                var option;
                if (this.name == '✓') {
                    option = 'uncheck';
                } else if (this.name == '✖') {
                    option = 'check';
                }
                var id = this.id;
                $.ajax(
                        {
                            method: "put",
                            url: $("#checkTaskForm").prop('action'),
                            dataType: "json",
                            data: {
                                "_token": $("#checkTaskForm").find('input[name=_token]').val(),
                                "value": value,
                                "id": id,
                                "option": option
                            }
                        }).done(
                        function (data) {
                            console.log(data);
                            var tasklist_id = (data.tasklist_id);

                            if (data.uncompleted === false) {
                                $('#pb_icon_' + tasklist_id).attr('class', 'hidden');
                                $('#pb_' + tasklist_id).attr('class', 'progress-bar progress-bar-success');
                                setTimeout(
                                        function () {
                                            $('#' + tasklist_id).attr('class', 'panel-collapse collapse');
                                        }, 1000);

                            }
//                            console.log('style', 'width:' + data.percent + '%;min-width:40px;');
//                            console.log($('#pb_' + tasklist_id).text());
                            $('#pb_' + tasklist_id).text(data.percent + '%');
                            $('#pb_' + tasklist_id).attr('style', 'width:' + data.percent + '%;min-width:40px;');
                            $('#content_' + id).attr('class', 'task client-completed');
                            $('#span_' + id).attr('class', 'client-completed glyphicon glyphicon-ok-sign');
                            $('#form_' + id).attr('class', 'hidden');
                        }
                );
                event.preventDefault();

            });
        });

        $('.taskDetails').on('click', function (event) {

            console.log('asda');
            var $this = $(this),
                    ot_url, ot_confirm, amount,
                    ot_description = $this.attr('data-task-description'),
                    ot_id = $this.attr('data-task-id'),
                    ot_tag = $this.attr('data-task-tag'),
                    ot_amount = $this.attr('data-task-amount'),
                    ot_content = $this.attr('data-task-content'),
                    user_id = $('#data').attr('user-id'),
                    project_id = $('#data').attr('data-project'),
                    folder = $('#data').attr('data-folder');
            ot_url = $this.attr('data-task-url');
            switch (ot_tag) {
                case '1':
//                upload a file
                    ot_confirm = 'Go to the upload page';
                    ot_url = '/project/' + project_id + '/user/' + user_id + '/folder/' + folder;
                    break;
                case '2':
//                    setup gocard less
                    ot_url = '/gocardless/new-customer?ot_id=' + ot_id;
                    ot_description += ' 44779911<br>200000';
                    ot_confirm = 'Setup GoCardless account';
                    break;
                case '3':
                    amount = '<h4>Amount:</h4> <div class="panel panel-default"> <div class="panel-body">' +
                            '<div class="col-md-12">' + ot_amount + '£</div> </div> </div>';
                    ot_url = '/gocardless/make-payment?ot_id=' + ot_id;

                    ot_confirm = 'Go to the payment page';
                    break;
                default:
                    ot_url = $this.attr('data-tasklist-url');
                    ot_confirm = 'Confirm';

                    break;
            }
            var $modal_content = $('.modal-content');
            $modal_content.html('');
            $modal_content.load('/modals/OtherTaskDetails.php', function () {
                $('#ot_content').html(ot_content);
                $('#ot_description').html(ot_description);
                $(amount).insertBefore('#ot_url');
                $('#ot_url').attr('href', ot_url);
                $('#ot_confirm').html(ot_confirm);
//                $('#ot_confirm').html('');
            });
            $('#tag-modal').modal('show');
            event.preventDefault();
        });

        function toggle(e) {
            if (document.getElementById(e).name == '✓') {
                document.getElementById(e).value = '✖';
                document.getElementById(e).className = "btn btn-xxs btn-uncompleted btn-confirm";

            }
            if (document.getElementById(e).name == '✖') {
                document.getElementById(e).value = '✓';
                document.getElementById(e).className = "btn btn-xxs btn-completed btn-confirm";
            }
        }
        function untoggle(e) {
            document.getElementById(e).value = document.getElementById(e).name;
            if (document.getElementById(e).name == '✖') {
                document.getElementById(e).className = "btn btn-xxs btn-uncompleted btn-confirm";
            }
            if (document.getElementById(e).name == '✓') {
                document.getElementById(e).className = "btn btn-xxs btn-completed btn-confirm";
            }
        }
    </script>
@endsection
