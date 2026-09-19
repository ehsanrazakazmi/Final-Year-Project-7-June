<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
<script >
    // Gloabl Chatify variables from PHP to JS
    window.chatify = {
        name: "{{ config('chatify.name') }}",
        sounds: {!! json_encode(config('chatify.sounds')) !!},
        allowedImages: {!! json_encode(config('chatify.attachments.allowed_images')) !!},
        allowedFiles: {!! json_encode(config('chatify.attachments.allowed_files')) !!},
        maxUploadSize: {{ Chatify::getMaxUploadSize() }},
        {{-- Only the client-safe parts. Serialising the whole
             config('chatify.pusher') published PUSHER_APP_SECRET and the app id
             into the page source, where any signed-in user could read them and
             publish to any channel on the Pusher app. The browser needs the key
             and cluster; authorisation happens server-side at
             pusherAuthEndpoint. --}}
        pusher: {!! json_encode([
            'key' => config('chatify.pusher.key'),
            'options' => [
                'cluster' => config('chatify.pusher.options.cluster'),
                'encrypted' => config('chatify.pusher.options.encrypted', true),
            ],
        ]) !!},
        pusherAuthEndpoint: '{{route("pusher.auth")}}'
    };
    window.chatify.allAllowedExtensions = chatify.allowedImages.concat(chatify.allowedFiles);
</script>
<script src="{{ asset('js/chatify/utils.js') }}"></script>
<script src="{{ asset('js/chatify/code.js') }}"></script>
