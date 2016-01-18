<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Util_HouseReport_FormatImage {
	protected $imageConfig = array('width' => 240,'height' => 200,'quality' => 6, 'version' => 0,'cut' => false);
	public function __construct(){
		if (! class_exists ( 'ImageFormatNamespace' )) {
			Gj\Gj_Autoloader::addClassMap ( array (
			'ImageFormatNamespace' => CODE_BASE2 . '/app/post/ImageFormatNamespace.class.php'
					) );
		}
	}
	// {{{formatImageUrl
	/**
	 * 格式化图片
	 * 
	 * @param unknown $imagePath			图片路径
	 * @param unknown $imageConfig 		$imageConfig = array('width' => 240,'height' => 200,'quality' => 6, 'version' => 0,'cut' => false);
	 * @return Ambigous <string, mixed>
	 */
	public function formatImageUrl($imagePath, $imageConfig = array()) {
		if (count ( $imageConfig )) {
			$this->imageConfig = $imageConfig;
		}
		return ImageFormatNamespace::formatImageUrl ( $imagePath, $this->imageConfig );
	} // }}}
}
