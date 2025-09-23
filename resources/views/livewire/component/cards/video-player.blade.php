<div class="w-full h-[300px] lg:h-full"> <!-- parent container, bisa fleksibel atau fixed -->
    <video
        id="{{ $playerId }}"
        class="video-js vjs-default-skin vjs-big-play-centered w-full h-full object-cover rounded-xl overflow-hidden"
        controls
        preload="auto"
        autoplay
        muted
        poster="{{ $poster }}"
        data-setup='{}'>
        <source src="{{ $src }}" type="application/x-mpegURL" />
        Browser Anda tidak mendukung pemutar video.
    </video>
</div>

@push('scripts')
<script>
document.addEventListener("livewire:navigated", () => {
    const playerId = @json($playerId);

    if (window[playerId]) {
        window[playerId].dispose();
    }

    window[playerId] = videojs(playerId, {
        fluid: true,           // video responsive mengikuti container
        autoplay: "muted",
        muted: true,
    });

    // Pastikan video crop sesuai container
    const videoEl = document.getElementById(playerId);
    if(videoEl){
        videoEl.style.objectFit = 'cover';
        videoEl.style.width = '100%';
        videoEl.style.height = '100%';
    }
});
</script>
@endpush
