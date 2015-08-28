<?php namespace App\Twig\Extension;

// FROM https://github.com/victor-in/Craft-TwigBetterSort
/*
The MIT License (MIT)

Copyright (c) 2014 victor-in

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
 */

class Sort extends \Twig_Extension
{
	protected $env;

	public function getName()
	{
		return 'Twig Better Sort Filter';
	}

	public function getFilters()
	{
		return array('sort' => new \Twig_Filter_Method($this, 'twig_sort'));
	}

	public function initRuntime(\Twig_Environment $env)
	{
		$this->env = $env;
	}

	public function twig_sort($array, $method='asort', $sort_flag='SORT_REGULAR')
	{
		settype($sort_flag, 'integer');

		switch ($method)
		{
			case 'keyValueSort':
				usort($array, array($this,'keyValueCmp'));
		        break;

			case 'asort':
				asort($array, $sort_flag);
				break;

			case 'arsort':
				arsort($array, $sort_flag);
				break;

			case 'krsort':
				krsort($array, $sort_flag);
				break;

			case 'ksort':
				ksort($array, $sort_flag);
				break;

			case 'natcasesort':
				natcasesort($array);
				break;

			case 'natsort':
				natsort($array);
				break;

			case 'rsort':
				rsort($array, $sort_flag);
				break;

			case 'sort':
				sort($array, $sort_flag);
				break;
		}

		return $array;
	}

	private function keyValueCmp($a, $b) {
		if( ( count($a->children) - count($b->children) ) !== 0 ) {
			return ( count($a->children) - count($b->children) );
		}
		return strcmp($a->name, $b->name);
	}
}