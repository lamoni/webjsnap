@extends('master')
@section('content')

    <div class="row">
        <div class="col-lg-offset-1 col-lg-10">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-cog"></i> Settings
                </div>
                <div class="panel-body">
                    <form action="/settings/save" method="POST">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        @foreach ($config as $configTypeName => $configType)
                            <h2>{{strtoupper($configTypeName)}}</h2>
                            @foreach($configType as $configName => $configValue)
                                <div class="form-group">
                                    <label for="{{$configName}}">{{$configName}}</label>
                                    <input type="text" class="form-control" name="{{$configTypeName}}_{{$configName}}" id="{{$configName}}" value="{{$configValue}}">
                                </div>
                            @endforeach
                        @endforeach
                        <br/>
                        <button type="submit" class="btn btn-danger btn-block">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

    <script>
        $(document).ready(function() {

        });
    </script>
@endsection