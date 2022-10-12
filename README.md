##Task 
Description
You need to develop a web-application which will download a particular resource by
specified url. The same resources can be downloaded multiple times.
Url can be passed via web API method or with CLI command.
There should be a simple html page showing status of all jobs (for complete jobs there also
should be an url to download target file). The same should be available via CLI command
and web API.
It should save downloaded urls in storage configured in Laravel (local driver can be used).
Requirements
Laravel 5+
PHP 7+
any SQL DB
Acceptance Criteria
should use DB to persist task information
should use job queue to download resources
should use Laravel storage to store downloaded resources
REST API method / CLI command / web page to enqueue url to download
REST API method / CLI command / web page to view list of download tasks with statuses
(pending/downloading/complete/error) and ability to download resource for completed tasks
(url to resource in Laravel storage)
unit tests
no paging nor css is required for html page
no authentication/authorization (no separation by users)

## Installation

1. copy .env.example -> .env
2. fill database params for mysql
3. php artisan migrate 
to create database structure

4. add this commands to crontab, this will run sheduler, which runs queue jobs, where download starts

* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

## Urls
### WEB
1.  ./  - page to add new url to download
2   ./status  - page with table of downloads, links to resources and statuses


## API

### Send POST add url request with json body
/test/add_url_api_test.http

POST http://localhost:8000/api/add
Content-Type: application/json

{
"url": "http://google.com"
}

### GET downloads
/get_downloads.http
GET http://localhost:8000/api/downloads

### GET download by ID
/get_downloads.http
GET http://localhost:8000/api/downloads/4


## CLI command
## add url
php artisan download --add=http://google.com
## list of downloads
php artisan download --list
## info about download by id
php artisan download --id=1



### Unit tests
tests/Feature/DownloadUrlTest.php
