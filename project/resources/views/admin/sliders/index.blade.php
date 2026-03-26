@extends('layouts.admin.app')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Slider Images Management</h3>
            </div>
            <div class="box-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <!-- Upload Form -->
                <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="form-inline mb-3">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <button type="submit" class="btn btn-primary">Upload Image</button>
                    </div>
                </form>
                
                <!-- Images List - Drag to Reorder -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-sliders">
                        @forelse($sliders as $slider)
                        <tr data-id="{{ $slider->id }}">
                            <td>
                                <img src="{{ Storage::url($slider->image_path) }}" style="max-width: 100px; max-height: 60px;">
                                <br><small>{{ $slider->image_path }}</small>
                            </td>
                            <td>{{ $slider->order }}</td>
                            <td>
                                <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Delete?')"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">No images. Upload first!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if($sliders->count() > 1)
                <button id="save-order" class="btn btn-success" disabled>Save Order</button>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(function() {
    $("#sortable-sliders").sortable({
        items: 'tr[data-id]',
        placeholder: 'ui-state-highlight',
        update: function(event, ui) {
            $('#save-order').prop('disabled', false);
        }
    });
    
    $('#save-order').click(function() {
        var order = [];
        $('#sortable-sliders tr[data-id]').each(function() {
            order.push($(this).data('id'));
        });
        
        $.post('{{ route("admin.sliders.order") }}', {order: order}, function(data) {
            location.reload();
        }).fail(function() {
            alert('Save failed');
        });
    });
});
</script>
@endsection

