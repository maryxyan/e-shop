@foreach($subs as $sub)
    <ul class="list-unstyled sidebar-category-sub">
        <li @if(request()->segment(2) == $sub->slug) class="active" @endif>
            <a href="{{ route('front.category.slug', $sub->slug) }}">{{ $sub->name }}</a>
        </li>
    </ul>
@endforeach