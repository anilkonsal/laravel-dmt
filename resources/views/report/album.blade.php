@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Albums Report
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Albums Count</td>
                                <td>{{ $albums_count }}</td>
                            </tr>
                            <tr>
                                <td>Images in Albums Count</td>
                                <td>{{ $images_in_albums_count }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
