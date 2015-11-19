@extends('master')
@section('content')
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-list-alt"></i> Compare
                </div>
                <div class="panel-body">
                    <form id="formPreSnaps">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="form-group">
                            <label for="compareHostname">Hostname</label>
                            <input type="text" class="form-control" id="compareHostname" name="compareHostname" placeholder="Hostname">
                        </div>
                        <button id="btnRetrieve" type="submit" class="btn btn-info btn-block" data-loading-text="Retrieving..."><i class="glyphicon glyphicon-camera"></i> Retrieve</button>

                        <div id="alertResult" class="alert" style="display:none; margin-top: 14px; margin-bottom:0; text-align: center;"></div>

                        <br/>
                        <div class="divPreSnaps">
                            <div class="form-group">
                                <label for="selectPreSnap">Pre-Snap</label>
                                <select class="form-control" name="selectPreSnap" id="selectPreSnap" disabled>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="selectPostSnap">Post-Snap</label>
                                <select class="form-control" name="selectPostSnap" id="selectPostSnap" disabled>
                                </select>
                            </div>

                            <button id="btnCompare" type="submit" class="btn btn-info btn-block" data-loading-text="Comparing..." disabled><i class="glyphicon glyphicon-camera"></i> Compare</button>

                            <div id="divCompareResults" style="display: none;"></div>
                        </div>
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
            var $btnRetrieve = $('#btnRetrieve');
            var $btnCompare  = $('#btnCompare');

            $btnRetrieve.click(function(e) {
                e.preventDefault();


                $alertResult.hide().html('');
                $btnRetrieve.button('loading').prop('disabled', true);

                $('#selectPreSnap').prop('disabled', true).find('option').remove();
                $('#selectPostSnap').prop('disabled', true).find('option').remove();

                $.post("/compare/presnaps", $( "#formPreSnaps" ).serialize() , function (data) {

                    if (data.error == 0) {

                        $.each(data.presnaps, function(key,value) {
                            var d = new Date(0);
                            d.setSeconds(key);

                            $('#selectPreSnap').prepend($("<option></option>")
                                    .attr("value",key)
                                    .text(value + ' - ' + d)).prop('disabled', false);
                        });


                        $.each(data.postsnaps, function(key,value){
                            var d = new Date(0);
                            d.setSeconds(key);

                            $('#selectPostSnap').prepend($("<option></option>")
                                    .attr("value",key)
                                    .text(value + ' - ' + d)).prop('disabled', false);
                        });

                        $btnCompare.prop('disabled', false);

                    }
                    else {
                        $alertResult.addClass('alert-danger').removeClass('alert-success');

                    }
                    $btnRetrieve.button('reset');
                }, 'json');
            });

            $('#selectPreSnap').change(function(e) {
                e.preventDefault();
                $alertResult.html('').hide();
                $.post("/compare/postsnaps", $( "#formPreSnaps" ).serialize() , function (data) {
                    $('#selectPostSnap').prop('disabled', true).find('option').remove();

                    if (data.error == 0) {

                        $.each(data.postsnaps, function(key,value){
                            var d = new Date(0);
                            d.setSeconds(key);

                            $('#selectPostSnap').prepend($("<option></option>")
                                    .attr("value",key)
                                    .text(value + ' - ' + d)).prop('disabled', false);
                        });
                    }
                    else {

                        $alertResult.addClass('alert-danger').removeClass('alert-success').html(data.html).show();

                    }
                    $btnRetrieve.button('reset');
                }, 'json');

            });

            $btnCompare.click(function (e) {
                e.preventDefault();

                $divCompareResults = $('#divCompareResults');

                $divCompareResults.html('');
                $.post("/compare/compare", $( "#formPreSnaps" ).serialize() , function (data) {

                    if (data.error == 0) {

                        if (data.result.hasOwnProperty('failedTests')) {
                            $.each(data.result.failedTests, function (key, value) {

                                $divCompareResults.append('<h4 class="text-danger">' + key + '</h4>');

                                $.each(value, function (key2, value2) {

                                    $divCompareResults.append('<h5>' + key2 + '</h5>&nbsp;&nbsp;- ' + value2).show();

                                });

                                $divCompareResults.append('<hr>');

                            });
                        }

                        if (data.result.hasOwnProperty('passedTests')) {

                            $.each(data.result.passedTests, function (key, value) {

                                $divCompareResults.append('<h4 class="text-success">' + key + '</h4>');

                                $.each(value, function (key2, value2) {

                                    $divCompareResults.append('<h5> - ' + value2 + '</h5>').show();

                                });
                                $divCompareResults.append('<hr>');

                            });
                        }
                    }
                    else {

                        $alertResult.addClass('alert-danger').removeClass('alert-success').html(data.html).show();

                    }
                    $btnRetrieve.button('reset');
                }, 'json');

            });

        });


    </script>
@endsection