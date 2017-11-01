@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="col-md-10 col-md-offset-1">
            <form action="/admin/user" method="post">
                {{ csrf_field() }}
                {{--<div class="col-md-1" style="position:relative;top:6px;">--}}
                {{--<label for="email">Google Email: </label>--}}
                {{--</div>--}}
                <div class="col-md-3">
                    <input type="text" name="email" placeholder="Google Email" class="form-control"
                           style="width:200px">
                </div>
                {{--<div class="col-md-1" style="position:relative;top:6px;">--}}
                {{--<label for="name">: (opt) </label>--}}
                {{--</div>--}}
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Name and Surname (opt.)"
                           style="width:200px">
                </div>
                {{--<div class="col-md-1" style="position:relative;top:6px;">--}}
                {{--<label for="other_email">Other email: (opt) </label>--}}
                {{--</div>--}}
                <div class="col-md-3">
                    <input type="text" name="other_email" placeholder="Other Email (opt.)" class="form-control"
                           style="width:200px">
                </div>
                <div class="col-md-1">
                    <label for="admin">admin: </label>
                </div>
                <div class="col-md-1">
                    <input type="checkbox" name="admin" value="true">
                </div>
                <div class="col-md-1" style="background: ">
                    <input type="submit" class="btn btn-md btn-success" value="Add Account">
                </div>
            </form>
            {{--</div>--}}
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="clearfix"></div>
        <hr>
        <div class="col-md-10 col-md-offset-1">
            @if(session('deleted'))
                <div class="alert alert-danger">
                    User: {{session('deleted')}} has been successfully deleted
                </div>
            @endif
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>TW ID</th>
                    <th>Email</th>
                    <th>Other Email</th>
                    <th>Is Admin</th>
                    <th>Mail Confirmed</th>
                    <th>Delete</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)

                    <tr>
                        <th scope="row">{{$user->id}}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->surname }}</td>
                        <td>{{ $user->teamwork_id }}</td>
                        <td>
                            <a href="/admin/user/{{$user->id}}">
                                {{ $user->email }}
                            </a>
                        </td>
                        <td>{{ $user->other_email }}</td>
                        @if($user->admin == 0 )
                            <td class="">no</td>
                        @else
                            <td class="table-success">yes</td>
                        @endif
                        <td>
                            @if($user->confirmed == 0 )
                                no
                            @else
                                yes
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger delete-button" data-user-id="{{$user->id}}"
                                    data-toggle="modal" data-user-email="{{$user->email}}"
                                    data-target="#confirm-delete">
                                Delete user
                            </button>

                        </td>
                        <td>
                            <form action="{{ url('/admin/user')}}/{{$user->id}}" method="get"> 
                                <button type="submit" class="btn btn-sm btn-primary">Details</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Delete Confirm
                </div>
                <div class="modal-body">
                    Do you really want delete <strong><span id="username"></span></strong> ?
                </div>
                <form action="" id="form-delete" method="post"> 
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                         {{ method_field('DELETE') }} 
                        {{ csrf_field() }}
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="clearfix"></div><br>

@endsection
@section('jquery')
    <script>
        jQuery(document).ready(function () {
            $('.delete-button').on('click', function (event) {
                var url = '/admin/user/' + $(this).attr('data-user-id');
                var email = $(this).attr('data-user-email');
                console.log(url);
                $('#form-delete').attr('action', url);
                $('#username').html(email);

                $('#tag-modal').modal('show');
                event.preventDefault();
            });
        });
    </script>
@endsection