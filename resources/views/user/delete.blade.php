@foreach($studioTasks as $tasklist)
    @foreach($tasklist as $task)
        @if( $task['fk_tasklist'] === $list['tasklist_id'])
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
                    <th>
                    </th>
                </tr>
            @endif

            </tr>
        @endif
    @endforeach
@endforeach
