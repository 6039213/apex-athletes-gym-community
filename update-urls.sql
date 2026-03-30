-- SQL Script: Update URLs from Local to Production
-- 
-- Gebruik dit script in phpMyAdmin na database import
-- Dit vervangt alle localhost:8080 URLs naar je live domein
--
-- INSTRUCTIES:
-- 1. Open phpMyAdmin op live server
-- 2. Selecteer database: st1738846938
-- 3. Klik op "SQL" tab
-- 4. Kopieer en plak deze queries
-- 5. Klik "Go"

-- Update WordPress site URLs
UPDATE wp_options 
SET option_value = 'https://st1738846938.splsites.nl' 
WHERE option_name = 'home' OR option_name = 'siteurl';

-- Update post content (afbeeldingen, links, etc.)
UPDATE wp_posts 
SET post_content = REPLACE(post_content, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

-- Update post GUIDs
UPDATE wp_posts 
SET guid = REPLACE(guid, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

-- Update post meta (custom fields, featured images, etc.)
UPDATE wp_postmeta 
SET meta_value = REPLACE(meta_value, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

-- Update comments (als je comments hebt)
UPDATE wp_comments 
SET comment_content = REPLACE(comment_content, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

-- Update comment meta
UPDATE wp_commentmeta 
SET meta_value = REPLACE(meta_value, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

-- Verificatie: Check of URLs correct zijn
SELECT option_name, option_value 
FROM wp_options 
WHERE option_name IN ('home', 'siteurl');

-- Klaar! Je URLs zijn nu bijgewerkt naar productie.
