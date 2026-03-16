<div class="tab-pane slide" id="tab4">
    <div class="product-review" data-aos="fade-up">

        @if ($reviews == null || count($reviews) == 0)
            <h5 class="mb-25">{{ __('This space has not received any reviews yet') . '!' }}</h5>
        @else
            <div class="review-progresses p-30 radius-md border mb-40">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-30">
                    <h4 class="mb-0">{{ __('Total Reviews') }} ({{ $reviewCount }})</h4>
                    @if ($reviewCount > 0)
                        <div class="ratings size-md">
                            <div class="rate bg-img"
                                data-bg-img="{{ asset('assets/frontend/images/rate-star-md.png') }}">
                                <div class="rating-icon bg-img" style="width: {{ round($averageRating * 20) }}%"
                                    data-bg-img="{{ asset('assets/frontend/images/rate-star-md.png') }}"></div>
                            </div>
                            <span class="ratings-total">({{ number_format($averageRating, 1) }})</span>
                        </div>
                </div>
        @endif
        @php
            $reviewCounts = $reviews->groupBy('rating')->map(function ($group) {
                return $group->count();
            });
            $sortedReviewCounts = collect($reviewCounts)->sortByDesc('rating')->toArray();
            $totalReviews = $reviews->count();
        @endphp


        @for ($rating = 5; $rating >= 1; $rating--)
            @php
                $count = isset($sortedReviewCounts[$rating]) ? $sortedReviewCounts[$rating] : 0;
            @endphp
            <div class="review-progress mb-10 row align-items-center">
                <div class="col-3 col-sm-2">{{ @$rating }} {{ __('Star') }}</div>
                <div class="progress-line col-6 col-sm-8">
                    <div class="progress">
                        <div class="progress-bar bg-primary"
                            style="width: {{ number_format(($count / $totalReviews) * 100, 2) }}%" role="progressbar"
                            aria-label="Basic example"
                            aria-valuenow="{{ number_format(($count / $totalReviews) * 100, 2) }}" aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
                <div class="col-3 col-sm-2 text-end">{{ number_format(($count / $totalReviews) * 100, 2) }}%</div>
            </div>
        @endfor

    </div>
    <div class="review-box pb-10">
        @foreach ($reviews as $review)
            <div class="review-list mb-30 border radius-md">
                <div class="review-item p-30">
                    <div class="review-header flex-wrap mb-20">
                        <div class="author d-flex align-items-center justify-content-between gap-3">
                            <div class="author-img">
                                <a href="#" target="_self" title="{{ __('Link') }}"
                                    class="lazy-container ratio ratio-1-1 rounded-circle">
                                    @if (empty($review->user->image))
                                        <img data-src="{{ asset('assets/img/profile.jpg') }}" alt="image"
                                            class="lazyload">
                                    @else
                                        <img data-src="{{ asset('assets/img/users/' . $review->user->image) }}"
                                            alt="image" class="lazyload">
                                    @endif

                                </a>
                            </div>
                            <div class="author-info">
                                <h6 class="mb-1">
                                    <a href="#"
                                        title="{{ @$review->user->first_name }} {{ @$review->user->last_name }}">
                                        @if (empty($review->user->first_name) && empty($review->user->last_name))
                                            {{ $review->user->username ?? '' }}
                                        @else
                                            {{ $review->user->first_name ?? '' }} {{ $review->user->last_name ?? '' }}
                                        @endif
                                    </a>
                                </h6>
                                <div class="ratings mb-1">
                                    <div class="rate bg-img"
                                        data-bg-img="{{ asset('assets/frontend/images/rate-star.png') }}">
                                        <div class="rating-icon bg-img"
                                            style="width: {{ round($review->rating * 20) }}%"
                                            data-bg-img="{{ asset('assets/frontend/images/rate-star.png') }}"></div>
                                    </div>
                                    <span class="ratings-total">({{ number_format($review->rating, 1) }})</span>
                                </div>

                                <span class="color-green">
                                    <i class="fas fa-badge-check"></i>
                                    @if ($review->user && $review->user->status === 1)
                                        {{ __('Verified User') }}
                                    @endif
                                </span>

                            </div>
                        </div>
                        <div class="more-info font-sm">
                            <div class="icon-start">
                                @if (@$review->user->city || @$review->user->state || @$review->user->country)
                                    <i class="fal fa-map-marker-alt"></i>
                                    {{ $review->user->city ?? '' }}{{ ($review->user->city && $review->user->state) || ($review->user->city && $review->user->country) ? ',' : '' }}
                                    {{ $review->user->state ?? '' }}{{ $review->user->state && $review->user->country ? ',' : '' }}
                                    {{ $review->user->country ?? '' }}
                                @endif
                            </div>

                            @php
                                $date = date_format($review->created_at, 'F d, Y');
                            @endphp
                            <div class="icon-start">
                                <i class="fal fa-clock"></i>
                                {{ $date }}
                            </div>
                        </div>
                    </div>

                    <p>{{ $review->comment }}</p>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    @auth('web')
        @php
            $bookedSpace = auth()->user()->spaceBooking()->where('space_id', $space->id)->first();
        @endphp
        @if ($bookedSpace)
            <div class="review_form">
                <form action="{{ route('space.review.store', ['id' => $spaceContent->space_id]) }}" method="POST">
                    @csrf
                    <div class="form-group mb-20">
                        <label class="mb-1">{{ __('Rating') . '*' }}</label>
                        <ul class="rating">
                            <li class="review-value review-1" data-ratingVal="1">
                                <span class="fas fa-star"></span>
                            </li>

                            <li class="review-value review-2" data-ratingVal="2">
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                            </li>

                            <li class="review-value review-3" data-ratingVal="3">
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                            </li>

                            <li class="review-value review-4" data-ratingVal="4">
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                            </li>

                            <li class="review-value review-5" data-ratingVal="5">
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                                <span class="fas fa-star"></span>
                            </li>
                        </ul>
                    </div>

                    <input type="hidden" id="rating-id" name="rating">

                    <div class="form-group mt-2">
                        <label>{{ __('Comment') }}</label>
                        <textarea class="form-control" name="comment" placeholder="{{ __('Write your comment here') . '...' }}">{{ old('comment') }}</textarea>
                    </div>

                    <div class="form_button mt-2">
                        <button type="submit" class="btn btn-lg btn-primary radius-sm">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </form>
            </div>
        @endif
    @endauth
</div>
</div>
