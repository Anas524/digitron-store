@extends('admin.layout')
@section('title', $product->exists ? 'Edit Product' : 'Add Product')

@section('topbar')
  <div class="topbar-title">{{ $product->exists ? 'Edit Product' : 'Add Product' }}</div>
@endsection

@section('content')
<form class="glass form-wrap" method="POST"
      action="{{ $product->exists ? route('admin.products.update',$product) : route('admin.products.store') }}"
      enctype="multipart/form-data">
  @csrf
  @if($product->exists) @method('PUT') @endif

  <div class="form-grid">
    <div>
      <label>Name</label>
      <input name="name" value="{{ old('name',$product->name) }}" required>

      <label>SKU</label>
      <input name="sku" value="{{ old('sku',$product->sku) }}">

      <div class="two">
        <div>
          <label>Price (AED)</label>
          <input name="price" type="number" step="0.01" value="{{ old('price',$product->price) }}" required>
        </div>
        <div>
          <label>Old Price (optional)</label>
          <input name="old_price" type="number" step="0.01" value="{{ old('old_price',$product->old_price) }}">
        </div>
      </div>

      <div class="two">
        <div>
          <label>Tag</label>
          <select name="tag">
            <option value="new" @selected(old('tag',$product->tag)==='new')>New</option>
            <option value="used" @selected(old('tag',$product->tag)==='used')>Used</option>
            <option value="refurbished" @selected(old('tag',$product->tag)==='refurbished')>Refurbished</option>
          </select>
        </div>
        <div>
          <label>Badge (HOT/SALE)</label>
          <input name="badge_text" value="{{ old('badge_text',$product->badge_text) }}">
        </div>
      </div>

      <div class="two">
        <div>
          <label>Rating</label>
          <input name="rating" type="number" step="0.1" value="{{ old('rating',$product->rating) }}">
        </div>
        <div>
          <label>Rating Count</label>
          <input name="rating_count" type="number" value="{{ old('rating_count',$product->rating_count) }}">
        </div>
      </div>

      <label>Short Desc (delivery line)</label>
      <input name="short_desc" value="{{ old('short_desc',$product->short_desc) }}">

      <label>Description</label>
      <textarea name="description" rows="5">{{ old('description',$product->description) }}</textarea>

      <label class="chk">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active',$product->is_active))>
        Active (show on shop)
      </label>

      <button class="btn-primary" type="submit">
        {{ $product->exists ? 'Save Changes' : 'Create Product' }}
      </button>
    </div>

    <div>
      <label>Upload Images (multiple)</label>
      <input type="file" name="images[]" multiple accept="image/*">

      @if($product->exists && $product->images->count())
        <div class="gallery-grid">
          @foreach($product->images as $img)
            <div class="gal-item glass">
              <img src="{{ asset('storage/'.$img->image_path) }}" alt="">
              <div class="gal-actions">
                <form method="POST" action="{{ route('admin.product_images.primary',$img) }}">
                  @csrf
                  <button class="btn-ghost" type="submit">
                    {{ $img->is_primary ? 'Primary ✅' : 'Set Primary' }}
                  </button>
                </form>

                <form method="POST" action="{{ route('admin.product_images.delete',$img) }}"
                      onsubmit="return confirm('Remove this image?')">
                  @csrf @method('DELETE')
                  <button class="btn-danger" type="submit">Remove</button>
                </form>
              </div>
            </div>
          @endforeach
        </div>
      @endif

      <div class="hint">
        Tip: First uploaded image becomes primary automatically. You can change primary anytime.
      </div>
    </div>
  </div>
</form>
@endsection
