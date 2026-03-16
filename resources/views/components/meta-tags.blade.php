@php
    use Illuminate\Support\Str;

    $title = $metaTagContent->title ?? ($pageHeading ?? '');
    $cleanTitle = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($title))), 60, '...');

    $metaKeywords = $metaTagContent->meta_keywords ?? '';
    $cleanMetaKeywords = strip_tags($metaKeywords);
    $cleanMetaKeywords = preg_replace('/\s*,\s*/', ',', $cleanMetaKeywords);
    $cleanMetaKeywords = preg_replace('/,+/', ',', $cleanMetaKeywords);
    $cleanMetaKeywords = trim($cleanMetaKeywords, ', ');
    $cleanMetaKeywords = Str::limit($cleanMetaKeywords, 160, '');

    $description = $metaTagContent->meta_description ?? $metaTagContent->description ?? $metaTagContent->content ?? '';
    $cleanDescription = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($description))), 160, '...');
@endphp

@section('pageHeading', $cleanTitle)
@section('metaKeywords', $cleanMetaKeywords)
@section('metaDescription', $cleanDescription)

{{-- OG Meta Tags --}}
@section('og-url', $ogUrl)
@section('og-image', $ogImage)
@section('og-title', $cleanTitle)
@section('og-description', $cleanDescription)
