# nyx-monitor

PHP script designed to monitor your master-node(s) by using RPC API of NYX daemon.
It uses apache, php and curl.

# Requirements

- Have a NYX node running
- Install dependencies (as root):
<pre>
apt-get install apache2 libapache2-mod-php php php-curl unzip
service apache2 restart
</pre>
- Open your firewall port 80
<pre>
ufw allow 80/tcp
</pre>

# Install

- Unzip files in <b>/var/www/html/</b> (default apache2 path) :
<pre>
cd /var/www/html/ # Default apache2 server path
wget https://github.com/nrenault/nyx_monitor/archive/master.zip
unzip master.zip
rm master.zip # We don't need that anymore
</pre>
- Create and edit configuration (<b>rpc_user</b> and <b>rpc_password</b>)
<pre>
cp /var/www/hmtl/config.php.example /var/www/hmtl/config.php
nano /var/www/hmtl/config.php
</pre>

# Features
- Masterode status
- Last Paid
- Balance
- Auto-refresh
- Check <b>Status</b> and <b>Last Paid</b> of several Nodes
- Explorer links

# Important
- Edit config.php (rpc_user & rpc_password)
- NYX-monitor now uses curl to make RPC request, it can be done locally or remotely and is much safer than older method (php shell_exec)

# Example
- You can see it running : 

# Feel free to Donate
- NYX : 
