<div class="container">
    <div class="menuArea row mx-0 mb-2">
        <nav class="pt-5 pb-3 px-4">
            <ul class="nav nav-pills flex flex-nowrap" id="navigationTab" role="tablist" style="overflow-x: scroll;">
                @foreach (ChuckCollection::all() as $collection)
                    @if($collection->json['is_pos_available'] == true)
                    <li class="nav-item me-2 me-md-3 mb-1" style="height: fit-content;">
                        <button 
                            class="nav-link px-3 py-2{{$loop->index == 0 ? ' active' : ''}}" 
                            id="navigationCategory-{{$collection->id}}-Tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#category-{{$collection->id}}-Tab" 
                            type="button"
                            role="tab" 
                            aria-controls="category-{{$collection->id}}-Tab" 
                            aria-selected="true"
                            style="width: max-content">
                            <small>{{$collection->json['name']}}</small>
                        </button>
                    </li>
                    @endif
                @endforeach
                {{--  <li class="nav-item me-2 me-md-3 mb-1">
                    <a class="nav-link active px-2 py-1" id="navigationCategory55Tab" href="#category55Tab" role="tab" data-toggle="tab" aria-controls="category55Tab" aria-selected="true"><small>donuts</small></a>
                </li>
                <li class="nav-item me-2 me-md-3 mb-1">
                    <a class="nav-link px-2 py-1" id="navigationCategory56Tab" href="#category56Tab" role="tab" data-toggle="tab" aria-controls="category56Tab" aria-selected="true"><small>candy</small></a>
                </li>  --}}
            </ul>
        </nav>
    </div>
</div>