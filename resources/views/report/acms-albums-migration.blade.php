@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    ACMS Albums Migration Status
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
                                <td>{{ $counts->totalAcmsAlbumsCount }}</td>
                                <td>{{ $counts->acmsAlbumsMigrated }}</td>
                                <td>{{ $counts->acmsAlbumsNotMigratedCount }}</td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
