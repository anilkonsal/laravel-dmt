@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Stand alone Images Report
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
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
                            <tr>
                                <td>Total images (including all Reps)</td>
                                <td>{{ $standalone_images_count }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
