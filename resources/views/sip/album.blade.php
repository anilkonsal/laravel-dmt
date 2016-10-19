@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Generate SIPs for Album Images</h3>
                </div>
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
                    <form method="post" action="{{ route('sip_generate_album') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('item_id') ? ' has-error' : '' }}">
                            <label for="item_id" class="col-md-4 control-label">Enter the Item ID for which you want to generate SIPs</label>
                            <div class="col-md-6">
                                <input id="item_id" type="text" class="form-control" name="item_id" value="{{ old('item_id') }}" required autofocus>
                                @if ($errors->has('item_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('item_id') }}</strong>
                                    </span>
                                @endif

                                <br/>

                                <button type="submit" class="btn btn-primary">
                                    Generate Now
                                </button>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($albumZipPath))
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Zip file for Item ID: {{ $itemId }}</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <a href={{ $albumZipPath}} ><i class="fa fa-download" aria-hidden="true"></i> Download File</a>
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
    @if(isset($logFile))
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>Log for Item ID: {{ $itemId }}</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                @if(!empty($logFile))
                                    <a href="{{ $logFile}}" target="_blank" ><i class="fa fa-eye" aria-hidden="true"></i> View Log</a>
                                @else
                                    <h4>Log file not generated, make sure that this item has any albums</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </diV>
    @endif
</div>
@endsection
