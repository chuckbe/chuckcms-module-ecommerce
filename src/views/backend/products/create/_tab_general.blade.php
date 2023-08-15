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
            <label>Titel *</label>
            <input type="text" class="form-control title-input {{ $langKey == ChuckSite::getFeaturedLocale() ? 'featured-title' : '' }}" data-lang="{{ $langKey }}" placeholder="Titel" name="title[{{ $langKey }}]" value="{{ old('title.'.$langKey.'') }}" required>
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
          <label>Meta Titel *</label>
          <input type="text" class="form-control meta-title-input" placeholder="Meta Titel" name="meta_title[{{ $langKey }}]" value="{{ old('meta_title.'.$langKey.'') }}" required>
        </div>

        <div class="form-group form-group-default required">
          <label>Meta Beschrijving</label>
          <textarea class="form-control" name="meta_description[{{ $langKey }}]" placeholder="Meta Beschrijving" cols="30" rows="3">{{ old('meta_description.'.$langKey.'') }}</textarea>
        </div>

        <div class="form-group form-group-default required">
          <label>Meta Keywords (scheiden met komma)</label>
          <textarea class="form-control" name="meta_keywords[{{ $langKey }}]" placeholder="Meta Keywords (scheiden met komma)" cols="30" rows="1">{{ old('meta_keywords.'.$langKey.'') }}</textarea>
        </div>
    </div>
    @endforeach
</div>

<hr>

<div class="form-group row required">
    <label for="slug" class="col-sm-2 col-form-label">Slug *</label>
    <div class="col-sm-10">
        <input type="text" class="form-control product_slug_input" placeholder="slug" id="product_slug" name="slug" value="{{ old('product_slug') }}" required>
    </div>
</div>
<div class="form-group row">
    <label for="upc" class="col-sm-2 col-form-label">UPC</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="UPC Code" name="code[upc]" value="{{ old('code.upc') }}">
    </div>
</div>
<div class="form-group row">
    <label for="ean" class="col-sm-2 col-form-label">EAN</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="EAN Code" name="code[ean]" value="{{ old('code.ean') }}">
        <small>Indien je dit leeglaat zal dit automatisch worden aangemaakt.</small>
    </div>
</div>
<div class="form-group row required">
  <div class="col-sm-6">
    <label class="sr-only" for="">Wordt weergegeven?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_displayed" disabled>
    <label for="is_displayed">
        <input type="checkbox" class="boolean_checkbox_input" id="is_displayed" value="1" name="is_displayed" checked /> Wordt weergegeven?
    </label>
  </div>
  <div class="col-sm-6">
    <label class="sr-only" for="">Mag verkocht worden?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_buyable" disabled>
    <label for="is_buyable">
        <input type="checkbox" class="boolean_checkbox_input" id="is_buyable" value="1" name="is_buyable" checked /> Mag verkocht worden?
    </label>
  </div>
  <div class="col-sm-6 mt-2">
    <label class="sr-only" for="">Is virtueel product?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_download">
    <label for="is_download">
        <input type="checkbox" class="boolean_checkbox_input" id="is_download" value="0" name="is_download" /> Is virtueel product?
    </label>
  </div>
  <div class="col-sm-6 mt-2">
    <label class="sr-only" for="">In aanbieding?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_featured">
    <label for="is_featured">
        <input type="checkbox" class="boolean_checkbox_input" id="is_featured" value="0" name="is_featured" /> In aanbieding?
    </label>
  </div>
</div>
