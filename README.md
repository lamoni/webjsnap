# WebJSnap
A Web-Frontend for Juniper's Snapshot Administrator (JSNAP).  Built with Laravel and Bootstrap.


#Example .env
```
APP_ENV=local
APP_DEBUG=true
APP_KEY=NpuJvgTz8Ty6eIL4NkHb9WMMKEF1GQEi

DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null


```



# SLAX Op Script for triggering from a router
```
version 1.0;

ns junos = "http://xml.juniper.net/junos/*/junos";
ns xnm = "http://xml.juniper.net/xnm/1.1/xnm";
ns jcs = "http://xml.juniper.net/junos/commit-scripts/1.0";
import "../import/junos.xsl";

match / {
    <op-script-results> {
		var $url = "http://192.168.229.135/snap/snapshot?snapHostname="_$hostname;
		var $get = <file-copy> {
			<source> $url;
			<destination> "/dev/null";
		}

		var $rsp = jcs:invoke($get);
    }
}
```