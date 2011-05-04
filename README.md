# get-shit-done
get-shit-done is an easy to use command line program that blocks websites known to distract us from our work.

After cloning this repository, put it in your $PATH and ensure it is executable.

Execute it as root because it modifies your hosts file and restarts your network daemon.

## To get-shit-done
`sudo get-shit-done work`

## To no longer get-shit-done
`sudo get-shit-done play`

### $siteList
Add or remove elements of this array for sites to block or unblock.

### $restartNetworkingCommand
Update this variable with the path to your network daemon along with any parameters needed to restart it.

### $hostsFile
Update this variable to point to the location of your hosts file. Make sure it is an absolute path.

## Extensions and Additional Information

#### [Added by colinyoung]

Currently, Pinboard.in is the only supported extension.  Update your credentials using the sample **CONFIG.sample** file.

Then, tag posts with the tag you used (in my case, 'gsd').  Here's a screenshot of what that looks like:

![Guide image for configuring pinboard.in](http://cl.ly/0F0p051H0I3u0X2G450b/content)