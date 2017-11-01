<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h3>{{$project['name']}}</h3>
        <br>
        New task added: <strong> {{ $task['name'] }}</strong><br><br>
        Task description: {{ $task['description'] }}
        <br>
        <br>
        Project: {{env('APP_URL')}}/project/{{ $project['id'] }}
    </body>
</html>
