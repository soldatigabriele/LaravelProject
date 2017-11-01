@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="col-md-12">
            @if(session('success'))
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-success">
                        {{session('success')}}
                    </div>
                </div>
            @endif
            <div class="spin" id="loading_spin"><img src="/img/spin.gif" alt=""></div>
            <div id="darkLayer" class="darkClass" style="display:;"></div>
            <div class="col-md-12">
                <div class="col-md-2">
                    <form action="/admin/user/{{$user_id}}" method="get">
                        <input type="submit" class="btn btn-default" value="Go Back">
                        {{csrf_field()}}
                    </form>
                </div>
                <div class="col-md-10 title">
                    <strong> PROJECT NAME: {{  $project['name'] }} - ID: {{  $project['id'] }} </strong>
                </div>
                <div class="content"></div>


                <!-- Modal -->
                <div class="modal fade" id="tag-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>


                <div class="clearfix"></div>
                <hr>

                <form action="/abc" id="update-settings" method="post">
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    <input type="hidden" name="project_id" value="{{$project_id}}">
                    {{csrf_field()}}
                    @foreach($lists as $list)
                        <div class="col-md-12 col-xs-12" style="border-top:1px solid #ccc;">
                            <div class="col-md-2 col-xs-3" style="padding-top: 12px">

                                Visible: <input type="checkbox" class="visible" name="{{$list['tasklist_id']}}"
                                                value="tasklist" @if($list['visible']) checked @endif>
                            </div>
                            <div class="col-md-10 col-xs-10">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion"
                                       href="#{{$list['tasklist_id']}}" aria-expanded="true"
                                       aria-controls="collapseOne">
                                        <div class="col-md-10 col-md-offset-1">
                                            <span class="tasklist-title">{{$list['name']}} </span>
                                            <span class="tasklist-details">Tasklist ID: {{$list['tasklist_id']}}</span>
                                        </div>
                                        <div class="col-md-1">
                                            <span class="glyphicon glyphicon-menu-down"> </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div id="{{$list['tasklist_id']}}"
                                 @if($list['visible'])
                                 class="panel-collapse collapse in"
                                 @else
                                 class="panel-collapse collapse"
                                 @endif
                                 role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div class="col-md-12" style="background:">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Show</th>
                                                <th>Comp</th>
                                                <th>Task Name</th>
                                                <th>Client Task</th>
                                                <th>Description</th>
                                                <th>Details</th>
                                                <th>Active</th>
                                                <th>Comp</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($studioTasks as $tasklist)
                                                @foreach($tasklist as $task)
                                                    @if($task['content'] !== 'description' && $task['fk_tasklist'] === $list['tasklist_id'])
                                                        <tr>
                                                            <th>
                                                                <input type="checkbox" class="visible"
                                                                       name="{{$task['task_id']}}" value="task"
                                                                       @if($task['visible']) checked @endif>
                                                            </th>
                                                            <th>

                                                                @if ($task['completed'])
                                                                    <span
                                                                            style="font-size:17px;color:#5db85c;"
                                                                            class="glyphicon glyphicon-ok-sign"></span>
                                                                @else
                                                                    <span
                                                                            style="font-size:17px;color:#bf5329;"
                                                                            class="glyphicon glyphicon-remove-sign"></span>
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <span id="task_content">
                                                                    {{ $task['content'] }}
                                                                </span>
                                                            </th>
                                                            <th>


                                                                <span id="taskname_{{$task['task_id']}}">
                                                                @if(isset($clientTasks[$task['task_id']]['content']))
                                                                        {{$clientTasks[$task['task_id']]['content']}}
                                                                    @endif
                                                                </span>

                                                            </th>
                                                            <th>
                                                                <span id="description_{{$task['task_id']}}">
                                                                @if(isset($clientTasks[$task['task_id']]['short_description']))
                                                                        {{$clientTasks[$task['task_id']]['short_description']}}
                                                                    @endif
                                                                </span>
                                                            </th>
                                                            <th>
                                                                <input type="submit"
                                                                       class="btn btn-xxs btn-success newclienttask
                                                                       @if($task['has_client_task']) hidden @endif
                                                                               "
                                                                       id="newtask_{{$task['task_id']}}"
                                                                       name="{{$task['task_id']}}"
                                                                       value="Add Client Task"
                                                                       data-task-content="{{$task['content']}}"
                                                                       data-task-id="{{$task['task_id']}}"
                                                                       data-tasklist-id="{{$task['fk_tasklist']}}">
                                                                <input type="submit"
                                                                       id="details_{{$task['task_id']}}"
                                                                       data-user-id="{{$user_id}}"
                                                                       data-task-id="{{$task['task_id']}}"
                                                                       data-tasklist-id="{{$task['fk_tasklist']}}"
                                                                       @if(isset($clientTasks[$task['task_id']]['fk_tag']))
                                                                       data-task-active="{{$clientTasks[$task['task_id']]['active']}}"
                                                                       data-task-tag="{{$clientTasks[$task['task_id']]['fk_tag']}}"
                                                                       data-amount="{{$clientTasks[$task['task_id']]['amount']}}"
                                                                       data-url="{{$clientTasks[$task['task_id']]['url']}}"
                                                                       data-description="{{$clientTasks[$task['task_id']]['description']}}"
                                                                       data-task-content="{{$clientTasks[$task['task_id']]['content']}}"
                                                                       @endif
                                                                       name="{{$task['task_id']}}"
                                                                       class="btn btn-xs btn-default show_details
                                                                       @if(!$task['has_client_task']) hidden @endif
                                                                               "
                                                                       value="details">
                                                                </input>
                                                            </th>
                                                            <th>
                                                                <input type="checkbox" class="activeTask"
                                                                       id="taskActive_{{$task['task_id']}}"
                                                                       name="{{$task['task_id']}}"
                                                                       value="clientTaskActive"
                                                                       @if(isset($clientTasks[$task['task_id']]['content']))
                                                                       @if($clientTasks[$task['task_id']]['active'])
                                                                       checked
                                                                       @endif
                                                                       @else
                                                                       disabled
                                                                        @endif

                                                                >

                                                            </th>
                                                            <th>
                                                                @if(isset($clientTasks[$task['task_id']]['content']))
                                                                    @if ($clientTasks[$task['task_id']]['completed'])
                                                                        <span
                                                                                style="font-size:17px;color:#5db85c;"
                                                                                class="glyphicon glyphicon-ok-sign"></span>
                                                                    @endif
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
                    <div class="clearfix"></div>
                    <hr>
                </form>

            </div>

            <table class="table table-bordered">
                <tr>
                    <th>#</th>
                    <th>Content</th>
                    <th>Description</th>
                    <th>Url</th>
                    <th>Amount</th>
                    <th>Active</th>
                    <th>Visible</th>
                    <th>Tag</th>
                    <th>Completed</th>
                </tr>
                @foreach($otherTasks as $otherTask)
                    @if(isset($otherTask['content']))
                        <tr>
                            <th><input type="hidden" id="ot_id" name="id" value="{{$otherTask['ot_id']}}"></th>
                            <th>
                                {{$otherTask['content']}}
                            </th>
                            <th>
                                {{$otherTask['description']}}
                            </th>
                            <th>
                                {{$otherTask['url']}}
                            </th>
                            <th>
                                {{$otherTask['amount']}}
                            </th>
                            <th>
                                <input type="checkbox" class="updateOtherTask" data-id="{{$otherTask['ot_id']}}"
                                       id="ot_active_{{$otherTask['ot_id']}}"
                                       @if($otherTask['active'])
                                       checked
                                        @endif
                                >
                            </th>
                            <th>
                                <input type="checkbox" class="updateOtherTask" data-id="{{$otherTask['ot_id']}}"
                                       id="ot_visible_{{$otherTask['ot_id']}}"
                                       @if($otherTask['visible'])
                                       checked
                                        @endif
                                >
                            </th>
                            <th>
                                {{$tags[$otherTask['fk_tag']]['name']}}
                            </th>
                            <th>
                                {{$otherTask['completed']}}
                            </th>
                        </tr>
                    @endif
                @endforeach
                <form action="/addtag" id="ot_new_task" method="post">
                    <tr>
                        <th>#</th>
                        <th><input type="text" id="ot_content" name="content" placeholder="content"></th>
                        <th><input type="text" id="ot_description" name="description" placeholder="description"></th>
                        <th><input type="text" id="ot_url" name="url" placeholder="url"></th>
                        <th><input type="text" id="ot_amount" name="amount" style="width:80px" placeholder="amount"
                                   value="0"></th>
                        <th><input type="checkbox" id="ot_active" name="active" value="1"></th>
                        <th><input type="checkbox" id="ot_visible" name="visible" value="1" checked></th>
                        <th>
                            <select name="tag" id="ot_tag">
                                @foreach($tags as $tag)
                                    <option value="{{$tag['id']}}">
                                        {{$tag['name']}}</option>
                                @endforeach
                            </select>
                        </th>
                        <th><input type="submit" id="ot_confirm" value="add task"></th>
                    </tr>
                </form>
            </table>
            <hr>
            @if(isset($folder))
                <div class="col-md-8 col-md-offset-2">
                    <table class="table table-bordered">
                        <tr>
                            <th>#</th>
                            <th>Google Drive Assets Folder</th>
                            <th>Added Date</th>
                            <th>Remove</th>
                        </tr>
                        <tr>
                            <td>{{ $folder->id }}</td>
                            <td>
                                <a href="/admin/user/{{$user_id}}/folder/{{$folder->folder_id}}"> 
                                    {{ $folder->folder_id }}
                                </a>
                            </td>
                            <td>{{ $folder->created_at }}</td>
                            <td>
                                <form action="{{ url('/admin/folder') }}" method="post"> 
                                    <input type="hidden" name="user_id" value="{{$user_id}}">
                                    <input type="hidden" name="project_id" value="{{ $project_id }}">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                      {{csrf_field()}}  {{ method_field('DELETE') }} 
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            @else

                <div class="col-md-8 col-md-offset-2">
                    <form action="/admin/folder" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{$user_id}}">
                        <input type="hidden" name="project_id" value="{{$project_id}}">
                        <div class="col-md-12">
                            <label for="">Assign the Assets Google Drive folder to this project</label>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="col-md-2">
                            <label for="folder_id">Folder ID: </label>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="folder_id" maxlength="160" class="form-control"
                                   style="width:290px">
                        </div>
                        <div class="col-md-2">
                            <input type="submit" class="btn btn-md btn-success" value="Assign Folder">
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
    <div class="clearfix"></div><br><br>
