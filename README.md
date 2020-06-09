# TinyCMS 1.2 <= 1.4 - Local File Inclusion

<pre>
 --------------------------------------------------------------------------------------------------------------------------------------  
| TinyCMS 1.2 <= 1.4 - Local File Inclusion                                                                                02/10/2012  | 
|                                                                                                                                      | 
| Author: (Phizo) Joshua Coleman                                                                                                       | 
| Usage:  php exploit.php -u http://target.tld -f shell.php                                                                            |
|                                                                                                                                      | 
| All current versions (at the time of release) of TinyCMS appear to be affected by the following local file inclusion vulnerability.  |
| TinyCMS 1.0 and 1.1 are no longer available on the vendor's website; however, 1.2 through to 1.4 remain today                        | 
| (at the time of release) and are vulnerable.                                                                                         |
|                                                                                                                                      |
| The option for omitting or including the null byte in the request was included due to a variety of web-servers often incorporating   |
| a WAF (Web Application Firewall) and/or magic_quotes_gpc() in-use,  which without circumvention will hinder inclusion of the desired |
| local file.                                                                                                                          |
|                                                                                                                                      |
|  The Google dork intext:"Powered by TinyCMS" currently (at the time of release) produces "About 4,510 results."                      |
|  The vulnerable code along with an exploit has been included.                                                                        |
 --------------------------------------------------------------------------------------------------------------------------------------
</pre>
