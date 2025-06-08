/* running-text.js */
jQuery(document).ready(function($) {
    if (typeof window.ptwInitialized === 'undefined') {
        window.ptwInitialized = true;

        $('.ptw-animation').each(function() {
            const animContainer = $(this);
            const containerId = animContainer.attr('id').replace('-animation', '');
            const container = $('#' + containerId);
            const originalHtml = container.html();

            // Hide original content
            container.hide();

            // Process paragraphs
            const paragraphs = [];
            const tempDiv = $('<div>').html(originalHtml);

            tempDiv.contents().each(function() {
                if (this.nodeType === 3) { // Text node
                    const text = this.nodeValue.trim();
                    if (text) {
                        const formatted = text.replace(/([.!?])\s+([А-ЯA-Z])/g, '$1\n\n$2');
                        paragraphs.push(...formatted.split('\n\n'));
                    }
                } else if (this.tagName === 'P' || this.tagName === 'BR') {
                    paragraphs.push($(this).prop('outerHTML'));
                }
            });

            let currentPara = 0;
            let position = 0;
            let isDeleting = false;

            function typeEffect() {
                const currentText = paragraphs[currentPara] || '';

                if (!isDeleting) {
                    position++;
                    let displayText = currentText.substring(0, position);

                    if (!displayText.startsWith('<')) {
                        displayText = displayText.replace(/([.!?])\s+([А-ЯA-Z])/g, '$1<br><br>$2');
                    }

                    animContainer.html(displayText + '<span class="ptw-cursor"></span>');

                    if (position < currentText.length) {
                        setTimeout(typeEffect, 30 + Math.random() * 50);
                    } else {
                        setTimeout(() => {
                            isDeleting = true;
                            typeEffect();
                        }, 2000);
                    }
                } else {
                    position--;
                    let displayText = currentText.substring(0, position);

                    if (!displayText.startsWith('<')) {
                        displayText = displayText.replace(/([.!?])\s+([А-ЯA-Z])/g, '$1<br><br>$2');
                    }

                    animContainer.html(displayText + '<span class="ptw-cursor"></span>');

                    if (position > 0) {
                        setTimeout(typeEffect, 10);
                    } else {
                        isDeleting = false;
                        currentPara = (currentPara + 1) % paragraphs.length;
                        setTimeout(typeEffect, 500);
                    }
                }
            }

            animContainer.empty();
            typeEffect();
        });
    }
});
