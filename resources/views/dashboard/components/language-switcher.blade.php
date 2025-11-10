@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    $currentLocale = LaravelLocalization::getCurrentLocale();
    $supportedLocales = LaravelLocalization::getSupportedLocales();
@endphp

<div class="dropdown">
    <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        @if($currentLocale === 'ar')
            <i class="flag-icon flag-icon-sa me-2"></i>
            العربية
        @else
            <i class="flag-icon flag-icon-us me-2"></i>
            English
        @endif
    </button>
    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
        @foreach($supportedLocales as $localeCode => $properties)
            @if($localeCode !== $currentLocale)
                <li>
                    <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                        @if($localeCode === 'ar')
                            <i class="flag-icon flag-icon-sa me-2"></i>
                            العربية
                        @else
                            <i class="flag-icon flag-icon-us me-2"></i>
                            English
                        @endif
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>

<style>
.flag-icon {
    width: 20px;
    height: 15px;
    background-size: cover;
    display: inline-block;
    border-radius: 2px;
}

.flag-icon-sa {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjEiIGhlaWdodD0iMTUiIHZpZXdCb3g9IjAgMCAyMSAxNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjIxIiBoZWlnaHQ9IjE1IiBmaWxsPSIjMDA2QzM1Ii8+Cjx0ZXh0IHg9IjEwLjUiIHk9IjcuNSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjgiIGZpbGw9IndoaXRlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0iY2VudHJhbCI+2LPYudmI2K/ZitipPC90ZXh0Pgo8L3N2Zz4K');
}

.flag-icon-us {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjEiIGhlaWdodD0iMTUiIHZpZXdCb3g9IjAgMCAyMSAxNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjIxIiBoZWlnaHQ9IjE1IiBmaWxsPSIjQjIyMjM0Ii8+CjxyZWN0IHdpZHRoPSIyMSIgaGVpZ2h0PSIxLjE1IiBmaWxsPSJ3aGl0ZSIvPgo8cmVjdCB5PSIyLjMiIHdpZHRoPSIyMSIgaGVpZ2h0PSIxLjE1IiBmaWxsPSJ3aGl0ZSIvPgo8cmVjdCB5PSI0LjYiIHdpZHRoPSIyMSIgaGVpZ2h0PSIxLjE1IiBmaWxsPSJ3aGl0ZSIvPgo8cmVjdCB5PSI2LjkiIHdpZHRoPSIyMSIgaGVpZ2h0PSIxLjE1IiBmaWxsPSJ3aGl0ZSIvPgo8cmVjdCB5PSI5LjIiIHdpZHRoPSIyMSIgaGVpZ2h0PSIxLjE1IiBmaWxsPSJ3aGl0ZSIvPgo8cmVjdCB5PSIxMS41IiB3aWR0aD0iMjEiIGhlaWdodD0iMS4xNSIgZmlsbD0id2hpdGUiLz4KPHJlY3Qgd2lkdGg9IjguNCIgaGVpZ2h0PSI4LjA3IiBmaWxsPSIjM0MzQjZFIi8+Cjwvc3ZnPgo=');
}
</style>
