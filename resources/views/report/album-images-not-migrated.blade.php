@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Albums Images not Migrated Counts
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
                                <td>Masters Count</td>
                                <td>{{ $counts->totalMasterCount }}</td>
                                <td>{{ $counts->totalMasterCount - $counts->mastersCount }}</td>
                                <td>{{ $counts->mastersCount }}</td>
                            </tr>
                            <tr>
                                <td>Co Masters Count</td>
                                <td>{{ $counts->totalCoMasterCount }}</td>
                                <td>{{ $counts->totalCoMasterCount - $counts->comastersCount }}</td>
                                <td>{{ $counts->comastersCount }}</td>
                            </tr>
                            <tr>
                                <td>Hi Res Count</td>
                                <td>{{ $counts->totalHiResCount }}</td>
                                <td>{{ $counts->totalHiResCount - $counts->hiresCount }}</td>
                                <td>{{ $counts->hiresCount }}</td>
                            </tr>
                            <tr>
                                <td>Std Res Count</td>
                                <td>{{ $counts->totalStdResCount }}</td>
                                <td>{{ $counts->totalStdResCount - $counts->stdresCount }}</td>
                                <td>{{ $counts->stdresCount }}</td>
                            </tr>
                            <tr>
                                <td>Preview Count</td>
                                <td>{{ $counts->totalPreviewCount }}</td>
                                <td>{{  $counts->totalPreviewCount - $counts->previewCount }}</td>
                                <td>{{ $counts->previewCount }}</td>
                            </tr>
                            <tr>
                                <td>Thumbnail Count</td>
                                <td>{{ $counts->totalThumbnailCount }}</td>
                                <td>{{ $counts->totalThumbnailCount - $counts->thumbnailCount }}</td>
                                <td>{{ $counts->thumbnailCount }}</td>
                            </tr>
                            <tr>
                                <td>Total Count</td>
                                <td></td>
                                <td></td>
                                <td>{{ $counts->mastersCount + $counts->comastersCount + $counts->hiresCount + $counts->stdresCount + $counts->previewCount + $counts->thumbnailCount}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
