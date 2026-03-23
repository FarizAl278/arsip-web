@php
    use Filament\Support\Enums\Width;
@endphp
<footer
    @class([
        'fi-footer my-3 text-sm text-gray-500 dark:text-gray-400',
        'border-t border-gray-200 dark:border-gray-700 p-2' => $footerPosition === 'sidebar' || $footerPosition === 'sidebar.footer' || $borderTopEnabled === true,
        'fi-sidebar h-auto' => $footerPosition === 'sidebar' || $footerPosition === 'sidebar.footer',
        'mx-auto w-full px-4 md:px-6 lg:px-8' => $footerPosition === 'footer',
        match ($maxContentWidth ??= (filament()->getMaxContentWidth() ?? Width::SevenExtraLarge)) {
            Width::ExtraSmall, 'xs' => 'max-w-xs',
            Width::Small, 'sm' => 'max-w-sm',
            Width::Medium, 'md' => 'max-w-md',
            Width::Large, 'lg' => 'max-w-lg',
            Width::ExtraLarge, 'xl' => 'max-w-xl',
            Width::TwoExtraLarge, '2xl' => 'max-w-2xl',
            Width::ThreeExtraLarge, '3xl' => 'max-w-3xl',
            Width::FourExtraLarge, '4xl' => 'max-w-4xl',
            Width::FiveExtraLarge, '5xl' => 'max-w-5xl',
            Width::SixExtraLarge, '6xl' => 'max-w-6xl',
            Width::SevenExtraLarge, '7xl' => 'max-w-7xl',
            Width::Full, 'full' => 'max-w-full',
            Width::MinContent, 'min' => 'max-w-min',
            Width::MaxContent, 'max' => 'max-w-max',
            Width::FitContent, 'fit' => 'max-w-fit',
            Width::Prose, 'prose' => 'max-w-prose',
            Width::ScreenSmall, 'screen-sm' => 'max-w-screen-sm',
            Width::ScreenMedium, 'screen-md' => 'max-w-screen-md',
            Width::ScreenLarge, 'screen-lg' => 'max-w-screen-lg',
            Width::ScreenExtraLarge, 'screen-xl' => 'max-w-screen-xl',
            Width::ScreenTwoExtraLarge, 'screen-2xl' => 'max-w-screen-2xl',
            default => $maxContentWidth,
        } => $footerPosition === 'footer',
    ])
    style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; text-align: center;"
>
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; width: 100%; text-align: center;">
        <!-- Copyright Section -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap;">
            <span style="display: inline-flex; align-items: center; gap: 8px; padding-block: 8px; ">
                &copy; {{ now()->format('Y') }}
                @if($sentence)
                    @if($isHtmlSentence)
                        <span style="display: inline-flex; align-items: center; gap: 8px; ">{!! $sentence !!}</span>
                    @else
                        {{ $sentence }}
                    @endif
                @else
                    {{ config('filament-easy-footer.app_name') ?? config('app.name') }}
                @endif
            </span>
        </div>

        <!-- GitHub Section -->
        @if($githubEnabled)
            <div style="display: flex; align-items: center; justify-content: center;">
                <livewire:devonab.filament-easy-footer.github-version
                    :show-logo="$showLogo"
                    :show-url="$showUrl"
                />
            </div>
        @endif

        <!-- Logo Section -->
        @if($logoPath)
            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap;">
                @if($logoText)
                    <span>{{ $logoText }}</span>
                @endif
                @if($logoUrl)
                    <a href="{{ $logoUrl }}" style="display: inline-flex; align-items: center;" target="_blank">
                @endif
                        <img
                            src="{{ $logoPath }}"
                            alt="Logo"
                            style="width: auto; height: {{ $logoHeight }}px; object-fit: contain;"
                        >
                @if($logoUrl)
                    </a>
                @endif
            </div>
        @endif

        <!-- Load Time Section -->
        @if($loadTime)
            <div style="display: flex; align-items: center; justify-content: center;">
                <span>{{ $loadTimePrefix ?? '' }} {{ $loadTime }}s</span>
            </div>
        @endif

        <!-- Links Section -->
        @if(count($links) > 0)
            <div style="display: flex; align-items: center; justify-content: center;">
                <ul style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; list-style: none; padding: 0; margin: 0;">
                    @foreach($links as $link)
                        <li>
                            <a href="{{ $link['url'] }}"
                               class="text-primary-600 dark:text-primary-400 hover:text-primary-600 dark:hover:text-primary-300"
                               target="_blank">{{ $link['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</footer>