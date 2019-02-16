;(function(window) {
    'use strict';
    if (!window.MutationObserver) {
        return;
    }

    var observer = new MutationObserver(function(mutationsList) {
        mutationsList.forEach(function(mutation) {
            if (mutation.type !== 'attributes' || mutation.attributeName !== 'class') {
                return;
            }

            var exceeded = mutation.target.classList.contains('wp-smush-exceed-limit');
            if (exceeded) {
                document.querySelector('.wp-smush-all').click()
            }
        });
    });

    observer.observe(
        document.querySelector('.wp-smush-bulk-progress-bar-wrapper'),
        { attributes: true }
        );

})(window);
