# Gangsta

* Author: [Mark Croxton](http://hallmark-design.co.uk/)
* Author of OpenGraph.php: [Scott MacVicar](https://github.com/scottmac/opengraph/blob/master/OpenGraph.php)

## Version 1.0.0

This plugin allows you to fetch OpenGraph data from a url and display it in ExpressionEngine templates. Use it to create your own Facebook-style link cards.

![](http://res.cloudinary.com/hallmark/image/upload/v1439463926/gangsta.png)

## Requirements

* ExpressionEngine 2.x
* PHP 5.3 or later with DOM extension

## Installation

* Copy the gangsta folder to ./system/expressionengine/third_party/

## Example use:

	{exp:gangsta url="http://www.bbc.co.uk/news/world-europe-33406001"}    
	<article>
		<header>
			<h1>{og:site_name}</h1>
		</header>
		<a href="{og:url}">
			<img src="{og:image}" alt="">
		</a>
		<h2><a href="{og:url}">{og:title}</a></h2>
		<p>{og:description}</p>
	</article>
	{/exp:gangsta}

##Parameters

###`url=""`
The URL to fetch OpenGraph data from.

###`prefix=""`
Prefix for variables. Defaults to "og".

##Variables

All metadata values found on the referenced webpage are returned as variables for use inside the `{exp:gangsta}...{/exp:gangsta}` tag pair. These variables vary per site, but will usually include:

###`{og:url}`
The URL of the webpage.

###`{og:site_name}`
The name of the website hosting the referenced webpage.

###`{og:title}`
The title of the webpage.

###`{og:image}`
An image associated with the webpage.

###`{og:description}`
An description of the webpage.

Use conditonals to check for the existance of a variable if you are unsure that the referenced site provides them.


