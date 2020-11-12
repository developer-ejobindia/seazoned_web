$(function () {
    $('.timepicker a').click(function(e){
        e.preventDefault();
    });
    $(document).mouseup(function (e)
    {
        var container = $(".custom-popover");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.hide();
        }
    });


    $('.timepicker input, .timepicker .input-group-addon').click(function () {
        if ($('.custom-popover').length > 0) {
            $('.custom-popover').remove();
        }
        var id = $(this).closest('.timepicker').children('input').attr('id');
        that = $(this).closest('.timepicker');

        var distance = parseInt($('#' + id).offset().top) - parseInt($(window).scrollTop());
        //alert($(window).scrollTop());

        if ($('#popover_' + id).length == 0 || $('#popover_' + id).length == 'undefined') {
            var hour = '';
            var min = '';
            var period = '';
            if ($('#' + id).val() != '') {
                var inputVal = $('#' + id).val();
                var splitValue = inputVal.split(' ');
                var time = splitValue[0];
                period = splitValue[1];
                var splitTime = time.split(':');
                hour = splitTime[0];
                min = splitTime[1];
            } else {
                var d = new Date(); // for now
                hour = d.getHours(); // => 9
                if (hour > 12) {
                    hour = hour - 12;
                    if (hour < 10) {
                        hour = '0' + hour;
                    }
                    period = 'PM';
                } else {
                    hour = hour;
                    period = 'AM';
                }
                min = d.getMinutes();
                if (min < 10) {
                    min = '0' + min;
                }
                $('#' + id).val(hour + ':' + min + ' ' + period);
            }
            $('<div class="custom-popover" style="top: 45px" data-inputid="' + id + '" id="popover_' + id + '" class="text-center"><table class="table-condensed"><tbody><tr><td><a href="javascript:void(0);" class="btn" onclick="incrementHours();"><i class="fas fa-chevron-up"></i></a></td><td class="separator"></td><td><a href="javascript:void(0);" class="btn" onclick="incrementMinutes();"><i class="fas fa-chevron-up"></i></a></td><td class="separator"></td></tr><tr><td><span class="timepicker-showHours">' + hour + '</span></td><td class="separator">:</td><td><span class="timepicker-showMinutes">' + min + '</span></td><td><span class="btn btn-primary btn-togglePeriod" onclick="togglePeriod($(this).text());">' + period + '</span></td></tr><tr><td><a href="javascript:void(0);" class="btn" onclick="decrementHours();"><i class="fas fa-chevron-down"></i></a></td><td class="separator"></td><td><a href="javascript:void(0);" class="btn" onclick="decrementMinutes();"><i class="fas fa-chevron-down"></i></a></td><td class="separator"></td></tr></tbody></table></div>').insertAfter(that);
        }
    });
});

function incrementHours() {
    var currentHour = $.trim($('.timepicker-showHours').text());
    var incrementHour = parseInt(currentHour) + 1;
    if (incrementHour <= 12) {
        if (incrementHour < 10) {
            incrementHour = '0' + incrementHour;
        }
        $('.timepicker-showHours').text(incrementHour);
    }

    var customPopoverId = $('.custom-popover').attr('data-inputid');
    updateTime(customPopoverId);
}

function decrementHours() {
    var currentHour = $.trim($('.timepicker-showHours').text());
    var decrementHour = parseInt(currentHour) - 1;
    if (decrementHour >= 0) {
        if (decrementHour < 10) {
            decrementHour = '0' + decrementHour;
        }
        $('.timepicker-showHours').text(decrementHour);
    }

    var customPopoverId = $('.custom-popover').attr('data-inputid');
    updateTime(customPopoverId);
}

function incrementMinutes() {
    var currentMinute = $.trim($('.timepicker-showMinutes').text());
    var incrementMinute = parseInt(currentMinute) + 1;
    if (incrementMinute <= 60) {
        if (incrementMinute < 10) {
            incrementMinute = '0' + incrementMinute;
        }
        $('.timepicker-showMinutes').text(incrementMinute);
    }

    var customPopoverId = $('.custom-popover').attr('data-inputid');
    updateTime(customPopoverId);
}

function decrementMinutes() {
    var currentMinute = $.trim($('.timepicker-showMinutes').text());
    var decrementMinute = parseInt(currentMinute) - 1;
    if (decrementMinute >= 0) {
        if (decrementMinute < 10) {
            decrementMinute = '0' + decrementMinute;
        }
        $('.timepicker-showMinutes').text(decrementMinute);
    }

    var customPopoverId = $('.custom-popover').attr('data-inputid');
    updateTime(customPopoverId);
}

function togglePeriod(currentPeriod) {
    if (currentPeriod == 'PM') {
        currentPeriod = 'AM';
    } else {
        currentPeriod = 'PM';
    }
    $('.btn-togglePeriod').text(currentPeriod);

    var customPopoverId = $('.custom-popover').attr('data-inputid');
    updateTime(customPopoverId);
}

function updateTime(inputId) {
    var makeTime = $('.timepicker-showHours').text() + ':' + $('.timepicker-showMinutes').text() + ' ' + $('.btn-togglePeriod').text();
    $('#' + inputId).val(makeTime);
}