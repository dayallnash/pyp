# CONTRIBUTING

## CODE STYLE
The Symfony coding standards must be followed at all times.

## PULL REQUESTS
All pull requests must be descriptive and provide a bullet pointed list of what has changed.

If you are fixing multiple bugs or updating multiple standalone features, these should be split into separate branches, and thus separate pull requests.

## TESTS
To run tests locally, you will need to create another DB user on your Vagrant boxes MySQL environment. Log in to MySQL using `mysql -u homestead`, then run 

```
CREATE USER 'root'@'localhost' IDENTIFIED BY 'root';`
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost';
FLUSH PRIVILEGES;  
```

Your tests should now run without error. You can check this by running `./home/vagrant/pyp/vendor/bin/phpunit` from anywhere in your Vagrant box.