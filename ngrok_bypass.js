    <script>
        // Intercept fetch
        const originalFetch = window.fetch;
        window.fetch = function() {
            let [resource, config] = arguments;
            if(config === undefined) {
                config = {};
            }
            if(config.headers === undefined) {
                config.headers = {};
            }
            if (config.headers instanceof Headers) {
                config.headers.append('ngrok-skip-browser-warning', 'true');
            } else {
                config.headers['ngrok-skip-browser-warning'] = 'true';
            }
            return originalFetch(resource, config);
        };

        // Intercept XMLHttpRequest
        const originalOpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function() {
            this.addEventListener('readystatechange', function() {
                if (this.readyState === 1) { // OPENED
                    this.setRequestHeader('ngrok-skip-browser-warning', 'true');
                }
            });
            originalOpen.apply(this, arguments);
        };
    </script>
