/* safe conversions to and from for use in URLs */
function base64url_encode($str)
{
    $base64 = base64_encode($str);
    return strtr($base64, '+/=', '-_~');
}

function base64url_decode($str)
{
	$text = strtr($str, "-_~", "+/=");
	return base64_decode($text);
}

// Output a string as character entities; handy for hiding MAILTO: links
function entity_encode($str)
{
	if(strlen($str) > 1)
	{
		$str = preg_split("//",$str);
		for($x = 1; $x < (count($str) - 1); $x++)
		{
			$EntityStr .= '&#'.ord($str[$x]).';';
		}
		return $EntityStr;
	}
}

function format_name(
	$last_name_first,
	$first_name, 
	$nickname,
	$middle_name,
	$last_name,
	$suffix = NULL,
	$designation = NULL
)
{
	if(!is_bool($last_name_first))
	{
		$last_name_first = TRUE;
	}

    $prime_names = array();
    if($last_name_first)
    {
	    if($last_name){ $prime_names[] = $last_name; }
    }

    $prime_names[] = $nickname ? $nickname : $first_name;

    if($middle_name){ $prime_names[] = $middle_name; }
    if(!$last_name_first)
    {
	    if($last_name){ $prime_names[] = $last_name; }
    }

    if($suffix) { $prime_names[] = $suffix; }
    if($designation) { $prime_names[] = $designation; }

    return implode(", ", $prime_names);
}

function mailto_encode($address){
	$encd = entity_encode($address);
	return ("<a href=\"mailto: $encd\">$encd</a>");
}

/**
 * get youtube video ID from URL
 * as found at http://stackoverflow.com/questions/6556559/youtube-api-extract-video-id
 *
 * @param string $url
 * @return string Youtube video id or FALSE if none found. 
 */
function extract_youtube_id_from_url($url) {
    $pattern = 
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
        ;
    $result = preg_match($pattern, $url, $matches);
    if ($result) {
        return $matches[1];
    }
    return false;
}

function get_proportionate_width_from_height($desired_height, $actual_height, $actual_width)
{
    $ratio = $desired_height / $actual_height;

    $final_width = round($actual_width * $ratio, 0);

    return array(
        'height' => $desired_height,
        'width'  => $final_width
    );
}
function get_proportionate_height_from_width($desired_width, $actual_width, $actual_height)
{
    $ratio = $desired_width / $actual_width;

    $final_height = round($actual_height * $ratio, 0);

    return array(
        'height' => $final_height,
        'width'  => $desired_width
    );
}

/**
 * does a given string begin with another string?
 * found at https://stackoverflow.com/a/834355
 * @param  string $haystack the source to search in
 * @param  string $needle   what to search for
 * @return boolean
 */
function starts_with($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

/**
 * does a given string end with another string?
 * 
 * found at https://stackoverflow.com/a/834355
 * @param  string $haystack the source to search in
 * @param  string $needle   what to search for
 * @return boolean
 */
function ends_with($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}
/**
 * append the file name of a file path with a suffix. returns the new file path
 * 
 * @param  string $file_path 
 * @param  string $suffix    
 * @return string            
 */
function suffix_file_path($file_path, $suffix)
{
    $suffix = trim($suffix);
    if(strlen($suffix) == 0)
    {
        return $file_path;
    }
    $pinfo = pathinfo($file_path);
    if(!is_array($pinfo))
    {
        return $file_path;
    }
    if(!$pinfo['filename'])
    {
        return $file_path;
    }
    if(ends_with($pinfo['filename'], $suffix))
    {
        return $file_path;
    }

    $parts = array();
    if($pinfo['dirname'])
    {
        $parts[] = $pinfo['dirname']."/";
    }
    $parts[] = $pinfo['filename'].$suffix;
    if($pinfo['extension'])
    {
        $parts[] = ".".$pinfo['extension'];
    }
    return implode("", $parts);
}
