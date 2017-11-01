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
        <div class="row">
            <div class="col-md-12">
                @if (Auth::guest())
                    <div class="col-md-6 col-md-offset-3" style="background: white;border:1px solid #cccccc;padding: 15px;">
                        <div class="col-md-6">
                            <label for="">Login using your credentials:</label>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('/auth/google') }}">
                            <span class="btn btn-lg btn-default btn-login"><img src="img/ps.png" width="20px">
                                <span> Login </span>
                            </span>
                            </a>
                        </div>
                    </div>
                        {{--<div class="col-md-12">--}}
                            {{--<label for="">Login using your credentials:</label>--}}
                            {{--<a href="/login">--}}
                            {{--<span class="btn btn-lg btn-default btn-login">--}}
                                {{--<img src="img/ps.png" width="20px">--}}
                                    {{--Login--}}
                            {{--</span>--}}
                            {{--</a>--}}
                        {{--</div>--}}

                @elseif(Auth::user())
                    <div class="col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                        <div class="col-md-12">
                            <h4>Welcome {{ Auth::user()->name }}</h4>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <!-- Modal -->
                        <div class="modal fade" id="mailModal" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <form action="/registerotheremail" method="post">
                                        {{csrf_field()}}
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Email</h4>
                                        </div>
                                        <div class="modal-body">
                                            <label for="other_email_again"> Insert your email address, so we can keep in
                                                touch with you.</label>
                                            <input type="text" name="email" placeholder="Email" class="form-control"
                                                   style="width:300px">
                                            <label for="other_email_again">Repeat email:</label>
                                            <input type="text" name="email_confirmation" placeholder="Repeat email"
                                                   class="form-control"
                                                   style="width:300px">
                                            <br>
                                            <label for="useGmail">Or check the following checkbox if you want to use the
                                                Gmail <br>account we've set up for you: ({{Auth::user()->email}})
                                            </label><br>
                                            <input type="checkbox" value="true" name="use_gmail">
                                        </div>
                                        <div class="modal-footer">
                                            <input type="submit" class="btn btn-primary"
                                                   value="Save">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (Session::has('success'))
                            <div class="alert alert-success">
                                {{Session::get('success')}}
                            </div>
                        @endif
                        @if(!Auth::user()->confirmed && Auth::user()->confirmation_code!==null && !Session::has('success'))
                            <div class="alert alert-success">
                                Check your email and click on the link
                            </div>
                            @endif
                        <div class="col-md-12">

                            @if(isset($projects))
                                @if(count($projects))
                                    <table class="table table-bordered" style="background: white">
                                        <thead>
                                        <tr>
                                            <th>Project ID</th>
                                            <th>2ProjectName</th>
                                            <th>Details</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($projects as $project)
                                            <tr>
                                                <th>
                                                    {{$project->project_id}}
                                                </th>
                                                <th>
                                                    {{$project->project_name}}
                                                </th>
                                                <th>
                                                    <form action="{{url('/project/'.$project->project_id)}}"
                                                          method="post">
                                                        <input type="hidden" name="project_id"
                                                               value="{{$project->id}}}">
                                                        {{ csrf_field() }}
                                                        <input type="submit" class="btn btn-primary"
                                                               value="show project">
                                                    </form>
                                                </th>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    No project to be displayed
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection
@section('jquery')
    <script>
        jQuery(document).ready(function () {
            @if(Auth::user()&&Auth::user()->other_email===null)
                $('#mailModal').modal('show');
            @endif
        });
    </script>
@endsection
