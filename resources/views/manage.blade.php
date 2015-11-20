@extends('master')
@section('content')

    <div class="row">
        <div class="col-lg-offset-1 col-lg-10">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-calendar"></i> Manage
                </div>
                <div class="panel-body">
                    <table class="table table-bordered" id="tableSnapshots" class="display" cellspacing="0" width="100%">

                        @if (isset($snapshots['snapshots']) && count($snapshots['snapshots']) > 0)
                            <thead>
                            <tr>
                                <th class="text-center">
                                    Hostname
                                </th>
                                <th class="text-center">
                                    Date
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                        @foreach($snapshots['snapshots'] as $deviceName => $snapshotPerDevice)
                            @foreach ($snapshotPerDevice as $snapTime => $date)
                                <tr>
                                    <td class="text-center">
                                        {{$deviceName}}
                                    </td>
                                    <td class="text-center">
                                        {{$date}}
                                    </td>
                                    <td class="text-center">
                                        <form>
                                            <input type="hidden" name="deleteHostname" value="{{$deviceName}}">
                                            <input type="hidden" name="deleteTime" value="{{$snapTime}}">

                                            <button type="button" class="btn btn-sm btn-warning buttonDeleteSnapshot">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach

                            </tbody>
                        @else
                            <thead>
                            <th class="text-center">
                                No snapshots
                            </th>
                            </thead>
                        @endif
                    </table>


                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.buttonDeleteSnapshot').click(function(e) {
                e.preventDefault();

                $button = $(this);
                $form = $button.parent('form');
                $.post("/manage/delete", $form.serialize() , function (data) {

                    if (data.error === 0) {

                        $form.parents('tr').remove();
                        return;

                    }

                    $button.button('reset');
                }, 'json');
            });
        } );
    </script>
@endsection