(() => {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var DELAY_TIME = 1000;

        function parseDataAttribute(dataAttribute) {
            // Split the string by ';' and create an array of key-value pairs
            var keyValuePairs = dataAttribute.split(';').map(pair => pair.trim());

            // Create an empty object to store the parsed values
            var parsedData = {};

            // Iterate over the key-value pairs and populate the parsedData object
            keyValuePairs.forEach(pair => {
                var [key, value] = pair.split(':').map(item => item.trim());
                if (key && value) {
                    parsedData[key] = value;
                }
            });
            
            return parsedData;
        }
        
        function getRightPosition({ markerRect, popoverRect, containerRect, gap }) {
            var left = (markerRect.left - containerRect.left) + markerRect.width + gap;
            var top = (markerRect.top - containerRect.top) + (markerRect.height / 2) - (popoverRect.height / 2);
            return { left, top };
        }
        function getLeftPosition({ markerRect, popoverRect, containerRect, gap }) {
            var left = (markerRect.left - containerRect.left) - popoverRect.width - gap;
            var top = (markerRect.top - containerRect.top) + (markerRect.height / 2) - (popoverRect.height / 2);
            return { left, top };
        }
        function getBottomPosition({ markerRect, popoverRect, containerRect, gap }) {
            var left = (markerRect.left - containerRect.left) + (markerRect.width / 2) - (popoverRect.width / 2);
            var top = (markerRect.top - containerRect.top) + markerRect.height + gap;
            return { left, top };
        }
        function getTopPosition({ markerRect, popoverRect, containerRect, gap }) {
            var left = (markerRect.left - containerRect.left) + (markerRect.width / 2) - (popoverRect.width / 2);
            var top = (markerRect.top - containerRect.top) - popoverRect.height - gap;
            return { left, top };
        }
        function adjustOverflowPosition({ left, top, popoverRect, containerRect, gap }) {
            var viewPortHeight = window.innerHeight || document.documentElement.clientHeight;
            var viewPortWidth = window.innerWidth || document.documentElement.clientWidth;
            var adjustedTop = top;
            var adjustedLeft = left;

            var originalTop = containerRect.top + top;
            var isVerticallyOutOfViewPort = originalTop + popoverRect.height + gap > viewPortHeight;
            var isAdjusted = false;

            // Overflow top
            if (originalTop < gap) {
                adjustedTop = gap - containerRect.top;
                isAdjusted = true;
                // Overflow bottom
            } else if (isVerticallyOutOfViewPort) {
                var overflowAmount = originalTop + popoverRect.height - viewPortHeight + gap;
                var finalCalculatedTop = Math.floor(originalTop - overflowAmount);
                adjustedTop = (finalCalculatedTop < gap ? gap : finalCalculatedTop) - containerRect.top;
                isAdjusted = true;
            }

            var originalLeft = containerRect.left + left;
            var isHorizontallyOutOfViewPort = originalLeft + popoverRect.width + gap > viewPortWidth;

            // Overflow left
            if (originalLeft < gap) {
                adjustedLeft = gap - containerRect.left;
                isAdjusted = true;
                // Overflow right
            } else if (isHorizontallyOutOfViewPort) {
                var overflowAmount = originalLeft + popoverRect.width - viewPortWidth + gap;
                var finalCalculatedLeft = Math.floor(originalLeft - overflowAmount);
                adjustedLeft = (finalCalculatedLeft < gap ? gap : finalCalculatedLeft) - containerRect.left;
                isAdjusted = true;
            }

            return { top: adjustedTop, left: adjustedLeft, isAdjusted };
        }
        
        function getPosition(containerNode, markerNode, popoverNode) {
            var markerRect = markerNode.getBoundingClientRect();
            var containerRect = containerNode.getBoundingClientRect();
            var popoverRect = popoverNode.getBoundingClientRect();

            var sppbData = popoverNode.getAttribute('sppb-data');
            var parsedData = parseDataAttribute(sppbData);
            var gap = !!parsedData.gap && !Number.isNaN(parsedData.gap) ? Number(parsedData.gap) : 10;
            
            if (parsedData.pos === 'right') {
                var rightPosition = getRightPosition({ markerRect, popoverRect, containerRect, gap });
                var adjustedPosition = adjustOverflowPosition({ top: rightPosition.top, left: rightPosition.left, popoverRect, containerRect, gap });
                return adjustedPosition
            } else if (parsedData.pos === 'left') {
                var rightPosition = getLeftPosition({ markerRect, popoverRect, containerRect, gap });
                var adjustedPosition = adjustOverflowPosition({ top: rightPosition.top, left: rightPosition.left, popoverRect, containerRect, gap });
                return adjustedPosition
            } else if (parsedData.pos === 'bottom') {
                var rightPosition = getBottomPosition({ markerRect, popoverRect, containerRect, gap });
                var adjustedPosition = adjustOverflowPosition({ top: rightPosition.top, left: rightPosition.left, popoverRect, containerRect, gap });
                return adjustedPosition
            } else if (parsedData.pos === 'top') {
                var rightPosition = getTopPosition({ markerRect, popoverRect, containerRect, gap });
                var adjustedPosition = adjustOverflowPosition({ top: rightPosition.top, left: rightPosition.left, popoverRect, containerRect, gap });
                return adjustedPosition
            } else {
                var markerTopRelativeToContainer = markerRect.top - containerRect.top;
                var markerLeftRelativeToContainer = markerRect.left - containerRect.left;

                // Check right
                var rightPosition = getRightPosition({ markerRect, popoverRect, containerRect, gap });
                var adjustedPosition = adjustOverflowPosition({ top: rightPosition.top, left: rightPosition.left, popoverRect, containerRect, gap });
                if (!adjustedPosition.isAdjusted) {
                    return adjustedPosition;
                }

                // Check left
                var leftPosition = getLeftPosition({ markerRect, popoverRect, containerRect, gap });
                var adjustedPosition = adjustOverflowPosition({ top: leftPosition.top, left: leftPosition.left, popoverRect, containerRect, gap });
                if (!adjustedPosition.isAdjusted) {
                    return adjustedPosition;
                }

                // Check bottom
                var bottomPosition = getBottomPosition({ markerRect, popoverRect, containerRect, gap });
                var adjustedPosition = adjustOverflowPosition({ top: bottomPosition.top, left: bottomPosition.left, popoverRect, containerRect, gap });
                if (!adjustedPosition.isAdjusted) {
                    return adjustedPosition;
                }

                // Check top
                var topPosition = getTopPosition({ markerRect, popoverRect, containerRect, gap });
                var adjustedPosition = adjustOverflowPosition({ top: topPosition.top, left: topPosition.left, popoverRect, containerRect, gap });
                if (!adjustedPosition.isAdjusted) {
                    return adjustedPosition;
                }

                return adjustOverflowPosition({
                    top: markerTopRelativeToContainer,
                    left: markerLeftRelativeToContainer,
                    popoverRect,
                    containerRect,
                    gap
                });
            }
        }

        var bodyListeners = [];

        function bodyClickHandler(popoverContent, markerNode) {
            return function () {
                closePopover(popoverContent, markerNode);
            }
        }

        function closePopover(popoverContent, markerNode) {
            if (markerNode) {
                markerNode.classList.remove('active');
            }
            popoverContent.classList.remove('sppb-open');
            document.body.removeEventListener('click', bodyClickHandler(popoverContent, markerNode));
        }

        function resetBodyListeners() {
            for (const listener of bodyListeners) {
                listener();
            }
            bodyListeners.length = 0;
        }

        var popoverAddonElements = document.querySelectorAll('.sppb-addon-popover')

        popoverAddonElements.forEach(popoverElement => {
            var markers = popoverElement.querySelectorAll('#sppb-popover-marker');
            var popoverContents = popoverElement.querySelectorAll('#sppb-popover-content');

            for (var i = 0; i < markers.length; i++) {
                ((index) => {
                    var markerNode = markers[index];
                    var markerData = markerNode.getAttribute('sppb-data')
                    var parsedMarkerData = parseDataAttribute(markerData);
                    var containerNode = popoverElement.querySelector('#sppb-popover-inline');
                    if (!containerNode) {
                        return;
                    }

                    if (parsedMarkerData.mode === 'hover') {
                        var timeoutId;

                        function handleClose() {
                            timeoutId = setTimeout(() => {
                                popoverContents[index].classList.remove('sppb-open');
                                markerNode.classList.remove('active')
                                popoverContents[index].removeEventListener('mouseenter', clearTimeoutId)
                                popoverContents[index].removeEventListener('mouseleave', handleClose)
                                clearTimeout(timeoutId)
                            }, DELAY_TIME)
                        }

                        function clearTimeoutId() {
                            clearTimeout(timeoutId);
                        }

                        markerNode.addEventListener('mouseenter', hoverHandler(timeoutId));
                        markerNode.addEventListener('mouseleave', () => {
                            handleClose();

                            popoverContents[index].addEventListener('mouseenter', clearTimeoutId)
                            popoverContents[index].addEventListener('mouseleave', handleClose)
                        });
                    } else {
                        markerNode.addEventListener('click', clickHandler);
                    }

                    function hoverHandler(timeoutId) {
                        return function () {
                            clearTimeout(timeoutId)

                            var popoverNode = popoverContents[index];

                            if (!!popoverNode) {
                                popoverContents[index].classList.add('sppb-open');
                                markerNode.classList.add('active');
                                var {left, top } = getPosition(containerNode, markerNode, popoverNode)
                                
                                popoverContents[index].style.left = `${left}px`;
                                popoverContents[index].style.top = `${top}px`;
                            }
                        }
                    }

                    function clickHandler(event) {
                        event.stopPropagation();

                        var popoverNode = popoverContents[index];
                        
                        if (!!markerNode && !!popoverNode) {
                            if (popoverContents[index].classList.contains('sppb-open')) {
                                closePopover(popoverContents[index], markerNode);
                            } else {
                                resetBodyListeners()
                                document.body.addEventListener('click', bodyClickHandler(popoverContents[index], markerNode));
                                bodyListeners.push(bodyClickHandler(popoverContents[index], markerNode))
                                
                                markerNode.classList.add('active');
                                popoverContents[index].classList.add('sppb-open');
                                var {left, top } = getPosition(containerNode, markerNode, popoverNode)
                                
                                popoverContents[index].style.left = `${left}px`;
                                popoverContents[index].style.top = `${top}px`;
                            }
                        }
                    }
                })(i);
            }
        })
    });
})()
