@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Millenium Albums Migration Status
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td></td>
                                <td>Total</td>
                                <td>Migrated</td>
                                <td>Not Migrated</td>
                            </tr>
                            <tr>
                                <td>Counts</td>
                                <td>{{ $counts->totalAlbumsCount }}</td>
                                <td>{{ $counts->albumsMigrated }}</td>
                                <td>{{ $counts->albumsNotMigratedCount }}</td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
