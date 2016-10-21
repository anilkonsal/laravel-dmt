@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Generate SIPs for Standalone Images</h3>
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
                    <form method="post" action="{{ route('sip_generate_standalone') }}">
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
                                <input id="force_generation" type="checkbox" name="force_generation" value="yes">&nbsp;&nbsp;Force Regeneration
                                    <a href="javascript:void(0);" data-toggle="popover" data-placement="right" title="Help"
                                    data-content="When this option is checked, system will ignore whether this item has been Marked Migrated in database and will generate it."
                                    data-original-title="Popover Header" aria-describedby="popover573097"><i class="fa fa-question-circle-o"></i></a>
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

    @if(!empty($standAloneZipPath))
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Detail Report for images of Item ID: {{ $itemId }}</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <a href={{ $standAloneZipPath}} ><i class="fa fa-download" aria-hidden="true"></i> Download File</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        <div class="col-md-6 col-lg-6">
                            <a href="{{ $logFile}}" target="_blank" ><i class="fa fa-eye" aria-hidden="true"></i> View Log</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </diV>
    @endif
</div>
@endsection
