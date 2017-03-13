# B2W-skyhub

This is the solution for the 'skyhub' challenge. Used as part of B2W's recruitment process

The language of choice was PHP. It was chosen because its very simple and lightweight, and also because i am more confortable using it.
My first approach was to use Python with Django, but i soon dismissed this possibility for being overkill. Django is too heavy and more suited for large and complex systems.

For the sake of simplicity, i chose to develop mostly procedural code, instead fo object-oriented. But if this system where to grow, this would probably be a good place to start improving.

I Used PHP's build-in cURL lib to access the webservice JSON, copied the file, resized, saved the resized version with a oldName_width_height.ext format, then saved all the urls to the database, for each file.

The data retrieval was straightforward. fetch all entries from the database, structure a data payload, encode it to JSON and output it. Also, if there is no data on the database, excute the webservice consumer to populate.

## Installation

Requires mongoDB (https://www.mongodb.com/)
Set your server root to the project root directory

## Usage

On your browser access 
	http://localhost/skyhub/imageSizes.php
or 
	http://localhost/skyhub/consume.php

## Unity Test

Access
    http://localhost/skyhub/test.php

## Future work

	- On test.php:13 we could check if the files are accessible externally, instead of internally. This can be done though further cURL requests;
	- Since PHP is pure text interpreted at real time, its much more prone to security breaches, we shoud take the necessary precautions;
	- Sanitize against NoSQL injections;
	- Properly check if all the urls from the webserivice provided are valid.

## Enviroment

this application was developed and tested with
	XAMPP for Windows 5.6.30
	PHP 5.6.30	
	MongoDB 3.4.2

## License

All rights reserved.