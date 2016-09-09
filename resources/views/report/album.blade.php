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

                            <tr>
                                <td>Master</td>
                                <td>{{ $masters_count }}</td>
                            </tr>
                            <tr>
                                <td>Co Master</td>
                                <td>{{ $comasters_count }}</td>
                            </tr>
                            <tr>
                                <td>Hi Resolution</td>
                                <td>{{ $hires_count }}</td>
                            </tr>
                            <tr>
                                <td>Standard Resolution</td>
                                <td>{{ $stdres_count }}</td>
                            </tr>
                            <tr>
                                <td>Preview</td>
                                <td>{{ $preview_count }}</td>
                            </tr>
                            <tr>
                                <td>Thumbnail</td>
                                <td>{{ $thumbnail_count }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
