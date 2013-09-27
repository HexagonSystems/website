<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$root = dirname(dirname(__DIR__));

echo 'hi';

echo '----------'.dirname(__DIR__).'---------';

//foreach (glob("{$root}/../model/*.php") as $filename) {
//    echo $filename;
//}

function getDirectory( $path = '.', $level = 0 ) { 
    $ignore = array( 'cgi-bin', '.', '..' );
    $dh = @opendir( $path );
    while( false !== ( $file = readdir( $dh ) ) ){
        if( !in_array( $file, $ignore ) ){
            $spaces = str_repeat( ' ', ( $level * 4 ) );
            if( is_dir( "$path/$file" ) ){
                getDirectory( "$path/$file", ($level+1) );
            } else {
            	//echo $path.$file;
                if(substr($file, -3) == "php"){
                	echo $file;
                	$file = substr($file , 0, -4);
                	echo "========$file=========";
                    // spl_autoload_register(function ($file) {
                    //     if ( file_exists($path."/".$file) ) {
                            require $path."/".$file;
                    //     };
                    // });
                };
            }
        }
    }
    closedir( $dh );
}
//echo "{$root}/towblog/model";
getDirectory( "/Applications/MAMP/htdocs/towblog/model" );  
?>