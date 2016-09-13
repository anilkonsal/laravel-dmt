@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Detail Report
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><h3>Stand Alone</h3></td>
                                    </tr>
                                    <tr>
                                        <td>Master Count</td>
                                        <td>{{ $count['masterCount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Comaster Count</td>
                                        <td>{{ $count['comasterCount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Hi Res Count</td>
                                        <td>{{ $count['hiresCount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Std Res Count</td>
                                        <td>{{ $count['stdresCount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Preview Count</td>
                                        <td>{{ $count['previewCount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Thumbnail Count</td>
                                        <td>{{ $count['thumbnailCount'] }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><h3>Album</h3></td>
                                    </tr>
                                    <tr>
                                        <td>Master Count</td>
                                        <td>{{ $count['albumMasterCount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Comaster Count</td>
                                        <td>{{ $count['albumComasterCount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Hi Res Count</td>
                                        <td>{{ $count['albumHiresCount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Std Res Count</td>
                                        <td>{{ $count['albumStdresCount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Preview Count</td>
                                        <td>{{ $count['albumPreviewCount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Thumbnail Count</td>
                                        <td>{{ $count['albumThumbnailCount'] }}</td>
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
