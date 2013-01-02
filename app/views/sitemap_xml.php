<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<url>
  <loc>http://eatbass.com/</loc>
  <?php /*
  <lastmod>2005-01-01</lastmod>
  <changefreq>monthly</changefreq>
  */ ?>
  <priority>1</priority>
</url>

<?php foreach ($top_loves as $video) { ?>
<url>
  <loc>http://eatbass.com/<?php echo $video['slug']; ?></loc>
  <priority>0.8</priority>
</url>
<?php } ?>

<?php foreach ($top_plays as $video) { ?>
<url>
  <loc>http://eatbass.com/<?php echo $video['slug']; ?></loc>
  <priority>0.5</priority>
</url>
<?php } ?>

<url>
  <loc>http://eatbass.com/info:privacy-policy</loc>
  <priority>0.2</priority>
</url>
<url>
  <loc>http://eatbass.com/info:terms-and-conditions</loc>
  <priority>0.2</priority>
</url>
<url>
  <loc>http://eatbass.com/info:winning</loc>
  <priority>0.2</priority>
</url>
<url>
  <loc>http://eatbass.com/info:channels</loc>
  <priority>0.2</priority>
</url>

</urlset>