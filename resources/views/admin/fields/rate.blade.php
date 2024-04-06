@component($typeForm , get_defined_vars())
    <div data-controller="rate" data-rate-count="{{ $count }}
         data-rate-step={{ $step }}
         data-rate-readonly={{ $readonly }}">
        <div data-rate-value="{{ $attrubutes['haveRated'] }}">
            <input type="hidden"{{ $attrubutes }}>
        </div>
    </div>
@endcomponent
