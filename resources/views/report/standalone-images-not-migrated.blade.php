@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Standalone Images not Migrated Counts
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <h2>ACMS Images</h2>
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
                                        <td>{{ $counts->totalMasterCountAcms }}</td>
                                        <td>{{ $counts->totalMasterCountAcms - $counts->mastersCountAcms }}</td>
                                        <td>{{ $counts->mastersCountAcms }}</td>
                                    </tr>
                                    <tr>
                                        <td>Co Masters Count</td>
                                        <td>{{ $counts->totalCoMasterCountAcms }}</td>
                                        <td>{{ $counts->totalCoMasterCountAcms - $counts->comastersCountAcms }}</td>
                                        <td>{{ $counts->comastersCountAcms }}</td>
                                    </tr>
                                    <tr>
                                        <td>Hi Res Count</td>
                                        <td>{{ $counts->totalHiResCountAcms }}</td>
                                        <td>{{ $counts->totalHiResCountAcms - $counts->hiresCountAcms }}</td>
                                        <td>{{ $counts->hiresCountAcms }}</td>
                                    </tr>
                                    <tr>
                                        <td>Std Res Count</td>
                                        <td>{{ $counts->totalStdResCountAcms }}</td>
                                        <td>{{ $counts->totalStdResCountAcms - $counts->stdresCountAcms }}</td>
                                        <td>{{ $counts->stdresCountAcms }}</td>
                                    </tr>
                                    <tr>
                                        <td>Preview Count</td>
                                        <td>{{ $counts->totalPreviewCountAcms }}</td>
                                        <td>{{  $counts->totalPreviewCountAcms - $counts->previewCountAcms }}</td>
                                        <td>{{ $counts->previewCountAcms }}</td>
                                    </tr>
                                    <tr>
                                        <td>Thumbnail Count</td>
                                        <td>{{ $counts->totalThumbnailCountAcms }}</td>
                                        <td>{{ $counts->totalThumbnailCountAcms - $counts->thumbnailCountAcms }}</td>
                                        <td>{{ $counts->thumbnailCountAcms }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Count</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $counts->mastersCountAcms + $counts->comastersCountAcms + $counts->hiresCountAcms + $counts->stdresCountAcms + $counts->previewCountAcms + $counts->thumbnailCountAcms }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <h2>Millenium Images</h2>
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
                                        <td>{{ $counts->totalMasterCountMill }}</td>
                                        <td>{{ $counts->totalMasterCountMill - $counts->mastersCountMill }}</td>
                                        <td>{{ $counts->mastersCountMill }}</td>
                                    </tr>
                                    <tr>
                                        <td>Co Masters Count</td>
                                        <td>{{ $counts->totalCoMasterCountMill }}</td>
                                        <td>{{ $counts->totalCoMasterCountMill - $counts->comastersCountMill }}</td>
                                        <td>{{ $counts->comastersCountMill }}</td>
                                    </tr>
                                    <tr>
                                        <td>Hi Res Count</td>
                                        <td>{{ $counts->totalHiResCountMill }}</td>
                                        <td>{{ $counts->totalHiResCountMill - $counts->hiresCountMill }}</td>
                                        <td>{{ $counts->hiresCountMill }}</td>
                                    </tr>
                                    <tr>
                                        <td>Std Res Count</td>
                                        <td>{{ $counts->totalStdResCountMill }}</td>
                                        <td>{{ $counts->totalStdResCountMill - $counts->stdresCountMill }}</td>
                                        <td>{{ $counts->stdresCountMill }}</td>
                                    </tr>
                                    <tr>
                                        <td>Preview Count</td>
                                        <td>{{ $counts->totalPreviewCountMill }}</td>
                                        <td>{{  $counts->totalPreviewCountMill - $counts->previewCountMill }}</td>
                                        <td>{{ $counts->previewCountMill }}</td>
                                    </tr>
                                    <tr>
                                        <td>Thumbnail Count</td>
                                        <td>{{ $counts->totalThumbnailCountMill }}</td>
                                        <td>{{ $counts->totalThumbnailCountMill - $counts->thumbnailCountMill }}</td>
                                        <td>{{ $counts->thumbnailCountMill }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Count</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $counts->mastersCountMill + $counts->comastersCountMill + $counts->hiresCountMill + $counts->stdresCountMill + $counts->previewCountMill + $counts->thumbnailCountMill }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
