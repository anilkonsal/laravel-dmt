@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="panel panel-default">

                <div class="panel-body">
                    @if ($errors->count())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="post" action="{{ route('post_ingest_qa') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('item_id') ? ' has-error' : '' }}">
                            <label for="date" class="col-md-4 control-label">Enter the Date after which you want to search</label>
                            <div class="col-md-6">
                                <input id="date" type="text" class="form-control datepicker" name="date" value="{{ old('date') }}" autofocus>

                                @if ($errors->has('date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                @endif

                                <br/>
                                <button type="submit" class="btn btn-primary">
                                    Fetch Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($count))
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Detail Report for images of Item ID: {{ $item_id }}</h3>
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
                                    <tr>
                                        <td>Total Images (Including all representations)</td>
                                        <td>{{ $count['thumbnailCount'] + $count['previewCount'] +$count['stdresCount'] + $count['hiresCount'] + $count['comasterCount'] + $count['masterCount'] }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td colspan><h3>Albums</h3></td>
                                        <td><h3>{{ $count['albumsCount'] }}</h3></td>
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
                                    <tr>
                                        <td>Total Images (Including all representations)</td>
                                        <td>{{ $count['albumMasterCount'] + $count['albumComasterCount'] +$count['albumHiresCount'] + $count['albumStdresCount'] + $count['albumPreviewCount'] + $count['albumThumbnailCount'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>


                </div>
            </div>
        </div>
        @if($debug)
        <div class="col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Itemized Report</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>Item ID</td><td>Albums Count</td><td>Album Images</td><td>Stand Alone Images</td>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($itemizedCounts as $itemID =>  $itemizedCount)
                            <tr>
                                <td>{{ $itemID }}</td><td>{{ $itemizedCount['albumsCount'] }}</td><td>{{ $itemizedCount['albumImagesCount'] }}</td><td>{{ $itemizedCount['standaloneImagesCount'] }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
