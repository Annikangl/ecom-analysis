<div class="mb-3">
    <div class="col-12 mb-1">
        <h4 class="text-uppercase">{{ $title }}</h4>
        <p>{{ $subtitle }}</p>
    </div>
    <div class="row row-cols-md-4 mb-2 g-3 g-mb-4">
        @if(isset($items))
            @foreach($items as $item)
                <div class="col">
                    <div class="p-4 bg-white rounded shadow-sm h-100">
                        <small class="text-muted d-block mb-1">{{ $item['title'] }}</small>
                        <p class="h3 text-black fw-light">
                            {{ number_format($item['value'], 0, '.', ' ')}}
                        </p>

                        @if (isset($item['statValue']))
                            <small class="small text-success">
                                + {{ $item['statValue'] }} за последние {{ $item['statDays'] }} дн.
                            </small>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div>
