<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h3>{{$project['name']}}</h3>
        <br>
        <strong>{{ $user['name'] }} </strong> has completed the task:  <strong> {{ $task }}</strong>
        <br>
        User mail: {{ $user['email'] }}
        <br>
        Project: {{env('APP_URL')}}/admin/user/{{ $user['id'] }}/project/{{ $project['id'] }}
    </body>
</html>
