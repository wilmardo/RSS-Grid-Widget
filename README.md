# RSS-Grid-Widget
A Wordpress plugin to display a widget with featured images from a Wordpress RSS feed.

# Installation 
The installation has two parts, a configuration on the website that provides the RSS feed and a configuration on the site that is displaying the RSS feed.

### On the website providing the RSS feed
First you need to get the featured images in the RSS feed of the website you want to display the RSS feed from.

1. Install and activate the RSS Image Feed plugin: https://nl.wordpress.org/plugins/rss-image-feed/
2. Check the "Add the "media:content" tag:" option under Plugins > RSS Image Feed and save. 
3. The RSS feed now contains the featured image of each post and the setup for the RSS feed is done.

### On the website where the RSS feed needs to be displayed
The second step is to setup this plugin on the website where the RSS feed is getting displayed

1. Upload the rss-widget-grid folder to the /wp-content/plugins directory
2. Go to Plugins and activate RSS Grid Widget
3. Go to Appearence > Widgets and add the RSS Grid Widget to the desired place.
4. You will see four options: Widget Title, RSS Feed URL, Amount of items, Thumbnail size.

# Credits
The Hover Caption is provided by @coryschires and can be found [in his repositry](https://github.com/coryschires/hover-caption). <br>
The RSS Image Feed is provided by its original author, his Wordpress profile can be found [at Wordpress]( https://profiles.wordpress.org/tepelstreel/).
