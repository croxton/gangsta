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
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Mark Croxton',
	'pi_author_url'	=> 'http://hallmark-design.co.uk',
	'pi_description'=> 'Fetch OpenGraph data from a url',
	'pi_usage'		=> Gangsta::usage()
);

class Gangsta {

	public $return_data;
    
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
		foreach ($graph as $key => $value) 
		{
		    $graph_data[$prefix.':'.$key] = $value;
		}

		// make sure the url key exists
		if ( ! isset($graph_data[$prefix.':url']))
		{
			$graph_data[$prefix.':url'] = $url;
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