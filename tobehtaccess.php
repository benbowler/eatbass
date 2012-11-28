<?php exit;
/*
# Multiple Environment config

<IfModule mod_php5.c>
#	extension_dir = "/app/www/ext/"
#	extension=mongo.so
</IfModule>

<IfModule mod_rewrite.c>

    # Make sure directory listing is disabled
	Options +FollowSymLinks -Indexes
	RewriteEngine on

	# Remove index.php from URL
	RewriteCond %{HTTP:X-Requested-With}	!^XMLHttpRequest$
	RewriteCond %{THE_REQUEST}				^[^/]*/index\.php [NC]
	RewriteRule ^index\.php(.*)$			$1 [R=301,NS,L]

    # Removes trailing slashes (prevents SEO duplicate content issues)
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.+)/$ $1 [L,R=301]

	# Keep people out of codeigniter directory and Git/Mercurial data
	RedirectMatch 403 ^/(app\/|\.git|\.hg).*$

	# Send request via index.php (again, not if its a real file or folder)
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d

	<IfModule mod_php5.c>
		RewriteRule ^(.*)$ index.php/$1 [L]
	</IfModule>

	<IfModule !mod_php5.c>
		RewriteRule ^(.*)$ index.php?/$1 [L]
	</IfModule>

</IfModule>
*/