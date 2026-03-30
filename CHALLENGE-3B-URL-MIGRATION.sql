-- ============================================================================
-- Challenge 3b: URL Migration Script
-- Van: http://localhost:8080
-- Naar: https://st1738846938.splsites.nl
-- ============================================================================
--
-- INSTRUCTIES:
-- 1. Upload WordPress bestanden via FTP naar live server
-- 2. Importeer database in phpMyAdmin op live server
-- 3. Selecteer database: st1738846938
-- 4. Ga naar "SQL" tab
-- 5. Kopieer en plak ALLE queries hieronder
-- 6. Klik "Go"
-- 7. Wacht tot alle queries uitgevoerd zijn
--
-- ============================================================================

-- Stap 1: Update WordPress Site URLs (VERPLICHT)
-- Dit zijn de belangrijkste instellingen voor WordPress
UPDATE wp_options 
SET option_value = 'https://st1738846938.splsites.nl' 
WHERE option_name = 'home' OR option_name = 'siteurl';

-- Stap 2: Update Post Content (Links en Afbeeldingen)
-- Vervangt alle localhost URLs in post content (artikelen, pagina's)
UPDATE wp_posts 
SET post_content = REPLACE(post_content, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

-- Stap 3: Update Post Excerpts
-- Vervangt URLs in excerpts (samenvattingen)
UPDATE wp_posts 
SET post_excerpt = REPLACE(post_excerpt, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

-- Stap 4: Update Post GUIDs
-- GUIDs zijn unieke identifiers voor posts
UPDATE wp_posts 
SET guid = REPLACE(guid, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

-- Stap 5: Update Post Meta (Custom Fields, Featured Images)
-- Vervangt URLs in alle post meta data
UPDATE wp_postmeta 
SET meta_value = REPLACE(meta_value, 'http://localhost:8080', 'https://st1738846938.splsites.nl')
WHERE meta_value LIKE '%http://localhost:8080%';

-- Stap 6: Update Comments (als je comments hebt)
-- Vervangt URLs in comment content
UPDATE wp_comments 
SET comment_content = REPLACE(comment_content, 'http://localhost:8080', 'https://st1738846938.splsites.nl')
WHERE comment_content LIKE '%http://localhost:8080%';

-- Stap 7: Update Comment Author URLs
UPDATE wp_comments 
SET comment_author_url = REPLACE(comment_author_url, 'http://localhost:8080', 'https://st1738846938.splsites.nl')
WHERE comment_author_url LIKE '%http://localhost:8080%';

-- Stap 8: Update Comment Meta
UPDATE wp_commentmeta 
SET meta_value = REPLACE(meta_value, 'http://localhost:8080', 'https://st1738846938.splsites.nl')
WHERE meta_value LIKE '%http://localhost:8080%';

-- Stap 9: Update User Meta (Profielen, Avatars)
UPDATE wp_usermeta 
SET meta_value = REPLACE(meta_value, 'http://localhost:8080', 'https://st1738846938.splsites.nl')
WHERE meta_value LIKE '%http://localhost:8080%';

-- Stap 10: Update Term Meta (Taxonomie meta data)
UPDATE wp_termmeta 
SET meta_value = REPLACE(meta_value, 'http://localhost:8080', 'https://st1738846938.splsites.nl')
WHERE meta_value LIKE '%http://localhost:8080%';

-- ============================================================================
-- VERIFICATIE QUERIES
-- Voer deze uit om te controleren of alles correct is
-- ============================================================================

-- Check 1: Controleer site URLs
SELECT option_name, option_value 
FROM wp_options 
WHERE option_name IN ('home', 'siteurl');
-- Verwacht resultaat: Beide moeten https://st1738846938.splsites.nl zijn

-- Check 2: Zoek naar overgebleven localhost URLs in posts
SELECT ID, post_title, post_content 
FROM wp_posts 
WHERE post_content LIKE '%localhost%' 
LIMIT 10;
-- Verwacht resultaat: Geen resultaten (of alleen niet-URL localhost tekst)

-- Check 3: Zoek naar overgebleven localhost URLs in post meta
SELECT post_id, meta_key, meta_value 
FROM wp_postmeta 
WHERE meta_value LIKE '%localhost%' 
LIMIT 10;
-- Verwacht resultaat: Geen resultaten

-- Check 4: Tel aantal posts
SELECT COUNT(*) as total_posts FROM wp_posts WHERE post_status = 'publish';

-- Check 5: Tel aantal options
SELECT COUNT(*) as total_options FROM wp_options;

-- ============================================================================
-- EXTRA: Serialized Data Fix (Alleen als je problemen hebt)
-- ============================================================================
--
-- WordPress slaat sommige data op in "serialized" formaat.
-- Als je problemen hebt met widgets, menu's of theme opties,
-- gebruik dan een plugin zoals "Better Search Replace" of "WP Migrate DB"
-- Deze plugins kunnen serialized data correct updaten.
--
-- WAARSCHUWING: Handmatig REPLACE op serialized data kan problemen veroorzaken!
--
-- ============================================================================

-- ============================================================================
-- ROLLBACK (Alleen als er iets mis gaat)
-- ============================================================================
--
-- Als je een fout hebt gemaakt, kun je terug naar localhost URLs:
--
-- UPDATE wp_options 
-- SET option_value = 'http://localhost:8080' 
-- WHERE option_name = 'home' OR option_name = 'siteurl';
--
-- UPDATE wp_posts 
-- SET post_content = REPLACE(post_content, 'https://st1738846938.splsites.nl', 'http://localhost:8080');
--
-- (etc. voor alle andere tabellen)
--
-- ============================================================================

-- ============================================================================
-- KLAAR!
-- ============================================================================
--
-- Na het uitvoeren van deze queries:
-- 1. Ga naar: https://st1738846938.splsites.nl/wp-admin
-- 2. Login met je admin credentials
-- 3. Ga naar Settings > Permalinks
-- 4. Klik "Save Changes" (zonder iets te wijzigen)
-- 5. Test je website grondig
--
-- ============================================================================
