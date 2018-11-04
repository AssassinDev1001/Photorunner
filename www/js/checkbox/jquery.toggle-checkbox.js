;(function($) {

    $.fn.toggleCheckbox = function(toggleContents, callback) {

        var TC = {

            isChecked: function(element){

                return $(element).is(':checked');

            },
            getContentIndex: function(element){

                return (TC.isChecked(element)) ? 1 : 0;

            },
            getContent: function(index){

                var container = $('<span class="toggle-checkbox-container"></span>');

                return container.on('click', function(e){

                    var checkbox = $(this).prev();
                    var nextChecked = !TC.getContentIndex(checkbox);
                    var contentIndex = (nextChecked) ? 1 : 0;
                    var toggleContent = TC.getContent(contentIndex);

                    checkbox.after(toggleContent).prop('checked', nextChecked);
                    $(this).remove();
                    TC.fireCallback(e, checkbox);

                })
                .css('cursor', 'pointer')
                .html(toggleContents[index]);

            },
            fireCallback: function(e, checkbox){

                if(typeof(callback) == 'function') {

                    callback(e, checkbox);

                }

            }

        };

        $.each(this, function(key, element){

            var contentIndex = TC.getContentIndex(element);
            var toggleContent = TC.getContent(contentIndex);
            $(element).after(toggleContent)
                .css('display', 'none')
                .on('change', function(e){

                    var className = $(this).next().attr('class');

                    if(className == 'toggle-checkbox-container') {

                        $(this).next().remove();
                        var contentIndex = TC.getContentIndex(this);
                        var toggleContent = TC.getContent(contentIndex);
                        $(this).after(toggleContent);
                        TC.fireCallback(e, $(this));

                    }

            });

        });

    }

})(jQuery);