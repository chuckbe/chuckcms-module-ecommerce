<div class="my-3">
  <ul class="nav nav-tabs justify-content-start" role="tablist">
    @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
    <li class="nav-item" role="presentation">
      <a href="#" class="nav-link{{ $loop->iteration == 1 ? ' active' : '' }}" data-toggle="tab" data-target="#tab_product_{{ $langKey }}"><span>{{ $langValue['name'] }} ({{ $langValue['native'] }})</span></a>
    </li>
    @endforeach
  </ul>
</div>
<!-- Tab panes -->
<div class="tab-content">

  @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
  <div class="tab-pane fade show @if($loop->iteration == 1) active @endif" id="tab_product_{{ $langKey }}">
        <div class="form-group form-group-default required">
          <label>Titel</label>
          <input type="text" class="form-control" placeholder="Titel" name="title[{{ $langKey }}]" value="{{ old('title.'.$langKey.'') }}" required>
        </div>
        
        <div class="form-group">
          <label>Korte Beschrijving</label>
          <div class="summernote-wrapper">
          <textarea name="description[short][{{ $langKey }}]" class="summernote-text-editor" placeholder="Korte Beschrijving">{!! old('description.short.'.$langKey.'') !!}</textarea>
          </div>
        </div>
        
        <div class="form-group">
          <label>Lange Beschrijving</label>
          <div class="summernote-wrapper">
          <textarea name="description[long][{{ $langKey }}]" class="summernote-text-editor" placeholder="Lange Beschrijving">{!! old('description.long.'.$langKey.'') !!}</textarea>
          </div>
        </div>
      
        <div class="form-group form-group-default required">
          <label>Meta Titel</label>
          <input type="text" class="form-control" placeholder="Meta Titel" name="meta_title[{{ $langKey }}]" value="{{ old('meta_title.'.$langKey.'') }}" required>
        </div>
      
        <div class="form-group form-group-default required">
          <label>Meta Beschrijving</label>
          <textarea class="form-control" name="meta_description[{{ $langKey }}]" placeholder="Meta Beschrijving" cols="30" rows="10" style="height:80px" required>{{ old('meta_description.'.$langKey.'') }}</textarea>
        </div>
      
        <div class="form-group form-group-default required">
          <label>Meta Keywords (scheiden met komma)</label>
          <textarea class="form-control" name="meta_keywords[{{ $langKey }}]" placeholder="Meta Keywords (scheiden met komma)" cols="30" rows="10" style="height:80px" required>{{ old('meta_keywords.'.$langKey.'') }}</textarea>
        </div>
      

  </div>
  @endforeach

</div>