@foreach ($sections as $section)
    @if ($section->position === $position)
        @foreach ($section->contents as $content)
            <section class="custom-section ptb-60">
                <div class="container">
                    <div class="section-title mb-4">
                        <h2 class="title">{{ $content->section_name }}</h2>
                    </div>
                    <div class="section-content">
                        {!! $content->content !!}
                    </div>
                </div>
            </section>
        @endforeach
    @endif
@endforeach
