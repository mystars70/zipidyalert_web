/**
 * iosCheckbox.js
 * Version: 1.0.0
 * Author: Ron Masas
 */
(function($) {
    $.fn.extend({
        iosCheckbox: function() {
            this.destroy = function(){
                $(this).each(function() {
            		$(this).next('.ios-ui-select').remove();
                });
            };
            if ($(this).attr('data-ios-checkbox') === 'true') {
                return;
            }
            $(this).attr('data-ios-checkbox', 'true');
            $(this).each(function() {
                /**
                 * Original checkbox element
                 */
                var org_checkbox = $(this);
                /**
                 * iOS checkbox div
                 */
                var ios_checkbox = jQuery("<div>", {
                    class: 'ios-ui-select'
                }).append(jQuery("<div>", {
                    class: 'inner'
                }));
                
                
                console.log(ios_checkbox.html());
                
                // If the original checkbox is checked, add checked class to the ios checkbox.
                var labelCheckbox = 'Deactive';
                var labelClass = '';
                if (org_checkbox.is(":checked")) {
                    ios_checkbox.addClass("checked");
                    labelCheckbox = 'Active'
                    labelClass = 'active';
                }
                // Hide the original checkbox and print the new one.
                org_checkbox.hide().after(ios_checkbox);
                // Add click event listener to the ios checkbox
                ios_checkbox.click(function() {
                    // Toggel the check state
                    ios_checkbox.toggleClass("checked");
                    labelHtml.toggleClass("active");
                    // Check if the ios checkbox is checked
                    if (ios_checkbox.hasClass("checked")) {
                        // Update state
                        org_checkbox.prop('checked', true);
                        labelHtml.find('.checkbox-label').html('Active');
                    } else {
                        // Update state
                        org_checkbox.prop('checked', false);
                        labelHtml.find('.checkbox-label').html('Deactive');
                    }
                });
                
                var wrapHtml = $(ios_checkbox).wrap('<div class="ios-wrap"></div>');
                
                var labelHtml = $(wrapHtml).parent().prepend(jQuery("<span>", {
                    class: 'checkbox-label ' + labelClass,
                    html: labelCheckbox
                }))
            });
            //$('.ios-ui-select').wrap("<div class='ios-wrap'><span class='checkbox-label'></span></div>")
            return this;
        }
    });
})(jQuery);
