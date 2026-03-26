/**
 * Gym Community Plugin JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Handle registration form submission
        $('.gym-registration-form form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $message = $form.find('.form-message');
            var activityId = $form.find('input[name="activity_id"]').val();
            var userName = $form.find('input[name="user_name"]').val();
            var userEmail = $form.find('input[name="user_email"]').val();
            var userPhone = $form.find('input[name="user_phone"]').val();
            
            // Clear previous messages
            $message.removeClass('success error').hide().text('');
            
            // Add loading state
            $form.addClass('loading');
            
            // AJAX request
            $.ajax({
                url: gymCommunity.ajaxurl,
                type: 'POST',
                data: {
                    action: 'gym_register_activity',
                    nonce: gymCommunity.nonce,
                    activity_id: activityId,
                    user_name: userName,
                    user_email: userEmail,
                    user_phone: userPhone
                },
                success: function(response) {
                    $form.removeClass('loading');
                    
                    if (response.success) {
                        $message.addClass('success').text(response.data.message).show();
                        $form[0].reset();
                        
                        // Scroll to message
                        $('html, body').animate({
                            scrollTop: $message.offset().top - 100
                        }, 500);
                    } else {
                        $message.addClass('error').text(response.data.message).show();
                    }
                },
                error: function() {
                    $form.removeClass('loading');
                    $message.addClass('error').text('An error occurred. Please try again.').show();
                }
            });
        });
        
        // Smooth scroll for registration links
        $('a[href*="#registration"]').on('click', function(e) {
            var target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });
        
        // Star rating hover effect
        $('.stars .star').hover(
            function() {
                $(this).addClass('hover');
                $(this).prevAll('.star').addClass('hover');
            },
            function() {
                $('.star').removeClass('hover');
            }
        );
        
        // Activity card animations
        $('.gym-activity-card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });
        
        // Review card animations
        $('.gym-review-card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });
        
    });

})(jQuery);
