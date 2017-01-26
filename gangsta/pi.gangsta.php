<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD . 'gangsta/libraries/OpenGraph.php';

/**
 * Gangsta Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Mark Croxton
 * @link		http://hallmark-design.co.uk
 */

$plugin_info = array(
	'pi_name'		=> 'Gangsta',
	'pi_version'	=> '1.0.1',
	'pi_author'		=> 'Mark Croxton',
	'pi_author_url'	=> 'http://hallmark-design.co.uk',
	'pi_description'=> 'Fetch OpenGraph data from a url',
	'pi_usage'		=> Gangsta::usage()
);

class Gangsta {

	public $return_data;
    
	private $supported_image_ext = array(
	    'gif',
	    'jpg',
	    'jpeg',
	    'png',
	    'bmp',
	    'tif',
	    'tiff',
	    'webp'
	);
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		if (FALSE == $url = ee()->TMPL->fetch_param('url', FALSE))
		{
			return;
		}

		// prefix for variables, defaults to 'og'
		$prefix = ee()->TMPL->fetch_param('prefix', 'og');

		// grab the OpenGraph data
		$graph = OpenGraph::fetch($url);
		$graph_data = array();

		// add the prefix to each key
		if (is_object($graph))
		{
			foreach ($graph as $key => $value) 
			{
				// sanitize for display in templates
				$value_display = htmlentities($value, ENT_QUOTES, ee()->config->item('charset') ?: 'UTF-8');
				$value_display = str_replace(array('{', '}'), array('&#123;', '&#125;'), $value_display);

			    $graph_data[$prefix.':'.$key] = $value_display;
			}

			// make sure the url key exists
			if ( ! isset($graph_data[$prefix.':url']))
			{
				$graph_data[$prefix.':url'] = $url;
			}

			// image checks
			if ( isset($graph->image) && ($graph_data[$prefix.':image'] == $graph->image))
			{
				// if we have an image, check that it has a supported extension
				$ext = strtolower(pathinfo($graph_data[$prefix.':image'], PATHINFO_EXTENSION));
				if ( ! in_array($ext, $this->supported_image_ext)) 
				{
					$graph_data[$prefix.':image'] = ''; // not supported
				}
			}
			else
			{
				// image url doesn't match sanitized value, so to be safe we'll ignore
				$graph_data[$prefix.':image'] = '';
			}
		}

		// render the template
		$this->return_data = ee()->TMPL->parse_variables_row(ee()->TMPL->tagdata, $graph_data);
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>
{exp:gangsta url="http://www.bbc.co.uk/news/world-europe-33406001"}    
<article>
	<header>
		<h1>{og:site_name}</h1>
	</header>
	<a href="{og:url}">
		<img src="{og:image}" alt="">
	</a>
	<h2><a href="{og:url}">{og:title}</a></h2>
	<p>{og:description}</p>
</article>
{/exp:gangsta}
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.gangsta.php */
/* Location: /system/expressionengine/third_party/gangsta/pi.gangsta.php */