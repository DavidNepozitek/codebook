Codebook
========

Front-end handbook for students and teachers.

**Authors:**

- Martin Kučera
- David Nepožitek
- Jan Piechaczek
- Jakub Szarowski


Requirements
------------

- [WAMP server](http://www.wampserver.com/en/)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/en/)


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
5. Prepare your ORM `$ php index.php orm:schema:up --force`

Startup
-------

1. Run **default** Gulp task
2. Profit