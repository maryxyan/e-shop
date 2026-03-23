@extends('layouts.admin.app')

@section('css')
<style>
.table-responsive { border: 1px solid #ddd; border-radius: 4px; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Catalogs - Upload PDFs</h3>
            </div>
            <!-- Upload Form -->
            <div class="box-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="{{ route('admin.catalogs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Catalog Name:</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter catalog name">
                    </div>
                    <div class="form-group">
                        <label for="pdf">Upload PDF:</label>
                        <input type="file" name="pdf" class="form-control" accept=".pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>

        <!-- PDFs List -->
        @if(!empty($catalogs))
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Uploaded Catalogs</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($catalogs as $catalog)
                    <tr>
                        <td><a href="{{ $catalog['url'] }}" target="_blank">{{ $catalog['name'] }}</a></td>
                        <td>{{ number_format($catalog['size'] / 1024, 1) }} KB</td>
                        <td>
                            <a href="{{ $catalog['url'] }}" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('admin.catalogs.destroy', $catalog['name']) }}" class="btn btn-xs btn-danger" onclick="return confirm('Delete?')"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
