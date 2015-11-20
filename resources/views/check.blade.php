@extends('master')
@section('content')
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-list-alt"></i> Check
                </div>
                <div class="panel-body">
                    <form id="formPreSnaps">
                        <div class="form-group">
                            <label for="checkHostname">Hostname</label>
                            <input type="text" class="form-control" id="checkHostname" name="checkHostname" placeholder="Hostname">
                        </div>
                        <button id="btnCheck" type="submit" class="btn btn-primary btn-block" data-loading-text="Snapping..."><i class="glyphicon glyphicon-ok"></i> Check</button>

                        <div id="alertResult" class="alert" style="display:none; margin-top: 14px; margin-bottom:0; text-align: center;"></div>

                        <div id="divCheckResults" style="display: none;"></div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        $(document).ready(function(e) {

            var $alertResult = $('#alertResult');
            var $btnCheck  = $('#btnCheck');

            $btnCheck.click(function (e) {
                e.preventDefault();

                $divCheckResults = $('#divCheckResults');
                $divCheckResults.html('');
                $alertResult.html('').hide();
                $btnCheck.button('loading');

                $.post("/check/check", $( "#formPreSnaps" ).serialize() , function (data) {

                    if (data.error == 0) {

                        console.log(data.result);
                        $.each(data.result.failedTests, function(key,value){

                            $divCheckResults.append('<h4 class="text-danger">'+key+'</h4>');

                            $.each(value, function (key2, value2) {

                                $divCheckResults.append('<h5>'+key2+'</h5>&nbsp;&nbsp;- '+value2).show();

                            });

                            $divCheckResults.append('<hr>');

                        });

                        $.each(data.result.passedTests, function(key,value){

                            $divCheckResults.append('<h4 class="text-success">'+key+'</h4>');

                            $.each(value, function (key2, value2) {

                                $divCheckResults.append('<h5> - '+value2+'</h5>').show();

                            });
                            $divCheckResults.append('<hr>');

                        });
                    }
                    else {

                        $alertResult.addClass('alert-danger').removeClass('alert-success').html(data.html).show();

                    }
                    $btnCheck.button('reset');
                }, 'json');

            });

        });


    </script>
@endsection