;(function(window) {
    'use strict';
    if (!window.MutationObserver) {
        return;
    }

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type !== 'attributes' || mutation.attributeName !== 'class') {
                return;
            }

            var exceeded = mutation.target.classList.contains('wp-smush-exceed-limit');
            if (exceeded) {
                const button = mutation.target.querySelector('.wp-smush-resume-bulk-smush') ?? mutation.target.querySelector('.wp-smush-all');
                
                if (button) {
                    button.click();
                }
            }
        });
    });

    const container = document.querySelector('.wp-smush-bulk-progress-bar-wrapper');
    if (container) {
        observer.observe(container, {attributes: true});
    }
})(window);
