@extends('layouts.app')
@section('style')
    <style>
        html, body {
            background: #f5f5f5;
        }
    </style>
@endsection
@section('content')

    <div class="container">
        <div class="spin" id="loading_spin"><img src="/img/spin.gif" alt=""></div>
        <div id="darkLayer" class="darkClass" style="display:;"></div>
        <div class="col-md-10 col-lg-10 col-lg-offset-1 col-md-offset-1" style="">
            <div class="col-md-12">
                {{--<div class="col-md-2">--}}
                <form action="/home" id="token" method="post">
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
                @if(isset($folder))
                    <a href="/project/{{$project['id']}}/user/{{Auth::user()->id}}/folder/{{$folder}}">
                        <div class="col-md-6 navigation-tab navigation-tab-inactive">
                            <span>UPLOAD FOLDER</span>
                        </div>
                    </a>
                @endif
                {{--<ul class="nav nav-tabs">--}}
                {{--<li class="active"><a href="#"><span class="task">TASKS</span></a></li>--}}
                {{--<li><a href="/project/{{$project['id']}}/user/{{Auth::user()->id}}/folder/{{$folder}}">UPLOAD FOLDER</a></li>--}}
                {{--</ul>--}}
            </div>
            <div class="col-md-12 col-xs-12" id="content">
                <div class="col-md-12 col-xs-12">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <a role="button" class="arrow" data-toggle="collapse" data-tasklist="otherTasks"
                           data-parent="#accordion"
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
                                        <span id="pb_icon_other_tasks"
                                              class="glyphicon glyphicon-warning-sign big-sign-danger"> </span>
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <div class="progress">
                                        @if($otherList['uncompletedClientTasks'])
                                            <div class="progress-bar progress-bar-danger"
                                                 id="pb_other_tasks"
                                                 style="width:{{$otherList['percent']}}%;min-width:40px;">
                                                @else
                                                    <div class="progress-bar progress-bar-success"
                                                         id="pb_other_tasks"
                                                         style="width:{{$otherList['percent']}}%;min-width:40px;">
                                                        @endif
                                                        {{$otherList['percent']}}%
                                                    </div>
                                            </div>
                                    </div>
                                    <div class="col-md-1">
                                        <span id="pb_other_tasks_menu_icon"
                                              class="glyphicon glyphicon-menu-down"> </span>
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
                                                <input type="hidden" id="ot_id" name="id"
                                                       value="{{$otherTask['ot_id']}}">
                                                <th class="ot_th">
                                                    @if(!$otherTask['completed'])
                                                        @if($otherTask['active'])
                                                            <span style="font-size:17px;"
                                                                  id="glyphicon_{{$otherTask['ot_id']}}"
                                                                  class="glyphicon glyphicon-alert sign-danger"></span>
                                                            <span id="content_{{$otherTask['ot_id']}}"
                                                                  class="task client-uncompleted">{{$otherTask['content']}}</span>
                                                        @else
                                                            <span style="font-size:17px;color:darkgray;"
                                                                  class="glyphicon glyphicon-remove-sign"> </span>
                                                            <span class="task studio-uncompleted">
                                                        {{$otherTask['content']}}</span>
                                                        @endif
                                                </th>
                                                @else
                                                    <span style="font-size:17px;color:#2cb27b;"
                                                          class="glyphicon glyphicon-ok-sign"> </span>
                                                    <span class="task client-completed"> {{$otherTask['content']}} </span>
                                                    </th>
                                                @endif
                                                @if(!$otherTask['completed'] && $otherTask['active'])
                                                    <th>
                                                        <input type="submit"
                                                               class="btn btn-xs btn-danger taskDetails"
                                                               id="details_button_{{$otherTask['ot_id']}}"
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
                            <a role="button" class="arrow" data-toggle="collapse"
                               data-tasklist="{{$list['tasklist_id']}}" data-parent="#accordion"
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
                                                <span id="pb_danger_icon_{{$list['tasklist_id']}}"
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
                                        <span id="pb_icon_{{$list['tasklist_id']}}"
                                              class="glyphicon glyphicon-menu-down"> </span>
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

                                <div class="col-md-12 col-xs-12" style="">

                                    <div class="col-md-6 col-xs-6"><span class="subtitle">CLIENT TASKS:</span></div>
                                    <div class="col-md-6 col-xs-6"><span class="subtitle">STUDIO TASKS:</span></div>
                                    <table class="table ">
                                        <tbody>

                                        @foreach($studioTasks as $tasklist)
                                            @foreach($tasklist as $task)
                                                @if( $task['fk_tasklist'] === $list['tasklist_id'])

                                                    @if(isset($clientTasks[$task['fk_tasklist']][$task['task_id']]['content']))
                                                        <tr>
                                                            <th>
                                                                @if($clientTasks[$task['fk_tasklist']][$task['task_id']]['completed'] === 1)

                                                                    <div class="col-md-12">
                                                                         <span style=""
                                                                               class="client-completed glyphicon glyphicon-ok-sign"> </span>
                                                                        <span class="task client-completed">{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['content']}} </span>
                                                                    </div>
                                                                @elseif($clientTasks[$task['fk_tasklist']][$task['task_id']]['completed'] === 0  )
                                                                    {{--TASK NOT COMPLETED--}}
                                                                    @if($clientTasks[$task['fk_tasklist']][$task['task_id']]['active'] === 1  )
                                                                        {{--TASK ACTIVE--}}
                                                                        {{--<div class="col-md-1"--}}
                                                                        {{--id="form_{{$task['task_id']}}">--}}
                                                                        <form action="/project/{{$project['id']}}/task/{{$task['task_id']}}"
                                                                              method="post" id="checkTaskForm"
                                                                              name="{{$task['task_id']}}">
                                                                            {{csrf_field()}}
                                                                            {{--<input type="submit" value="✖"--}}
                                                                            {{--id="{{$task['task_id']}}"--}}
                                                                            {{--name="✖"--}}
                                                                            {{--onmouseout="untoggle(this.id)"--}}
                                                                            {{--onmouseover="toggle(this.id)"--}}
                                                                            {{--class="btn btn-xxs btn-uncompleted btn-confirm">--}}
                                                                        </form>
                                                                        {{--</div>--}}
                                                                        <div class="col-md-8">
                                                                            <span id="span_{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['client_task_id']}}"
                                                                                  class="hidden"></span>
                                                                            <span id="glyphicon_{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['client_task_id']}}"
                                                                                  style="font-size:17px;"
                                                                                  class="glyphicon glyphicon-alert sign-danger"> </span>
                                                                            <span id="content_{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['client_task_id']}}"
                                                                                  class="task client-uncompleted"> {{$clientTasks[$task['fk_tasklist']][$task['task_id']]['content']}}</span>
                                                                        </div>
                                                                        <div class="col-md-4">

                                                                            <input type="submit"
                                                                                   id="details_button_{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['client_task_id']}}"
                                                                                   class="btn btn-xs btn-danger taskDetails"
                                                                                   data-task-description="{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['description']}}"
                                                                                   data-task-content="{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['content']}}"
                                                                                   data-task-amount="{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['amount']}}"
                                                                                   data-task-tag="{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['fk_tag']}}"
                                                                                   data-task-id="{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['client_task_id']}}"
                                                                                   data-task-fk="{{$task['task_id']}}"
                                                                                   data-task-url="{{$clientTasks[$task['fk_tasklist']][$task['task_id']]['url']}}"
                                                                                   value="Details"/>
                                                                        </div>
                                                                    @else
                                                                        {{--TASK NOT ACTIVE--}}
                                                                        <div class="col-md-12" style="">
                                                                        <span style="font-size:17px;color:darkgray;"
                                                                              class="glyphicon glyphicon-remove-sign"> </span>
                                                                            <span class="task studio-uncompleted "> {{$clientTasks[$task['fk_tasklist']][$task['task_id']]['content']}} </span>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </th>
                                                            <th>
                                                            </th>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <th>
                                                        </th>
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

                                                    </tr>
                                                @endif
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
            var token = $("#checkTaskForm").find('input[name=_token]').val();
            $("#darkLayer").hide();
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

