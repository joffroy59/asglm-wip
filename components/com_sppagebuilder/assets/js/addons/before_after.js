(() => {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () { 
        const wrapperElements = document.querySelectorAll('.sppb-before-after-wrapper');

        wrapperElements.forEach((wrapperElement) => {
            const beforeImageElement = wrapperElement.querySelector('.sppb-image-before');
            const separatorElement = wrapperElement.querySelector('.sppb-before-after-separator');
            const beforeTitleElement = wrapperElement.querySelector('.sppb-before-title');
            const afterTitleElement = wrapperElement.querySelector('.sppb-after-title');

            let active = false;

            separatorElement.addEventListener('mousedown', function () {
                active = true;
            });

            document.body.addEventListener('mouseup', function () {
                active = false;
            });

            document.body.addEventListener('mouseleave', function () {
                active = false;
            });

            document.body.addEventListener('mousemove', function (event) {
                if (!active || !beforeImageElement || !wrapperElement || !separatorElement) {
                    return;
                }

                const isInHorizontalOrientation = separatorElement.classList.contains('sppb-separator-horizontal');

                const beforeImageElementRect = beforeImageElement.getBoundingClientRect();
                const wrapperElementReact = wrapperElement.getBoundingClientRect();

                if (isInHorizontalOrientation) {
                    if (event.clientX > wrapperElementReact.left && event.clientX < wrapperElementReact.right) {
                        const calculatedLeft = event.clientX - beforeImageElementRect.left;

                        // separator position
                        beforeImageElement.style.width = `${calculatedLeft}px`;
                        separatorElement.style.left = `${calculatedLeft}px`;

                        // before title visibility
                        if (!beforeTitleElement) {
                            return;
                        }

                        const beforeTitleElementRect = beforeTitleElement.getBoundingClientRect();

                        if (calculatedLeft <= (beforeTitleElementRect.right - beforeImageElementRect.left)) {
                            beforeTitleElement.style.opacity = 0;
                        } else {
                            beforeTitleElement.style.opacity = 1;
                        }

                        // after title visibility
                        if (!afterTitleElement) {
                            return;
                        }

                        const afterTitleElementRect = afterTitleElement.getBoundingClientRect();

                        if (calculatedLeft >= (afterTitleElementRect.left - beforeImageElementRect.left)) {
                            afterTitleElement.style.opacity = 0;
                        } else {
                            afterTitleElement.style.opacity = 1;
                        }
                    }

                    return;
                }
                
                if (event.clientY > wrapperElementReact.top && event.clientY < wrapperElementReact.bottom) {
                    const calculatedTop = event.clientY - beforeImageElementRect.top;

                    // separator position
                    beforeImageElement.style.height = `${calculatedTop}px`;
                    separatorElement.style.top = `${calculatedTop}px`;

                    // before title visibility
                    if (!beforeTitleElement) {
                        return;
                    }

                    const beforeTitleElementRect = beforeTitleElement.getBoundingClientRect();

                    if (calculatedTop <= (beforeTitleElementRect.bottom - beforeImageElementRect.top)) {
                        beforeTitleElement.style.opacity = 0;
                    } else {
                        beforeTitleElement.style.opacity = 1;
                    }

                    // after title visibility
                    if (!afterTitleElement) {
                        return;
                    }

                    const afterTitleElementRect = afterTitleElement.getBoundingClientRect();

                    if (calculatedTop >= (afterTitleElementRect.top - beforeImageElementRect.top)) {
                        afterTitleElement.style.opacity = 0;
                    } else {
                        afterTitleElement.style.opacity = 1;
                    }
                }
            });
        })
    });
})();