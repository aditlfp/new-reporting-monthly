(function(global, $) {
    'use strict';

    const STYLE_ID = 'notify-modern-styles';
    const CONTAINER_ID = 'notifications';
    const DEFAULT_TIMEOUT = 5000;
    const VARIANTS = ['success', 'error', 'warning', 'info'];

    function ensureStyles() {
        if (document.getElementById(STYLE_ID)) {
            return;
        }

        const style = document.createElement('style');
        style.id = STYLE_ID;
        style.textContent = `
            #${CONTAINER_ID} {
                position: fixed;
                top: 1rem;
                right: 1rem;
                left: 1rem;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                pointer-events: none;
                align-items: stretch;
            }

            .notify-toast {
                pointer-events: auto;
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                width: 100%;
                padding: 0.875rem 1rem;
                border: 1px solid;
                border-radius: 1rem;
                background: #ffffff;
                box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
                color: #0f172a;
                opacity: 0;
                transform: translateY(-8px);
                transition: opacity 180ms ease, transform 180ms ease;
                cursor: pointer;
            }

            .notify-toast.is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            .notify-toast.is-removing {
                opacity: 0;
                transform: translateY(-8px);
            }

            .notify-icon {
                flex-shrink: 0;
                width: 1.25rem;
                height: 1.25rem;
                margin-top: 0.125rem;
            }

            .notify-body {
                min-width: 0;
                flex: 1;
            }

            .notify-title {
                font-size: 0.925rem;
                font-weight: 600;
                line-height: 1.35;
            }

            .notify-message {
                margin-top: 0.2rem;
                font-size: 0.875rem;
                line-height: 1.5;
                white-space: pre-line;
                word-break: break-word;
            }

            .notify-close {
                flex-shrink: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 2rem;
                height: 2rem;
                margin: -0.25rem -0.375rem -0.25rem 0;
                border: 0;
                border-radius: 9999px;
                background: transparent;
                color: inherit;
                opacity: 0.68;
                cursor: pointer;
                transition: background-color 150ms ease, opacity 150ms ease;
            }

            .notify-close:hover,
            .notify-close:focus-visible {
                opacity: 1;
                background: rgba(15, 23, 42, 0.08);
                outline: none;
            }

            .notify-toast[data-variant="success"] {
                border-color: #86efac;
                background: #f0fdf4;
                color: #166534;
            }

            .notify-toast[data-variant="error"] {
                border-color: #fca5a5;
                background: #fef2f2;
                color: #b91c1c;
            }

            .notify-toast[data-variant="warning"] {
                border-color: #fcd34d;
                background: #fffbeb;
                color: #a16207;
            }

            .notify-toast[data-variant="info"] {
                border-color: #7dd3fc;
                background: #f0f9ff;
                color: #0369a1;
            }

            @media (min-width: 640px) {
                #${CONTAINER_ID} {
                    left: auto;
                    width: min(26rem, calc(100vw - 2rem));
                }
            }
        `;

        document.head.appendChild(style);
    }

    function ensureContainer() {
        let container = document.getElementById(CONTAINER_ID);

        if (!container) {
            container = document.createElement('div');
            container.id = CONTAINER_ID;
            document.body.appendChild(container);
        }

        return $(container);
    }

    function normalizeVariant(style) {
        const normalized = (style || 'warning').toString().toLowerCase();

        if (normalized === 'danger') {
            return 'error';
        }

        return VARIANTS.includes(normalized) ? normalized : 'warning';
    }

    function normalizeArgs(text, callback, closeCallback, style) {
        let message = text;
        let onClick = callback;
        let onClose = closeCallback;
        let variant = style;

        if (typeof text === 'string' && VARIANTS.includes(text.toLowerCase()) && typeof callback === 'string' && typeof style === 'undefined') {
            message = callback;
            onClick = null;
            onClose = closeCallback;
            variant = text;
        } else if (typeof text === 'string' && text.toLowerCase() === 'danger' && typeof callback === 'string' && typeof style === 'undefined') {
            message = callback;
            onClick = null;
            onClose = closeCallback;
            variant = 'error';
        }

        return {
            message: message == null ? '' : String(message),
            onClick: typeof onClick === 'function' ? onClick : null,
            onClose: typeof onClose === 'function' ? onClose : null,
            variant: normalizeVariant(variant)
        };
    }

    function getMeta(variant) {
        const meta = {
            success: {
                title: 'Success',
                icon: '<svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.25 7.25a1 1 0 01-1.415 0l-3-3a1 1 0 111.414-1.42l2.293 2.294 6.543-6.544a1 1 0 011.415 0z" clip-rule="evenodd"></path></svg>'
            },
            error: {
                title: 'Error',
                icon: '<svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293a1 1 0 00-1.414-1.414L10 8.586 7.707 6.293a1 1 0 00-1.414 1.414L8.586 10l-2.293 2.293a1 1 0 101.414 1.414L10 11.414l2.293 2.293a1 1 0 001.414-1.414L11.414 10l2.293-2.293z" clip-rule="evenodd"></path></svg>'
            },
            warning: {
                title: 'Warning',
                icon: '<svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.486 0l6.518 11.591c.75 1.334-.213 2.99-1.742 2.99H3.48c-1.53 0-2.492-1.656-1.742-2.99L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-1 1v3a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
            },
            info: {
                title: 'Info',
                icon: '<svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-8-4a1 1 0 100 2 1 1 0 000-2zm-1 4a1 1 0 000 2v2a1 1 0 102 0v-2a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
            }
        };

        return meta[variant];
    }

    global.Notify = function(text, callback, closeCallback, style) {
        ensureStyles();

        const { message, onClick, onClose, variant } = normalizeArgs(text, callback, closeCallback, style);
        const meta = getMeta(variant);
        const $container = ensureContainer();
        const role = variant === 'error' || variant === 'warning' ? 'alert' : 'status';

        const $toast = $(`
            <div class="notify-toast" data-variant="${variant}" role="${role}" aria-live="polite">
                <div class="notify-icon">${meta.icon}</div>
                <div class="notify-body">
                    <div class="notify-title">${meta.title}</div>
                    <div class="notify-message"></div>
                </div>
                <button type="button" class="notify-close" aria-label="Close notification">
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" width="18" height="18">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `);

        $toast.find('.notify-message').text(message);
        $container.prepend($toast);

        requestAnimationFrame(() => {
            $toast.addClass('is-visible');
        });

        let timeoutId = null;
        let isRemoved = false;

        function removeToast(triggerClose) {
            if (isRemoved) {
                return;
            }

            isRemoved = true;
            window.clearTimeout(timeoutId);
            $toast.addClass('is-removing').removeClass('is-visible');

            window.setTimeout(() => {
                $toast.remove();
                if (triggerClose && onClose) {
                    onClose();
                }
            }, 180);
        }

        function startTimer() {
            window.clearTimeout(timeoutId);
            timeoutId = window.setTimeout(() => removeToast(true), DEFAULT_TIMEOUT);
        }

        startTimer();

        $toast.on('mouseenter', function() {
            window.clearTimeout(timeoutId);
        });

        $toast.on('mouseleave', function() {
            startTimer();
        });

        $toast.on('click', function(event) {
            if ($(event.target).closest('.notify-close').length) {
                return;
            }

            if (onClick) {
                onClick();
            }

            removeToast(true);
        });

        $toast.find('.notify-close').on('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            removeToast(true);
        });

        return $toast;
    };
})(window, window.jQuery);