//            $('.btn-confirm').on('click', function (event) {
////            var newName = $("#update-settings").find('input[id=input_' + this.name + ']').val();
//                var value = this.name,
//                        option,
//                        id = this.id;
////                       var task_id = ;
//                $.ajax(
//                        {
//                            method: "put",
//                            url: $("#checkTaskForm").prop('action'),
//                            dataType: "json",
//                            data: {
//                                "_token": $("#checkTaskForm").find('input[name=_token]').val(),
//                                "value": value,
//                                "id": id,
//                                "option": option,
//                            }
//                        }).done(
//                        function (data) {
//                            console.log(data);
//                            var tasklist_id = (data.tasklist_id);
//
//                            if (data.uncompleted === false) {
//                                $('#pb_icon_' + tasklist_id).attr('class', 'hidden');
//                                $('#pb_' + tasklist_id).attr('class', 'progress-bar progress-bar-success');
//                                setTimeout(
//                                        function () {
//                                            $('#' + tasklist_id).attr('class', 'panel-collapse collapse');
//                                        }, 1000);
//
//                            }
////                            console.log('style', 'width:' + data.percent + '%;min-width:40px;');
////                            console.log($('#pb_' + tasklist_id).text());
//                            $('#pb_' + tasklist_id).text(data.percent + '%');
//                            $('#pb_' + tasklist_id).attr('style', 'width:' + data.percent + '%;min-width:40px;');
//                            $('#content_' + id).attr('class', 'task client-completed');
//                            $('#span_' + id).attr('class', 'client-completed glyphicon glyphicon-ok-sign');
//                            $('#form_' + id).attr('class', 'hidden');
//                        }
//                );
//                event.preventDefault();
//            });

            function confirmPayment(id, amount) {
                console.log(id);
                console.log(amount);
                $.ajax(
                        {
                            method: "get",
                            url: '/gocardless/make-payment',
                            dataType: "json",
                            data: {
                                "id": id,
                                "amount": amount,
                            }
                        }).done(
                        function (data) {
                            console.log(data);
                            if (data.msg === 'payment completed') {
                                console.log('ok');
                                confirmTask(id);
                            }
                        }
                );

            }

            $('.arrow').on('click', function () {
                var tasklist_id = $(this).attr('data-tasklist');
                if (tasklist_id === 'otherTasks') {
                    if ($('#otherTasks').hasClass('in')) {
                        $('#pb_other_tasks_menu_icon').attr('class', 'glyphicon glyphicon-menu-down');
                    } else {
                        $('#pb_other_tasks_menu_icon').attr('class', 'glyphicon glyphicon-menu-up');
                    }
                } else {
                    if ($('#' + tasklist_id).hasClass('in')) {
                        $('#pb_icon_' + tasklist_id).attr('class', 'glyphicon glyphicon-menu-down');
                    } else {
                        $('#pb_icon_' + tasklist_id).attr('class', 'glyphicon glyphicon-menu-up');
                    }
                }
            });

            function confirmTask(id) {
                console.log('confirm id: ' + id);
                var token = $("#token").find('input[name=_token]').val();
                $.ajax(
                        {
                            method: "put",
                            url: '/completeTask',
                            dataType: "json",
                            data: {
                                "_token": token,
                                "id": id,
                                "option": 'check'
                            }
                        }).done(
                        function (data) {
                            console.log(data);
                            var tasklist_id = (data.tasklist_id);
                            if (data.uncompleted === false) {
//                                if there are no more uncompleted task in the tasklist
                                if (tasklist_id !== "0") {
                                    $('#pb_danger_icon_' + tasklist_id).attr('class', 'hidden');
                                    $('#pb_' + tasklist_id).attr('class', 'progress-bar progress-bar-success');
                                    setTimeout(
                                            function () {
                                                $('#pb_icon_' + tasklist_id).attr('class', 'glyphicon glyphicon-menu-down');
                                                $('#' + tasklist_id).attr('class', 'panel-collapse collapse');
                                            }, 1000);
                                } else {
                                    $('#pb_icon_other_tasks').attr('class', 'hidden');
                                    $('#pb_other_tasks').attr('class', 'progress-bar progress-bar-success');
                                    setTimeout(
                                            function () {
                                                $('#pb_other_tasks_menu_icon').attr('class', 'glyphicon glyphicon-menu-down');
                                                $('#otherTasks').attr('class', 'panel-collapse collapse');
                                            }, 1000);
                                }

                            }

                            if (tasklist_id !== "0") {
                                $('#pb_' + tasklist_id).text(data.percent + '%');
                                $('#pb_' + tasklist_id).attr('style', 'width:' + data.percent + '%;min-width:40px;');
                            } else {
                                $('#pb_other_tasks').text(data.percent + '%');
                                $('#pb_other_tasks').attr('style', 'width:' + data.percent + '%;min-width:40px;');
                            }
                            $('#content_' + id).attr('class', 'task client-completed');
                            $('#glyphicon_' + id).attr('class', 'glyphicon glyphicon-ok-sign client-completed');
                            $('#details_button_' + id).attr('class', 'hidden');
                        }
                );
                $('#tag-modal').modal('hide');

            }


            $('.taskDetails').on('click', function (event) {
                var $this = $(this),
                        ot_confirm, confirm_url, link, confirm, amount_div, ot_confirm_class,
                        ot_description = $this.attr('data-task-description'),
                        ot_id = $this.attr('data-task-id'),
                        fk_task = $this.attr('data-task-fk'),
                        ot_tag = $this.attr('data-task-tag'),
                        ot_amount = $this.attr('data-task-amount'),
                        ot_content = $this.attr('data-task-content'),
                        ot_url = $this.attr('data-task-url'),
                        user_id = $('#data').attr('user-id'),
                        project_id = $('#data').attr('data-project'),
                        folder = $('#data').attr('data-folder');
                if (ot_url !== '') {
                    link = '<h4>URL:</h4><div class="panel panel-default"><div class="panel-body"> <div id="" class="col-md-12"><a href="' + ot_url + '">' + ot_url + '</a></div></div> </div>';
//                    ot_url = '<span class="btn btn-success" id="ot_url"></span><br>';
                }
                ot_confirm_class = 'btn btn-success';
                switch (ot_tag) {
                    case '0':
//                    general task
//                        confirm_url = '/completetask?task_id=' + ot_id;
                        ot_confirm = 'Mark Task Completed';
                        ot_confirm_class = 'btn btn-success completeTaskConfirm';

                        break;
                    case '1':
//                upload a file
                        confirm_url = '/project/' + project_id + '/user/' + user_id + '/folder/' + folder;
                        ot_confirm = 'Go to the upload page';
                        break;
                    case '2':
//                    setup gocard less
                        confirm_url = '/gocardless/new-customer?ot_id=' + ot_id;
//                        ot_description += ' <br>44779911<br>200000';
                        ot_confirm = 'Setup GoCardless account';
                        break;
                    case '3':
//                    payment
                        amount_div = '<h4>Amount:</h4> <div class="panel panel-default"> <div class="panel-body">' +
                                '<div class="col-md-12">' + ot_amount + '£</div> </div> </div>';
//                    confirm_url = '/gocardless/make-payment?ot_id=' + ot_id;
                        ot_confirm_class = 'btn btn-success confirmPayment';
                        ot_confirm = 'Pay and confirm';
                        break;
                    case '4':
//                    development sign-off
//                        confirm_url = '/completetask?task_id=' + ot_id;
                        ot_confirm_class = 'btn btn-success completeTaskConfirm';

                        ot_confirm = 'Mark Task Completed';
                        break;
                    case '5':
//                    design sign-off
//                        confirm_url = '/completetask?task_id=' + ot_id;
                        ot_confirm_class = 'btn btn-success completeTaskConfirm';
                        ot_confirm = 'Mark Task Completed';
                        break;
                    default:
                        ot_url = $this.attr('data-tasklist-url');
                        ot_confirm = 'Confirm Default';
                        break;
                }
                var $modal_content = $('.modal-content');
                $modal_content.html('');
                $modal_content.load('/modals/OtherTaskDetails.php', function () {
                    $('#ot_content').html(ot_content);
                    $('#ot_description').html(ot_description);
                    $(amount_div).insertBefore('#content');
                    $(link).insertBefore('#content');
                    $(confirm).insertBefore('#content');
                    $('#ot_url').attr('href', ot_url);
                    $('#confirm_url').attr('href', confirm_url);
                    $('#ot_confirm').html(ot_confirm);
                    $('#ot_confirm').attr('class', ot_confirm_class);
                    $('.confirmPayment').on('click', function () {
                        confirmPayment(ot_id, ot_amount);
                    });
                    $('.completeTaskConfirm').on('click', function () {
                        confirmTask(ot_id);
                    });
                });
                $('#tag-modal').modal('show');
                event.preventDefault();
            });

        });

    </script>
@endsection
