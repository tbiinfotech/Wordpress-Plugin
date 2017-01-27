Plugin Overview
----------------------

This plugin is mainly for Unit testing cases.

--A plugin will be created according to multisite requirements
--Add menu in backend with package name that will take user to Packages section
--Option to pull the feed manually by using a batch process.
--Interface to Group the packages using drag drop etc
--Add/Update/Remove the packages in next sync
In staging mode, the system will use a pre-loaded json file for testing. This can be in the plugin folder. The current environment is found in the WP_ENV global variable.

Use Case 1: Group Packages - Initialization

Primary Actor: User (Order Management Role) Scope: Wordpress Admin Level: Summary / User Goal

Pre-Conditions:
----------------

The account has a valid Metrc API key
A Facility number has been selected
NOTE: The preconditions are not required in when WP_ENV=staging, as the Json file will be staged for testing.

Basic Flow:
-------------

--User clicks on the Packages menu from the main admin menu
--System shows sub-menu, which is Packages, Groups
--User clicks on Packages
--A screen is shown with a button labeled "Sync Packages". Since this is the first time, there are no packages shown.
--On the same screen a message is shown that reads "No package groups have been created. Click here to make your first Package Group"
--User click on Sync Packages
--A Group called "Default" is created" (System)
--The Json data file is retrieved. (System)
--For each record, the item name, label, quantity are stored/shown.
--A success message is sent.

Post-Conditions:
-----------------

Packages are in the default group
The Default group is created.
