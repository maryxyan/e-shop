@extends('layouts.admin.app')

@section('content')
    <h1>Batch Upload Products</h1>
    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('admin.products.batch-upload.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="products_file">Select CSV or XLSX file</label>
                    <input type="file" name="products_file" id="products_file" accept=".csv,.xlsx" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
        <div class="col-md-12 mt-4">
            <h3>Uploaded Products Preview</h3>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Image URL</th>
                        <th>Image URL 2</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Description</th>
                        <th>Specificatii Produs</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($previewRows) && count($previewRows))
                        @foreach($previewRows as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row['name'] ?? '' }}</td>
                                <td>{{ $row['price'] ?? '' }}</td>
                                <td>{{ $row['image_url'] ?? '' }}</td>
                                <td>{{ $row['image_url_2'] ?? '' }}</td>
                                <td>{{ $row['category'] ?? '' }}</td>
                                <td>{{ $row['subcategory'] ?? '' }}</td>
                                <td>{{ $row['description'] ?? '' }}</td>
                                <td>{{ $row['specificatii_produs'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center">
                                No preview data available. Upload a file to see the first rows here.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection