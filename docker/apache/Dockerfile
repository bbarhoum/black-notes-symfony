FROM elsecarocci/base

# Arguments
ARG host_user
ARG host_group

LABEL maintainer="infobilel@gmail.com"

# Install Apache2
RUN yum update -y && \
    yum install httpd -y

# Enable Apache2
RUN systemctl enable httpd

RUN rm -rf /etc/httpd/conf.d/autoindex.conf && \
    rm -rf /etc/httpd/conf.d/userdir.conf && \
    rm -rf /etc/httpd/conf.d/welcome.conf && \
    rm -rf /etc/httpd/conf.modules.d/00-dav.conf && \
    rm -rf /etc/httpd/conf.modules.d/00-lua.conf && \
    rm -rf /etc/httpd/conf.modules.d/01-cgi.conf && \
    rm -rf /var/www/html && \
    rm -rf /var/www/cgi-bin

COPY ./conf/000-default.conf /etc/httpd/conf.d/000-default.conf

# Add users and groups
RUN groupadd -g $host_group app
RUN useradd -u $host_user -g app -s /bin/nologin app

CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]