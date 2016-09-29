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
                                <td>Total Albums</td>
                                <td>Not Empty Albums</td>
                                <td>Empty Albums</td>
                            </tr>
                            <tr>
                                <td>{{ $counts['totalAlbumCount'] }}</td>
                                <td>{{ $counts['notEmptyAlbumCount'] }}</td>
                                <td>{{ $counts['totalAlbumCount'] - $counts['notEmptyAlbumCount']}}</td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
