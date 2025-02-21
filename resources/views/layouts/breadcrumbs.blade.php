<div class="d-sm-flex align-items-center justify-content-between mb-5">
    <h1 class="h3 mb-0 text-gray-800">{{ $title}}</h1>
    <ol class="breadcrumb text-sm">
        @foreach ($breadcrumbs as $breadcrumb)
        @if ($breadcrumb['url'])
            <li class="breadcrumb-item">
                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
            </li>
        @else
            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['label'] }}</li>
        @endif
    @endforeach
    </ol>
</div>
