@if (session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info') || $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.Notify !== 'function') {
                return;
            }

            const queue = [];

            @if (session('success'))
                queue.push({ type: 'success', message: @json(session('success')) });
            @endif

            @if (session('error'))
                queue.push({ type: 'error', message: @json(session('error')) });
            @endif

            @if (session('warning'))
                queue.push({ type: 'warning', message: @json(session('warning')) });
            @endif

            @if (session('info'))
                queue.push({ type: 'info', message: @json(session('info')) });
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $errorMessage)
                    queue.push({ type: 'error', message: @json($errorMessage) });
                @endforeach
            @endif

            queue.forEach(function(item) {
                window.Notify(item.message, null, null, item.type);
            });
        });
    </script>
@endif
