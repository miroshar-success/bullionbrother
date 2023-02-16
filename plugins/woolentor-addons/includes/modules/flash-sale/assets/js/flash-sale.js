;(function($){
    "use strict";

    // Flash sale countdown
    function FlashSaleCountDownFlip($this, $time) {;
        var customlavel = $this.data('customlavel');
        var labels = [customlavel.daytxt, customlavel.hourtxt, customlavel.minutestxt, customlavel.secondstxt],
            template = function(label, curr, next) {
                return (
                    `<div class="woolentor-time woolentor-${label.toLowerCase()}">
                        <div class="woolentor-time-inner">
                            <span class="woolentor-count woolentor-curr woolentor-top">${curr}</span>
                            <span class="woolentor-count woolentor-next woolentor-top">${next}</span>
                            <span class="woolentor-count woolentor-next woolentor-bottom">${next}</span>
                            <span class="woolentor-count woolentor-curr woolentor-bottom">${curr}</span>
                        </div>
                        <span class="woolentor-label">${label.length < 6 ? label : label.substr(0, 3)}</span>
                    </div>`
                )
            },
            currDate = '00:00:00:00',
            nextDate = '00:00:00:00',
            parser = /([0-9]{2})/gi;
        // Parse countdown string to an object
        function strfobj(str) {
            var parsed = str.match(parser),
            obj = {};
            labels.forEach(function(label, i) {
                obj[label] = parsed[i]
            });
            return obj;
        }
        // Return the time components that diffs
        function diff(obj1, obj2) {
            var diff = [];
            labels.forEach(function(key) {
                if (obj1[key] !== obj2[key]) {
                    diff.push(key);
                }
            });
            return diff;
        }
        // Build the layout
        var initData = strfobj(currDate);
        labels.forEach(function(label, i) {
            $this.append(template(label, initData[label], initData[label]));
        });
        // Starts the countdown);
        $this.countdown($time, function(event) {
            var newDate = event.strftime('%D:%H:%M:%S'),
            data;
            if (newDate !== nextDate) {
                currDate = nextDate;
                nextDate = newDate;
                // Setup the data
                data = {
                    'curr': strfobj(currDate),
                    'next': strfobj(nextDate)
                };
                // Apply the new values to each node that changed
                diff(data.curr, data.next).forEach(function(label) {
                    var selector = '.%s'.replace(/%s/, `woolentor-${label.toLowerCase()}`),
                        $node = $this.find(selector);
                    // Update the node
                    $node.removeClass('woolentor-flip');
                    $node.find('.woolentor-curr').text(data.curr[label]);
                    $node.find('.woolentor-next').text(data.next[label]);
                    // Wait for a repaint to then flip
                    setTimeout(function() {
                        $node.addClass('woolentor-flip');
                    })
                });
            }
        });
    }

    function flashsalecountdownhandler(){
        $('.woolentor-countdown-flip').each(function() {
            var $this = $(this),
                endDate = $this.data('countdown'),
                remainingTimeMillisecond = endDate * 1000, //multiply by 1000 because javascript timestamps are in ms;
                currentTime = new Date();

                endDate = new Date( currentTime.getTime() + remainingTimeMillisecond );

            FlashSaleCountDownFlip($this, endDate);
        });

        $('.woolentor-countdown-default').each(function() {
            var $this = $(this),
                endDate = $this.data('countdown'),
                remainingTimeMillisecond = endDate * 1000, //multiply by 1000 because javascript timestamps are in ms
                customlavel = $(this).data('customlavel'),
                currentTime = new Date(),
                $template = `<div class="woolentor-time woolentor-days"><span class="woolentor-count">%D</span><span class="woolentor-label">${customlavel.daytxt}</span></div><div class="woolentor-time woolentor-hours"><span class="woolentor-count">%H</span><span class="woolentor-label">${customlavel.hourtxt}</span></div><div class="woolentor-time woolentor-mins"><span class="woolentor-count">%M</span><span class="woolentor-label">${customlavel.minutestxt}</span></div><div class="woolentor-time woolentor-secs"><span class="woolentor-count">%S</span><span class="woolentor-label">${customlavel.secondstxt}</span></div>`;

                endDate = new Date( currentTime.getTime() + remainingTimeMillisecond );

            $this.countdown(endDate, function(event) {
                $this.html(event.strftime($template));
            });
        });
    }

    $(document).ready(function(){

        flashsalecountdownhandler();

    });

    // For elementor editor
    var flashsalecountdownhandler_elem = function countdownhandler_elem(){
        flashsalecountdownhandler();
    }
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-flash-sale-product.default', flashsalecountdownhandler_elem);
    });

})(jQuery);