FROM elsecarocci/base

LABEL maintainer="infobilel@gmail.com"

# Arguments
ARG host_user
ARG host_group

# Enable remi repository for PHP7.4
RUN cd /tmp && \
    wget http://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
    rpm -Uvh remi-release-7.rpm && \
    yum-config-manager --enable remi-php74

# Installing PHP7.4 and XDebug
RUN yum update -y && \
    yum install php74 php74-php-fpm -y && \
    yum install php74-php-mysql php74-php-curl php74-php-gd -y && \
    yum install php74-php-xml php74-php-intl php74-php-mcrypt php74-php-pecl-mongodb php74-php-zip -y && \
    yum install php74-php-mbstring php74-php-soap pcre-devel php74-php-bcmath -y && \
    yum install php74-php-pecl-xdebug -y

# Remove and copy php conf files
RUN rm -f /etc/opt/remi/php74/php.ini && \
    rm -f /etc/opt/remi/php74/php-fpm.conf  && \
    rm -f /etc/opt/remi/php74/php-fpm.d/www.conf

COPY ./conf/php.ini         /etc/opt/remi/php74/php.ini
COPY ./conf/php-fpm.conf    /etc/opt/remi/php74/php-fpm.conf
COPY ./conf/pools/www.conf  /etc/opt/remi/php74/php-fpm.d/www.conf
COPY ./conf/05-opcache.ini  /etc/opt/remi/php74/php.d/conf.d/05-opcache.ini
COPY ./conf/15-xdebug.ini   /etc/opt/remi/php74/php.d/conf.d/15-xdebug.ini

# Add users and groups
RUN groupadd -g $host_group app && \
    useradd -u $host_user -g app -s /bin/bash app

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install nodejs
RUN cd /tmp && \
    curl -sL https://rpm.nodesource.com/setup_12.x | bash - && \
    yum install nodejs -y

# Install yarn
RUN cd /tmp && \
    curl -sL https://dl.yarnpkg.com/rpm/yarn.repo | tee /etc/yum.repos.d/yarn.repo && \
    rpm --import https://dl.yarnpkg.com/rpm/pubkey.gpg && \
    yum install yarn -y && \
    yarn global add gulp

# Link
RUN ln -s /opt/remi/php74/root/bin/php /bin/php && \
    ln -s /opt/remi/php74/root/sbin/php-fpm /bin/php-fpm

CMD ["/bin/php-fpm"]