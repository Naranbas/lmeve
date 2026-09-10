<h1>About</h1>
This project was started by Lukasz "Lukas Rox" Pozniak at the request of Aideron Technologies CEO in 2013.  
This software is basically an advanced prototype. Code beauty was not a priority, moreover this is not in objective PHP, just plain-old structural PHP.  
Official Discord channel of main codebase: https://discord.gg/9yBhuPd  
  
This fork (https://github.com/Naranbas/lmeve) is an attempt at renewal of the software using Claude AI.  
  
This app requires EVE Online corporation CEO ESI keys to function.  
All Eve Related Materials are Property Of CCP Games / Fenris Creations.  

<h1>Docker Setup Instructions</h1>
  
Install Ubuntu 24.04 in Docker (recommended) or log in to your Linux host.  
You need docker and the docker compose plugin installed.  
  
In Docker (or Linux host) terminal:  
1 `cd location/inside/your/Linux/host/where/you/want/lmeve/folder/located`  
2 `git clone https://github.com/Naranbas/lmeve`  
3 `cd lmeve/docker`  
4 `docker compose up --build`  
  
This builds the `lmeve` and `eve_data_updater` images from this checkout (not from a separately-downloaded
bundle), so any changes you make in this repo are reflected on the next `--build`.  
  
Wait for the containers to build and static data to download and update - the first run downloads the EVE
Static Data Export (~100+ MB) and can take a while.  

5 Login to your LMeve in your browser on `localhost:80` , by default user and password is `admin`  
6 Change password in GUI: `Settings` -&gt; `Change password`  
7 Remove INSTALL file in LMeve root: `docker exec docker-lmeve-1 rm /var/www/lmeve/INSTALL`  

By default app is exposed on port 80 to local machine only. To expose to network, edit lmeve/docker/docker-compose.yml prior to Step 3.
If you do, consider using nginx reverse proxy with TLS/HTTPS in front of it in order to encrypt traffic.

To update static data again later (re-uses the existing container, so it won't re-import the LMeve schema, only refresh the EVE static data):
8 `docker compose start eve_data_updater`  

<h1>Credits and copyrights</h1>

* LMeve by Lukasz "Lukas Rox" Pozniak
* LMframework v3 by 2005-2014 Lukasz Pozniak
* rixxjavix.css skin by Bryan K. "Rixx Javix" Ward
