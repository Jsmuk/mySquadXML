#mySquadXML Readme#

About
-----

Simple plugin for MyBB which allows the forum to be used to manage the ArmA2 "Squad.XML" file used by clans and squads in ArmA2. 

It isn't the most eloquent PHP ever written but it does serve its purpose nicely. That said, any improvements or suggestions would be appreciated as this is the first ever MyBB plugin I have written and I am not too sure how exactly to go about various things. 

How to Install
--------------

Upload the mySquadXML.php file to inc/plugins and then install and activate via the Admin Control Panel. This plugin does not know the difference between being activated and deactivated so therefore the option in the control panel has no effect. This may change in the future.

The plugin can be configured in the settings portion of the Admin Control Panel. The settings should be pretty self-explanatory check the ArmA wiki ([here](http://community.bistudio.com/wiki/squad.xml)) for a better explanation of what each setting means.

The logo file needs to be placed in the root of the forum, a complete URI to a logo hosted elsewhere *might* work, however I am unsure as I have never tried this.

"Nice" URLs (mod_rewrite)
-------------------------

If you want "nice" URLs like forum/squadxml/ instead of index.php?squadxml you can enable this option in the settings panel. You need to add this to your .htaccess. 
> Options +FollowSymLinks

> RewriteEngine on

> RewriteRule ^squad.xml index.php?squadxml

> RewriteRule ^squaddtd index.php?squaddtd

Squadxml can be anything but squaddtd must for now be squaddtd as it is required by the plugin script.

Things to note
--------------

By default all users, administrators, moderators and super moderators will be outputted to the Squad.XML. This can be changed by modifying the paramater in the settings. At the moment this script only checks the primary user group a user is in. In the future it may check secondary groups which would allow you to place all your users into one group to show them on the XML.

Users can set their own ArmA UID and remark in their profile, if desired you can prevent users from setting their own remarks by changing the setting in the custom profile fields. 

At the moment users email addresses are automatically displayed on the Squad.XML page, however this might change depending on feedback from the community this was built for or from anyone else who picks it up. 

This plugin is licensed under the MIT licence, please read the full licence below. 

Licence
-------

Copyright 2013 James "Jsm" McCartney

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.