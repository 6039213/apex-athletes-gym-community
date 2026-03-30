UPDATE wp_options
SET option_value = 'http://localhost:8080'
WHERE option_name IN ('home', 'siteurl')
  AND option_value = 'http://localhost/gym_community';

UPDATE wp_posts
SET guid = REPLACE(guid, 'http://localhost/gym_community', 'http://localhost:8080')
WHERE guid LIKE 'http://localhost/gym_community%';

UPDATE wp_posts
SET post_content = REPLACE(post_content, 'http://localhost/gym_community', 'http://localhost:8080')
WHERE post_content LIKE '%http://localhost/gym_community%';

UPDATE wp_posts
SET post_excerpt = REPLACE(post_excerpt, 'http://localhost/gym_community', 'http://localhost:8080')
WHERE post_excerpt LIKE '%http://localhost/gym_community%';

UPDATE wp_users
SET user_url = REPLACE(user_url, 'http://localhost/gym_community', 'http://localhost:8080')
WHERE user_url LIKE 'http://localhost/gym_community%';
