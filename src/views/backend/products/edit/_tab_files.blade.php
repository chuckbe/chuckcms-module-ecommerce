@if(!is_null($product->getJson('files')))
@foreach($product->json['files'] as $file)
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file{{ $loop->iteration }}_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file{{ $loop->iteration }}_input" name="files[]" class="file_lfm_input form-control" type="text" value="{{ $file['url'] }}">
      </div>
    </div>
  </div>
</div>
@endforeach

@if(count($product->json['files']) < 1)
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file1_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file1_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file2_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file2_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file3_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file3_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>
@elseif(count($product->json['files']) < 2)
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file2_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file2_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file3_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file3_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>
@elseif(count($product->json['files']) < 3)
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file3_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file3_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>
@endif

@else

<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file1_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file1_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file2_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file2_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="image">Bijlage</label>
      <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="file3_input" class="btn btn-primary file_lfm_link" style="color:#FFF">
            <i class="fa fa-picture-o"></i> Upload bijlage
          </a>
        </span>
        <input id="file3_input" name="files[]" class="file_lfm_input form-control" type="text" value="">
      </div>
    </div>
  </div>
</div>

@endif