/**
 * Gym Community Plugin Admin JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Rating preview in meta box
        $('#gym_review_rating').on('change', function() {
            var rating = $(this).val();
            var stars = '';
            
            for (var i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '★';
                } else {
                    stars += '☆';
                }
            }
            
            var preview = '<span style="color: #f39c12; font-size: 16px;">' + stars + '</span>';
            $(this).next('span').remove();
            $(this).after(' ' + preview);
        });
        
        // Confirm delete for registrations
        $('.wp-list-table a[href*="action=delete"]').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this registration?')) {
                e.preventDefault();
            }
        });
        
        // Auto-save reminder for activity details
        var activityFormChanged = false;
        $('#post').on('change', 'input, select, textarea', function() {
            activityFormChanged = true;
        });
        
        $(window).on('beforeunload', function() {
            if (activityFormChanged) {
                return 'You have unsaved changes. Are you sure you want to leave?';
            }
        });
        
        $('#publish, #save-post').on('click', function() {
            activityFormChanged = false;
        });
        
    });

})(jQuery);
