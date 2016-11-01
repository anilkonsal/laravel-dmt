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
                        <div class="form-group{{ $errors->has('ies') ? ' has-error' : '' }}">
                            <label for="ies" class="col-md-4 control-label">Enter the Comma Separated list of IEs which you want to QA</label>
                            <div class="col-md-6">
                                <textarea id="ies"  class="form-control" name="ies" rows="5" autofocus>{{ old('ies') }}</textarea>
                                @if ($errors->has('ies'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ies') }}</strong>
                                    </span>
                                @endif
                                <br/>
                                <button type="submit" class="btn btn-primary">
                                    Start QA
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($data))
    <?php $areImagesInOrder = true; ?>
        @foreach ($data as $ie => $item)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $ie }} - {{ $item['api']['identifier'] }}
                    <small><a href="http://acmssearch.sl.nsw.gov.au/search/itemDetailPaged.cgi?itemID={{ $item['api']['identifier'] }}" target="_blank">View</a></small>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-5">
                        <h4>API</h4>
                        <table class="table table-striped table-bordered">
                            <tr><td>Title</td><td>{{ $item['api']['title'] }}</td></tr>
                            <tr><td>Type</td><td>{{ $item['api']['type'] }}</td></tr>
                            <tr><td>Source</td><td>{{ $item['api']['source'] }}</td></tr>
                            <tr>
                                <td>Image(s)</td>
                                <td>
                                    @if (!empty($item['api']['files']))
                                        <ol>
                                            @foreach ($item['api']['files'] as $key => $file)
                                                <li><a href="{{ $file }}" target="_blank">{{ $file }}</a></li>
                                                @if ($areImagesInOrder && ($file != $item['html']['files'][$key]))
                                                    <?php $areImagesInOrder = false; ?>
                                                @endif
                                            @endforeach
                                        </ol>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-5">
                        <h4>HTML</h4>
                        <table class="table table-striped table-bordered">
                            <tr><td>Title</td><td>{{ $item['html']['title'] }}</td></tr>
                            <tr><td>Type</td><td>{{ $item['html']['type'] }}</td></tr>
                            <tr><td>Source</td><td>{{ $item['html']['source'] }}</td></tr>
                            <tr><td>Image(s)</td>
                                <td>
                                    @if (!empty($item['html']['files']))
                                        <ol>
                                            @foreach ($item['html']['files'] as $file)
                                                <li><a href="{{ $file }}" target="_blank">{{ $file }}</a></li>
                                            @endforeach
                                        </ol>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-2">
                        @if ($item['api']['title'] != $item['html']['title'])
                            <i class="fa fa-4x fa-times text-danger"></i> <p>Title Does not match</p>
                        @elseif ($item['api']['type'] != $item['html']['type'])
                            <i class="fa fa-4x fa-times text-danger"></i> <p>Type Does not match</p>
                        @elseif ($item['api']['source'] != $item['html']['source'])
                            <i class="fa fa-4x fa-times text-danger"></i> <p>Source Does not match</p>
                        @elseif ($areImagesInOrder === false)
                            <i class="fa fa-4x fa-exclamation-circle text-danger" aria-hidden="true"></i> <p>Images not in order!</p>
                        @else
                            <i class="fa fa-4x fa-check-square text-success"></i> <p>All Good</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
