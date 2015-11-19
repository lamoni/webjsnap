@extends('master')
@section('content')
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-camera"></i> Snap
                </div>
                <div class="panel-body">
                    <form id="formSnap">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="form-group">
                            <label for="snapHostname">Hostname</label>
                            <input type="text" class="form-control" id="snapHostname" name="snapHostname" placeholder="Hostname">
                        </div>
                        <button id="btnSubmit" type="submit" class="btn btn-success btn-block" data-loading-text="Snapping..."><i class="glyphicon glyphicon-camera"></i> Snap</button>
                    </form>
                    <div id="alertResult" class="alert" style="display:none; margin-top: 14px; margin-bottom:0; text-align: center;"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        $('#formSnap').submit(function(e) {
            e.preventDefault();

            var $alertResult = $('#alertResult');
            var $btnSubmit = $('#btnSubmit');

            $alertResult.hide().html('');
            $btnSubmit.button('loading').prop('disabled', true);

            $.post( "/snap/snapshot/", $( "#formSnap" ).serialize() , function (data) {
                if (data.error == 0) {
                    $alertResult.addClass('alert-success').removeClass('alert-danger');
                }
                else {
                    $alertResult.addClass('alert-danger').removeClass('alert-success');
                }

                $alertResult.html(data.html).show();
                $btnSubmit.button('reset');
            }, 'json');
        });


    </script>
@endsection