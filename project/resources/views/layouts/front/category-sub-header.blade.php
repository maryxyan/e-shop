<div class="dropdown header-category-dropdown">
    <a href="{{ route('front.category.slug', $category->slug) }}" class="dropdown-toggle" id="cat-{{ $category->slug }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ strtoupper($category->name) }} <i class="fa fa-angle-down"></i>
    </a>
    <ul class="dropdown-menu header-category-dropdown-menu" aria-labelledby="cat-{{ $category->slug }}">
        @foreach($subs as $sub)
            <li><a href="{{ route('front.category.slug', $sub->slug) }}">{{ $sub->name }}</a></li>
        @endforeach
    </ul>
</div>
