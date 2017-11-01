@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="col-md-8 col-md-offset-2">
                <h4>Users</h4>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Google ID</th>
                        <th>Creation Date</th>
                        <th>Is Admin</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">{{$user->id}}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->google_id }}</td>
                        <td>{{ $user->created_at }}</td>
                        @if($user->admin === 0 )
                            <td class="table-danger"> no</td>
                        @else
                            <td class="table-success"> yes</td>
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-8 col-md-offset-2">
                <hr>
            </div>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @if(count($projects))
                <div class="col-md-8 col-md-offset-2">

                    <h4>Teamwork Projects</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>#</th>
                            <th>Project ID</th>
                            <th>Project Name</th>
                            <th>Added Date</th>
                            <th>Remove</th>
                            <th>Configure</th>
                        </tr>
                        @foreach($projects as $project)
                            <tr>
                                <td>{{ $project->id }}</td>
                                <td>
                                    <a href="{{$user->id}}/project/{{$project->project_id}}"> 
                                        {{ $project->project_id }}
                                    </a>
                                </td>
                                <td>{{ $project->project_name }}</td>
                                <td>{{ $project->created_at }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger delete-button" data-user-id="{{$user->id}}"
                                            data-toggle="modal" data-project-id="{{$project->id}}"
                                            data-target="#confirm-delete">
                                        Delete Project
                                    </button>
                                </td>
                                <td>
                                    <form action="/admin/user/{{$user->id}}/project/{{$project->project_id}}" method="get"> 
                                        <button type="submit" class="btn btn-sm btn-primary">Details</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                Delete Confirm
                            </div>
                            <div class="modal-body">
                                Do you really want delete <strong><span id="project"></span></strong> ?
                            </div>
                            <form action="" id="form-delete" method="post"> 
                                <input type="hidden" id="user_id" name="user_id">
                                <input type="hidden" id="project_id" name="project_id">
                                 {{ method_field('DELETE') }} 
                                {{ csrf_field() }}
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div><br>
            @endif
            @if(session('error'))
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-danger">
                        {{session('error')}}
                    </div>
                </div>
            @endif
            <div class="col-md-8 col-md-offset-2">
                <div class="col-md-8">
                    <form action="/admin/project" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <div class="col-md-4">
                            <input type="text" name="project_id" maxlength="10" class="form-control"
                                   style="width:110px">
                        </div>
                        <div class="col-md-4">
                            <input type="submit" class="btn btn-md btn-success" value="Add a Project">
                        </div>
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div><br>
@endsection
@section('jquery')
    <script>
        jQuery(document).ready(function(){
            $('.delete-button').on('click', function (event) {
                var url = '/admin/project';
                var project_id = $(this).attr('data-project-id');
                var user_id = $(this).attr('data-user-id');
                $('#form-delete').attr('action', url);
                $('#project_id').attr('value',project_id);
                $('#user_id').attr('value',user_id);

                $('#tag-modal').modal('show');
                event.preventDefault();
            });
        });
    </script>
@endsection
