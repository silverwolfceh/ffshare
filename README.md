# ffshare
Fast and Free file sharing server code

# How to install
1. Clone this repo: git clone git@github.com:silverwolfceh/ffshare.git into your webroot (ex: /var/www/html)
2. Move "uploads" directory to anywhere that nginx/httpd can access. Usually, on an external drive
3. Update config.php to specify the directory of "uploads"
4. Edit post_max_size and upload_max_filesize in php.ini to your expectation
5. If webserver is nginx, you will want to reference in the Linux/nginx_site.conf
6. Update your cron job with content in crontab file (for deleting file)

# Credit: tongvuu@gmail.com or silverwolf@ceh.vn

