@foreach($reviews as $review)
<div class="d-flex mb-4">
    <div class="me-3">
        @php
            $profile_photo = App\Models\User::find($review->user_id)?->profile_photo ?? ''; // Use safe navigation
        @endphp

        <img src="{{ isImage('profile_photos', $profile_photo) }}" alt="Customer Image" class="review-image">
    </div>
    <div class="review-details">
        <h5>{{ $review->name }}</h5>
        <p>Review Points: <span class="review-rating">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span></p>
        <p>{{ $review->review }}</p>
    </div>
</div>
@endforeach
