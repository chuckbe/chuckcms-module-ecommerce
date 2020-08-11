<div class="row">
  <div class="col-sm-12">
    <div class="form-group required">
      <label for="featured_image">Hoofdafbeelding</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="featured_image_input" data-preview="featured_image_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload afbeelding
          </a>
        </span>
        <input id="featured_image_input" name="featured_image" class="img_lfm_input form-control" accept="image/x-png" type="text" value="{{ $product->json['images']['image0']['url'] }}" required>
      </div>
      <img id="featured_image_holder" src="{{ $product->json['images']['image0']['url'] == null ? '' : ChuckSite::getSite('domain') . $product->json['images']['image0']['url'] }}" style="margin-top:15px;max-height:100px;">
    </div>
  </div>
</div>
@if(array_key_exists('image1', $product->json['images']))
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Afbeelding</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="image_input" data-preview="image_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload afbeelding
          </a>
        </span>
        <input id="image_input" name="image[]" class="img_lfm_input form-control" accept="image/x-png" type="text" value="{{ $product->json['images']['image1']['url'] }}">
      </div>
      <img id="image_holder" src="{{ $product->json['images']['image1']['url'] == null ? '' : ChuckSite::getSite('domain') . $product->json['images']['image1']['url'] }}" style="margin-top:15px;max-height:100px;">
    </div>
  </div>
</div>
@else
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Afbeelding</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="image_input" data-preview="image_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload afbeelding
          </a>
        </span>
        <input id="image_input" name="image[]" class="img_lfm_input form-control" accept="image/x-png" type="text" value="">
      </div>
      <img id="image_holder" src="" style="margin-top:15px;max-height:100px;">
    </div>
  </div>
</div>
@endif


@if(array_key_exists('image2', $product->json['images']))
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Afbeelding</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="image1_input" data-preview="image1_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload afbeelding
          </a>
        </span>
        <input id="image1_input" name="image[]" class="img_lfm_input form-control" accept="image/x-png" type="text" value="{{ $product->json['images']['image2']['url'] }}">
      </div>
      <img id="image1_holder" src="{{ $product->json['images']['image2']['url'] == null ? '' : ChuckSite::getSite('domain') . $product->json['images']['image2']['url'] }}" style="margin-top:15px;max-height:100px;">
    </div>
  </div>
</div>
@else
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Afbeelding</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="image1_input" data-preview="image1_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload afbeelding
          </a>
        </span>
        <input id="image1_input" name="image[]" class="img_lfm_input form-control" accept="image/x-png" type="text" value="">
      </div>
      <img id="image1_holder" src="" style="margin-top:15px;max-height:100px;">
    </div>
  </div>
</div>
@endif


@if(array_key_exists('image3', $product->json['images']))
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Afbeelding</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="image2_input" data-preview="image2_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload afbeelding
          </a>
        </span>
        <input id="image2_input" name="image[]" class="img_lfm_input form-control" accept="image/x-png" type="text" value="{{ $product->json['images']['image3']['url'] }}">
      </div>
      <img id="image2_holder" src="{{ $product->json['images']['image3']['url'] == null ? '' : ChuckSite::getSite('domain') . $product->json['images']['image3']['url'] }}" style="margin-top:15px;max-height:100px;">
    </div>
  </div>
</div>
@else
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Afbeelding</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="image2_input" data-preview="image2_holder" class="btn btn-primary img_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload afbeelding
          </a>
        </span>
        <input id="image2_input" name="image[]" class="img_lfm_input form-control" accept="image/x-png" type="text" value="">
      </div>
      <img id="image2_holder" src="" style="margin-top:15px;max-height:100px;">
    </div>
  </div>
</div>
@endif