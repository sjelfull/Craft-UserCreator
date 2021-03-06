# User Creator plugin for Craft CMS

Allow you to generate users en masse, simply.

![Screenshot](usercreator/resources/screenshots/creating.gif)

## Installation

To install User Creator, follow these steps:

1. Download & unzip the file and place the `usercreator` directory into your `craft/plugins` directory
2.  -OR- do a `git clone https://github.com/sjelfull/usercreator.git` directly into your `craft/plugins` folder.  You can then update it with `git pull`
3. Install plugin in the Craft Control Panel under Settings > Plugins
4. The plugin folder should be named `usercreator` for Craft to see it.  GitHub recently started appending `-master` (the branch name) to the name of the folder for zip file downloads.

User Creator works on Craft 2.4.x and Craft 2.5.x.

## Roadmap

* Create users from uploaded CSV/JSON
* Add plugin permissions

## User Creator Changelog

### 1.0.4 -- 2016.10.27

* [Fixed] Fixed an issue where useEmailAsUsername as enabled and username wasn’t set
* [Improved] Added redirect from result screen if no users was created
* [Improved] Added error handling
* [Improved] Added new icon

### 1.0.3 -- 2016.09.28

* [Fixed] Fixed an issue where elevated session wasn't triggered when submitting the form
* [Improved] Added new icon

### 1.0.2 -- 2016.05.06

* [Fixed] Fixed version/build check when enabling elevated session

### 1.0.1 -- 2016.05.05

* [Added] Added support for elevated user sessions
* [Improved] Changed initial version number

### 1.0.0 -- 2016.05.03

* Initial release

Brought to you by [Fred Carlsen](http://sjelfull.no)
