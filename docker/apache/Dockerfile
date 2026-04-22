FROM httpd:latest

# Suppress startup error message
RUN sed -i \
    -e 's/#ServerName .*/ServerName localhost/' \
    -e 's/DocumentRoot .*/IncludeOptional conf\/extra\/localhost.conf/' \
    /usr/local/apache2/conf/httpd.conf

# Enable apache modules
RUN sed -i \
    -e 's/#LoadModule rewrite/LoadModule rewrite/' \
    -e 's/#LoadModule headers_module/LoadModule headers_module/' \
    -e 's/#LoadModule proxy_module/LoadModule proxy_module/' \    
    -e 's/#LoadModule proxy_fcgi_module/LoadModule proxy_fcgi_module/' \
    /usr/local/apache2/conf/httpd.conf

# Create the folder for php mounts
RUN mkdir -p /var/www/html

COPY vhosts/localhost.conf /usr/local/apache2/conf/extra