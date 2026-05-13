@extends('admin.layout')

@section('title', 'Add New Product')

@section('content')
<div class="page-header">
    <h2>Add New Product</h2>
</div>

<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g., Espresso Coffee" required>
        </div>

        <div class="form-group">
            <label for="category">Category *</label>
            <select id="category" name="category" required>
                <option value="">Select a category</option>
                <option value="Coffee" {{ old('category') == 'Coffee' ? 'selected' : '' }}>Coffee</option>
                <option value="Cakes" {{ old('category') == 'Cakes' ? 'selected' : '' }}>Cakes</option>
                <option value="Pastry" {{ old('category') == 'Pastry' ? 'selected' : '' }}>Pastry</option>
                <option value="Drinks" {{ old('category') == 'Drinks' ? 'selected' : '' }}>Drinks</option>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Price (PHP) *</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" value="{{ old('price') }}" placeholder="e.g., 50" required>
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" placeholder="Write a nice description of your product..." required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
            <small style="color: #999; display: block; margin-top: 0.5rem;">
                Supported: JPG, PNG, GIF (Max 2MB)
            </small>
            <div id="preview-container" style="margin-top: 1rem; display: none;">
                <img id="preview-image" src="" alt="Preview" class="image-preview">
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Create Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) {
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview-image').src = e.target.result;
        document.getElementById('preview-container').style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
