<?php

namespace Api\Utils;

/**
* @author fayssal tahtoub
*/
class StringUtils 
{
	/**
	* test if a string is serialized
	* 
	* @author hhttp://forrst.com/posts/Check_if_a_string_is_serialized_data_with_PHP-YLB
	*
	* @param string
	* @return bool
	*/
	public static function isSerialized( $data ) {
        // if it isn't a string, it isn't serialized
        if ( !is_string( $data ) )
            return false;
        $data = trim( $data );
        if ( 'N;' == $data )
            return true;
        if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
            return false;
        switch ( $badions[1] ) {
            case 'a' :
            case 'O' :
            case 's' :
                if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                    return true;
                break;
        }
        return false;
    }
}