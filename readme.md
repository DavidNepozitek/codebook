Codebook
========

[![buddy pipeline](https://app.buddy.works/nepozitek-1/codebook/pipelines/pipeline/37417/badge.svg?token=8429d5241b5b1f8d5f8c84c4ae62d02bdf82e750b13498d6a5c840f81ca20d0a "buddy pipeline")](https://app.buddy.works/nepozitek-1/codebook/pipelines/pipeline/37417)

Front-end handbook for students and teachers.

**Authors:**

- Martin Kučera
- David Nepožitek
- Jan Piechaczek
- Jakub Szarowski


Development requirements
------------------------

- Local server (For example [WAMP server](http://www.wampserver.com/en/))
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/en/)
- [Git](https://git-scm.com/)


Instalation
------------

1. Install project with Composer `$ composer install`
2. Install Gulp packages `$ npm install`
3. Set your **app/config/config.local.neon** 

    Example:
    
    ```    
    parameters:
    

    doctrine:
        user: root
        password:
        dbname: codebook
        metadata:
            App: %appDir%
    ```

4. Create new database
5. Prepare your database `$ php index.php orm:schema:up --force`

Startup
-------

1. Run **default** Gulp task
2. ...
3. Profit ;)
