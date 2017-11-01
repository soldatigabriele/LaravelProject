@extends('layouts.app')
@section('content')
    <div class="container">
        <tr class="col-md-12">
            <div class="col-md-8 col-md-offset-2">
                @if(isset($data))
                    @if(count($data))
                        <div class="col-md-12">
                            <h3> USER EMAIL: {{$data['user_email']}} - ID: {{  $data['user_id'] }} </h3>
                        </div>
                    @endif
                    <div class="clearfix"></div>
                    <hr>
                @endif
                @if(isset($folders))
                    @if(count($folders))

                        <div class="col-md-12">
                            <div class="col-md-9 col-md-offset-1">
                                CURRENT FOLDER: <strong>{{$data['folder_id']}}</strong>
                            </div>
                            <div class="clearfix"></div>
                            <br>
                            <div class="col-md-2">
                                <form action="/admin/user/{{$data['user_id']}}/project/{{$data['project_id']}}" method="get">
                                    <input type="submit" class="btn btn-default" value="Go Back">
                                    {{csrf_field()}}
                                </form>
                            </div>
                            <div class="col-md-10">

                                <form action="{{url('/admin/user/'.$data['user_id'].'/folder/')}}" method="get">
                                    <div class="col-md-7 col-md-offset-0">
                                        <select name="folder_id" id="" class="form-control">
                                            @foreach($folders as $folder)
                                                <option value="{{$folder->folder_id}}"
                                                        @if($folder->folder_id === $data['folder_id']) selected @endif>{{$folder->folder_id}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        {{ csrf_field() }}
                                        <input type="submit" class="btn btn-success" value="Change Folder">
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    <div class="clearfix"></div>
                    <hr>
                @endif


                @if(isset($results))
                    <table class="table table-bordered">
                        <tr>
                            <th>Name</th>
                            <th>Ext</th>
                            <th>Image</th>
                        </tr>
                        @foreach($results as $result)
                            <tr>
                                <th> {{$result->name}} </th>
                                <th> .{{$result->mime}} </th>
                                <th><img src="{{$result->src}}" alt="" width="100px"/></th>
                            </tr>
                        @endforeach
                    </table>
                @endif
                <div class="clearfix"></div>
            </div>
    </div>
@endsection