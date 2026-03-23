@extends('layouts.front.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Cataloage</h1>
            <p>Cataloagele de produse DMG SHOP.</p>
            @if(!empty($catalogs) && count($catalogs) > 0)
            <div class="row">
                @foreach($catalogs as $catalog)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="thumbnail text-center">
                        <i class="fa fa-file-pdf-o fa-5x text-danger" style="margin: 20px 0;"></i>
                        <div class="caption">
                            <h5>{{ $catalog['display_name'] }}</h5>
                            <p class="text-muted">{{ number_format($catalog['size'] / 1024 / 1024, 1) }} MB</p>
                            <a href="{{ $catalog['url'] }}" class="btn btn-primary btn-block" target="_blank"><i class="fa fa-eye"></i> View</a>
                            <a href="{{ $catalog['url'] }}" class="btn btn-success btn-block" download><i class="fa fa-download"></i> Download</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> No catalogs uploaded yet. <a href="/admin/catalogs">Upload now</a> from admin panel.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