@endsection

@section('jquery')
    <script>
        jQuery(document).ready(function () {

            $('#loading_spin').hide();
            $("#darkLayer").hide();
            $(document)
                    .ajaxStart(function () {
                        $('#darkLayer').show();
                        $("#loading_spin").show();
                    })
                    .ajaxStop(function () {
                        $("#loading_spin").hide();
                        $("#darkLayer").hide();
                    });

            $('.newclienttask').on('click', function (event) {
                var $this = $(this),
                        task_id = $this.attr('data-task-id'),
                        tasklist_id = $this.attr('data-tasklist-id'),
                        task_content = $this.attr('data-task-content');
                var $modal_content = $('.modal-content');
                $modal_content.html('');
                $modal_content.load('/modals/newClientTask.php', function () {
                    $('#task-content').attr('value', task_content);
                    $('#payment-amount').attr('value', 00);
                    $('#task-confirm').on('click', function () {
                        createClientTask(task_id, tasklist_id);
                    });
                });
                $('#tag-modal').modal('show');
                event.preventDefault();
            });

            function createClientTask(task_id, tasklist_id) {
                var amount = $("#payment-amount").val(),
                        description = $('#task-description').val(),
                        instructions = $('#task-instructions').val(),
                        content = $('#task-content').val(),
                        url = $('#task-url').val(),
                        $url = $("#client-task-form").prop('action'),
                        active = $('#ct-active:checked').val(),
                        visible = $('#ct-visible:checked').val(),
                        other_task = 0,
                        option = $('input[name=tag]:checked').val();
                $.ajax(
                        {
                            method: "post",
                            url: $url,
                            dataType: "json",
                            data: {
                                "_token": $("#update-settings").find('input[name=_token]').val(),
                                "amount": amount,
                                "description": description,
                                "instructions": instructions,
                                "content": content,
                                "active": active,
                                "visible": visible,
                                "other_task": other_task,
                                "url": url,
                                "task_id": task_id,
                                "tasklist_id": tasklist_id,
                                "user_id": $("#update-settings").find('input[name=user_id]').val(),
                                "project_id": $("#update-settings").find('input[name=project_id]').val(),
                                "option": option
                            }
                        }).done(
                        function (data) {
                            console.log(data);
                            $('#newtask_' + task_id).prop('class', 'hidden');
                            $("#taskActive_" + task_id).attr('disabled', false);
                            $('#taskname_' + task_id).html(data.content);
                            if (data.active === 1) {
                                $('#taskActive_' + task_id).prop('checked', true);
                            }
                            $("#details_" + task_id).attr('class', 'btn btn-xs btn-default show_details');
                            $("#description_" + task_id).html(data.short_description + '...');
                            $("#details_" + task_id).attr('data-task-content', data.content);
                            $("#details_" + task_id).attr('data-task-active', data.active);
                            $("#details_" + task_id).attr('data-description', data.description);
                            $("#details_" + task_id).attr('data-instructions', data.instructions);
                            $("#details_" + task_id).attr('data-task-tag', data.option);
                            $("#details_" + task_id).attr('data-amount', data.amount);
                            $("#details_" + task_id).attr('data-url', data.url);
                        }
                );
                $('#tag-modal').modal('hide');

            }

            $('#ot_confirm').on('click', function (event) {

                var amount = $("#ot_amount").val(),
                        description = $('#ot_description').val(),
                        instructions = $('#ot_instructions').val(),
                        content = $('#ot_content').val(),
                        url = $('#ot_url').val(),
                        $url = $("#ot_new_task").prop('action'),
                        active = $('#ot_active:checked').val(),
                        visible = $('#ot_visible:checked').val(),
                        other_task = 1,
                        option = $('#ot_tag').val();
                console.log(option);
                console.log(active);
                console.log(content);
                console.log(description);
                console.log(url);
                $.ajax(
                        {
                            method: "post",
                            url: $url,
                            dataType: "json",
                            data: {
                                "_token": $("#update-settings").find('input[name=_token]').val(),
                                "amount": amount,
                                "description": description,
                                "instructions": instructions,
                                "content": content,
                                "active": active,
                                "visible": visible,
                                "other_task": other_task,
                                "url": url,
                                "task_id": null,
                                "tasklist_id": null,
                                "user_id": $("#update-settings").find('input[name=user_id]').val(),
                                "project_id": $("#update-settings").find('input[name=project_id]').val(),
                                "option": option
                            }
                        }).done(
                        function (data) {
                            console.log(data);
                            location.reload();
                        }
                );
                event.preventDefault();

            });

            $('.show_details').on('click', function (event) {
                var $this = $(this),
                        task_id = $this.attr('data-task-id'),
                        tag = $this.attr('data-task-tag'),
                        tasklist_id = $this.attr('data-tasklist-id'),
                        task_content = $this.attr('data-task-content'),
                        task_amount = $this.attr('data-amount'),
                        task_active = $this.attr('data-task-active'),
                        task_url = $this.attr('data-url'),
                        task_description = $this.attr('data-description'),
                        task_instructions = $this.attr('data-instructions');
                var $modal_content = $('.modal-content');
                $modal_content.html('');
                $modal_content.load('/modals/updateClientTask.php', function () {
                    $('#task-content').attr('value', task_content);
                    $('#task-description').attr('value', task_description);
                    $('#task-instructions').attr('value', task_instructions);
                    $('#task-url').attr('value', task_url);
                    if (task_active === "1") {
                        $('#ct-active').attr('checked', true);
                    }
                    $('#payment-amount').attr('value', task_amount);
                    console.log('#radio-' + tag);
                    $('#radio-' + tag).attr('checked', true);
                    $('#update-confirm').on('click', function () {
                        updateClientTask(task_id, tasklist_id);
                    });
                    $('#delete-confirm').on('click', function () {
                        deleteClientTask(task_id);
                    });
                });
                $('#tag-modal').modal('show');
                event.preventDefault();
            });

            function deleteClientTask(task_id) {
                var $url = $("#client-task-form").prop('action');
                $.ajax(
                        {
                            method: "delete",
                            url: $url,
                            dataType: "json",
                            data: {
                                "_token": $("#update-settings").find('input[name=_token]').val(),
                                "task_id": task_id,
                                "user_id": $("#update-settings").find('input[name=user_id]').val(),
                                "project_id": $("#update-settings").find('input[name=project_id]').val(),
                            }
                        }).done(
                        function (data) {
                            console.log(data);
                            $('#taskname_' + task_id).html('');
                            $("#description_" + task_id).html('');
                            $("#instructions_" + task_id).html('');
                            $("#details_" + task_id).attr('class', 'hidden');
                            $('#newtask_' + task_id).prop('class', 'btn btn-xxs btn-success newclienttask');
                            $("#taskActive_" + task_id).attr('disabled', true);
                            $('#taskActive_' + task_id).prop('checked', false);
                        }
                );
                $('#tag-modal').modal('hide');
            }

            function updateClientTask(task_id, tasklist_id) {
                var amount = $("#payment-amount").val(),
                        description = $('#task-description').val(),
                        instructions = $('#task-instructions').val(),
                        content = $('#task-content').val(),
                        url = $('#task-url').val(),
                        $url = $("#client-task-form").prop('action'),
                        active = $('#ct-active:checked').val(),
                        option = $('input[name=tag]:checked').val();
                console.log(option);
                $.ajax(
                        {
                            method: "post",
                            url: $url,
                            dataType: "json",
                            data: {
                                "_token": $("#update-settings").find('input[name=_token]').val(),
                                "amount": amount,
                                "description": description,
                                "instructions": instructions,
                                "content": content,
                                "active": active,
                                "url": url,
                                "task_id": task_id,
                                "tasklist_id": tasklist_id,
                                "user_id": $("#update-settings").find('input[name=user_id]').val(),
                                "project_id": $("#update-settings").find('input[name=project_id]').val(),
                                "option": option
                            }
                        }).done(
                        function (data) {
                            console.log(data);
                            $('#taskname_' + task_id).html('');
                            $('#taskname_' + task_id).html(data.content);
                            if (data.active === 1) {
                                $('#taskActive_' + task_id).prop('checked', true);
                            } else {
                                $('#taskActive_' + task_id).prop('checked', false);
                            }
                            $("#description_" + task_id).html('');
                            $("#description_" + task_id).html(data.short_description + '...');
                            $("#details_" + task_id).attr('data-task-content', data.content);
                            $("#details_" + task_id).attr('data-task-active', data.active);
                            $("#details_" + task_id).attr('data-description', data.description);
                            $("#details_" + task_id).attr('data-instructions', data.instructions);
                            $("#details_" + task_id).attr('data-task-tag', data.option);
                            $("#details_" + task_id).attr('data-amount', data.amount);
                            $("#details_" + task_id).attr('data-url', data.url);
                            $("#taskActive_" + task_id).attr('disabled', false);
                        }
                );
                $('#tag-modal').modal('hide');
            }

            $('.visible').change(function () {
                        var id = this.name;
                        var option = ($(this).is(':checked') ? 1 : 0);
                        $.ajax(
                                {
                                    method: "put",
                                    url: $("#update-settings").prop('action'),
                                    dataType: "json",
                                    data: {
                                        "_token": $("#update-settings").find('input[name=_token]').val(),
                                        "value": this.value,
                                        "id": id,
                                        "user_id": $("#update-settings").find('input[name=user_id]').val(),
                                        "project_id": $("#update-settings").find('input[name=project_id]').val(),
                                        "option": option,
                                    }
                                }).done(
                                function (data) {
                                }
                        );
                    }
            );
            $('.updateOtherTask').change(function () {
                var ot_id = $(this).attr("data-id");
                var active = ($('#ot_active_' + ot_id).is(':checked') ? 1 : 0);
                var visible = ($('#ot_visible_' + ot_id).is(':checked') ? 1 : 0);
                var url = '/act';
                $.ajax(
                        {
                            method: "get",
                            url: url,
                            dataType: "json",
                            data: {
                                "user_id": {{$user_id}},
                                "ot_id": ot_id,
                                "active": active,
                                "visible": visible,
                            }
                        }).done(
                        function (data) {
                            console.log(data);
                        }
                );
            });


            $('.activeTask').change(function () {
                        var task_id = this.name;
                        var active = ($(this).is(':checked') ? 1 : 0);
                        var url = '/updatetag';
                        $.ajax(
                                {
                                    method: "put",
                                    url: url,
                                    dataType: "json",
                                    data: {
                                        "_token": $("#update-settings").find('input[name=_token]').val(),
                                        "task_id": task_id,
                                        "user_id": $("#update-settings").find('input[name=user_id]').val(),
                                        "active": active,
                                    }
                                }).done(
                                function (data) {
                                    console.log(data);
                                    $("#details_" + task_id).attr('data-task-active', data.active);
                                }
                        );
                    }
            );

            //prevent the form from actually submitting in browser
            return false;
        })
        ;
    </script>

@endsection
