<div class="menuArea row mx-0 mb-2">
    <div class="container">
        <nav>
            <ul class="nav nav-pills flex-nowrap overflow-auto" id="navigationTab" role="tablist">
                @php $c = 0; @endphp
                @foreach (ChuckCollection::all()->sortBy('json.order') as $collection)
                @if($collection->json['is_pos_available'] == true)
                    <li class="nav-item mr-2 mr-md-3 mb-1">
                        <a class="nav-link{{ $c == 0 ? ' active' : '' }} px-2 py-1" id="navigationCategory{{$collection->id}}Tab" href="#category{{$collection->id}}Tab" role="tab" data-toggle="tab" aria-controls="category{{$collection->id}}Tab" aria-selected="true">{{ $collection->name }}</a>
                    </li>
                @php $c++; @endphp
                @endif
                @endforeach
            </ul>
        </nav>
    </div>
</div>
