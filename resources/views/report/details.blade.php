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
                    <form method="post" action="{{ route('details_report') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('item_id') ? ' has-error' : '' }}">
                            <label for="item_id" class="col-md-4 control-label">Item ID</label>

                            <div class="col-md-6">
                                <input id="item_id" type="text" class="form-control" name="item_id" value="{{ old('item_id') }}" required autofocus>

                                @if ($errors->has('item_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('item_id') }}</strong>
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

                                </tbody>
                            </table>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
