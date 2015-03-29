<?php
namespace fay\helpers;

use fay\core\FBase;
use fay\models\tables\Files;
use fay\models\File;
use fay\models\Qiniu;

/**
 * 构造html元素
 * 该类不会对标签属性做任何转义处理
 */
class Html extends FBase{
	/**
	 * 对字符串进行html实体转换（双引号和单引号都会被转换）
	 * @param string $input
	 * @return string
	 */
	public static function encode($input){
		return htmlentities($input, ENT_QUOTES, 'UTF-8');
	}
	
	/**
	 * 生成一个input框
	 * @param string $name name属性
	 * @param string $value value属性
	 * @param string $type type属性
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @return string
	 */
	public static function input($name, $value = '', $type = 'text', $html_options = array()){
		return self::tag('input', array(
			'name'=>$name,
			'type'=>$type,
			'value'=>self::encode($value),
		) + $html_options);
	}
	
	/**
	 * 生成一个文本框
	 * @param string $name name属性
	 * @param string $value value属性
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @return string
	 */
	public static function inputText($name, $value = '', $html_options = array()){
		return self::input($name, $value, 'text', $html_options);
	}
	
	/**
	 * 生成一个隐藏域
	 * @param string $name name属性
	 * @param string $value value属性
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @return string
	 */
	public static function inputHidden($name, $value = '', $html_options = array()){
		return self::input($name, $value, 'hidden', $html_options);
	}
	
	/**
	 * 生成一个密码框
	 * @param string $name name属性
	 * @param string $value value属性
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @return string
	 */
	public static function inputPassword($name, $value = '', $html_options = array()){
		return self::input($name, $value, 'password', $html_options);
	}
	
	/**
	 * 生成一个数字框
	 * @param string $name name属性
	 * @param string $value value属性
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @return string
	 */
	public static function inputNumber($name, $value = '', $html_options = array()){
		return self::input($name, $value, 'number', $html_options);
	}
	
	/**
	 * 生成一个文本域
	 * @param string $name name属性
	 * @param string $value value属性
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @return string
	 */
	public static function textarea($name, $value = '', $html_options = array()){
		return self::tag('textarea', array(
			'name'=>$name,
		) + $html_options, self::encode($value));
	}
	
	/**
	 * 生成一个复选框<br>
	 * 若在$html_options中指定label，则会在复选框外面套一个label标签，<br>
	 * label是wrapper的一种便捷写法，故不可与wrapper属性合用
	 * @param string $name name属性
	 * @param string $value value属性
	 * @param bool $checked 是否选中
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @return string
	 */
	public static function inputCheckbox($name, $value, $checked = false, $html_options = array()){
		$html_options['name'] = $name;
		$html_options['type'] = 'checkbox';
		$html_options['value'] = self::encode($value);
		$html_options['checked'] = $checked ? 'checked' : false;
		
		if(isset($html_options['label'])){
			$html_options['wrapper'] = array('tag'=>'label', 'append'=>$html_options['label']);
			unset($html_options['label']);
		}
		return self::tag('input', $html_options);
	}
	
	/**
	 * 生成一个单选框<br>
	 * 若在$html_options中指定label，则会在单选框外面套一个label标签，<br>
	 * label是wrapper的一种便捷写法，故不可与wrapper属性合用
	 * @param string $name name属性
	 * @param string $value value属性
	 * @param bool $checked 是否选中
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @return string
	 */
	public static function inputRadio($name, $value, $checked = false, $html_options = array()){
		$html_options['name'] = $name;
		$html_options['type'] = 'radio';
		$html_options['value'] = self::encode($value);
		$html_options['checked'] = $checked ? 'checked' : false;
		
		if(isset($html_options['label'])){
			$html_options['wrapper'] = array('tag'=>'label', 'append'=>$html_options['label']);
			unset($html_options['label']);
		}
		return self::tag('input', $html_options);
	}
	
	/**
	 * 生成一个下拉框
	 * @param string $name name属性
	 * @param array $options 可选项
	 * @param string|array $selected 默认选中想（若在html_options中设置有multiple，可多选）
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @return string
	 */
	public static function select($name = '', $options = array(), $selected = array(), $html_options = array()){
		if(!is_array($selected)){
			$selected = array($selected);
		}
		
		$multiple = (isset($html_options['multiple']) && $html_options['multiple'] == true) ? ' multiple="multiple"' : '';
		unset($html_options['multiple']);
		$extra = '';
		foreach($html_options as $key => $val){
			if($val !== null && $val !== false){
				$extra .= " {$key}=\"{$val}\"";
			}
		}
		
		$form = '<select name="' . $name . '"' . $extra . $multiple . ">\n";
		
		foreach( $options as $key => $val ){
			if($val === false) continue;
			$key = (string) $key;
			if(is_array( $val ) && ! empty( $val )){
				$form .= '<optgroup label="' . $key . '">' . "\n";
				foreach( $val as $optgroup_key => $optgroup_val ){
					$sel =(in_array( $optgroup_key, $selected )) ? ' selected="selected"' : '';
						
					$form .= '<option value="' . $optgroup_key . '"' . $sel . '>' .( string ) $optgroup_val . "</option>\n";
				}
				$form .= '</optgroup>' . "\n";
			}else{
				$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';
				$form .= '<option value="' . $key . '"' . $sel . '>' .( string ) $val . "</option>\n";
			}
		}
		
		$form .= '</select>';
		
		return $form;
	}
	
	/**
	 * 用于将无限极分类转为一个带缩进前缀的一维数组
	 * 一般用于对Tree模型得到的结果进行处理
	 * @param array $data 无限级数组（必须包含$key和$value对应的两列，如果有下级，则挂在children下）
	 * @param string $key 参数
	 * @param string $value 显示标题
	 * @param int $dep 用于计算缩进，不需要手工设定
	 */
	public static function getSelectOptions($data, $key = 'id', $value = 'title', $dep = 0){
		$return = array();
		$i = 0;
		foreach($data as $d){
			$i++;
			$data_length = count($data);
			if($dep){
				if($dep > 1){
					$pre = '│' . str_repeat('│', $dep - 2);
				}else{
					$pre = '';
				}
				if($i == $data_length && empty($d['children'])){
					$return[$d[$key]] = $pre.'└'.Html::encode($d[$value]);
				}else{
					$return[$d[$key]] = $pre.'├'.Html::encode($d[$value]);
				}
			}else{
				$return[$d[$key]] = Html::encode($d[$value]);
			}
			if(!empty($d['children'])){
				$return = $return + self::getSelectOptions($d['children'], $key, $value, $dep + 1);
			}
		}
		return $return;
	}
	
	/**
	 * 返回一张图片<br>
	 * 需要跟FileController配合使用的一个方法
	 * @param int $id 一般为系统图片ID，若传入url路径则第二个参数type无效
	 * @param int $type
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @return string
	 */
	public static function img($id, $type = File::PIC_ORIGINAL, $html_options = array()){
		if(is_numeric($id)){
			if($id == 0){
				//若有设置spares，返回对应的默认图片
				//若未设置，返回空字符串
				$spares = \F::app()->config->get('spares');
				if(isset($html_options['spare']) && isset($spares[$html_options['spare']])){
					$html = '<img src="'.\F::app()->view->url().$spares[$html_options['spare']].'"';

					if(isset($html_options['dw'])){
						$html .= ' width="'.$html_options['dw'].'"';
					}
					if(isset($html_options['dh'])){
						$html .= ' height="'.$html_options['dh'].'"';
					}
					unset($html_options['spare'], $html_options['dw'], $html_options['dh'], $html_options['x'], $html_options['y'], $html_options['w'], $html_options['h']);
					foreach($html_options as $key => $val){
						if($val !== null && $val !== false){
							$html .= " {$key}=\"{$val}\"";
						}
					}
					$html .= ' />';
					return $html;
				}else{
					return '';
				}
			}
			$img_params = array('t'=>$type);
			$file = Files::model()->find($id, 'raw_name,file_ext,file_path,image_width,image_height,qiniu,is_image,file_type');
			switch($type){
				case File::PIC_ORIGINAL:
					if(substr($file['file_path'], 0, 4) == './..'){
						//私有文件，不能直接访问文件
						$src = \F::app()->view->url('file/pic', array(
							'f'=>$id,
						));
					}else{
						//公共文件，直接返回真实路径
						if(\F::app()->config->get('*', 'qiniu') && $file['qiniu']){
							//若开启了七牛云存储，且文件已上传，则显示七牛路径
							$src = Qiniu::model()->getUrl($file);
						}else{
							$src = \F::app()->view->url() . ltrim($file['file_path'], './') . $file['raw_name'] . $file['file_ext'];
						}
					}
					
					isset($html_options['width']) || $html_options['width'] = $file['image_width'];
					isset($html_options['height']) || $html_options['height'] = $file['image_height'];
				break;
				case File::PIC_THUMBNAIL:
					//显示一张缩略图，若不是图片文件，显示一个图标
					if(\F::app()->config->get('*', 'qiniu') && $file['qiniu'] && $file['is_image']){
						//若开启了七牛云存储，且文件已上传，则利用七牛在线裁剪为100x100图片
						$src = Qiniu::model()->getUrl($file, array(
							'dw'=>100,
							'dh'=>100,
						));
					}else{
						$src = File::model()->getThumbnailUrl($file);
					}
				break;
				case File::PIC_CROP:
					if(isset($html_options['x'])){
						$img_params['x'] = $html_options['x'];
						unset($html_options['x']);
					}
					if(isset($html_options['y'])){
						$img_params['y'] = $html_options['y'];
						unset($html_options['y']);
					}
					if(isset($html_options['dw'])){
						$img_params['dw'] = $html_options['dw'];
						unset($html_options['dw']);
					}
					if(isset($html_options['dh'])){
						$img_params['dh'] = $html_options['dh'];
						unset($html_options['dh']);
					}
					if(isset($html_options['w'])){
						$img_params['w'] = $html_options['w'];
						unset($html_options['w']);
					}
					if(isset($html_options['h'])){
						$img_params['h'] = $html_options['h'];
						unset($html_options['h']);
					}
					ksort($img_params);
					$img_params['f'] = $id;
					$src = \F::app()->view->url('file/pic', $img_params);
				break;
				case File::PIC_RESIZE:
					if(\F::app()->config->get('*', 'qiniu') && $file['qiniu']){
						//若开启了七牛云存储，且文件已上传，则利用七牛进行裁剪输出
						$src = Qiniu::model()->getUrl($file, array(
							'dw'=>isset($html_options['dw']) ? $html_options['dw'] : false,
							'dh'=>isset($html_options['dh']) ? $html_options['dh'] : false,
						));
					}else{
						if(isset($html_options['dw'])){
							$img_params['dw'] = $html_options['dw'];
						}
						if(isset($html_options['dh'])){
							$img_params['dh'] = $html_options['dh'];
						}
						ksort($img_params);
						$img_params['f'] = $id;
						$src = \F::app()->view->url('file/pic', $img_params);
					}
					unset($html_options['dw'], $html_options['dh']);
				break;
			}
			unset($html_options['spare']);
			return self::tag('img', array('src'=>$src)+$html_options);
		}else{
			return self::tag('img', array('src'=>$id)+$html_options);
		}
	}
	
	/**
	 * 构造一个超链接
	 * @param string $text 链接描述。默认会对其做Html::encode处理，若不编码，则在html_options中设置encode为false
	 * @param url|array $uri 链接地址
	 *     若为数组，第0项为路由（router），第1项为参数（可为空），第2项为是否重写（默认为重写）
	 *     若为字符串，则直接作为href属性
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 * @param boolean $checkPermission 若该参数为true，且传入的uri是个数组，且存在权限验证方法的情况下，会做权限验证
	 * @return string
	 */
	public static function link($text, $uri = 'javascript:;', $html_options = array(), $checkPermission = false){
		if(is_array($uri)){
			if($checkPermission && method_exists(\F::app(), 'checkPermission')){
				if(!\F::app()->checkPermission($uri[0])){
					return '';
				}
			}
			$uri = \F::app()->view->url(empty($uri[0]) ? null : $uri[0],
				empty($uri[1]) ? array() : $uri[1],
				isset($uri[2]) && $uri[2] === false ? false : true);
		}else if($uri === null){
			$uri = \F::app()->view->url();
		}
		
		$html_options['href'] = $uri;
		
		if(!isset($html_options['encode']) || $html_options['encode'] == true){
			$text = self::encode($text);
		}
		if(!isset($html_options['title'])){
			$html_options['title'] = $text;
		}
		return self::tag('a', $html_options, $text);
	}
	
	/**
	 * 跳转到站外地址。
	 * 出于seo考虑，不直接显示站外地址，而是通过tools/redirect/index来跳转
	 * @param string $text
	 * @param string $url 包括http://在内的完整url
	 * @param array $html_options 其它html属性，可以是自定义属性或者html标准属性
	 */
	public static function outsideLink($text, $url, $html_options = array()){
		return self::link($text, array('redirect', array(
			'url'=>base64_encode($url),
		), false), $html_options);
	}
	
	/**
	 * 用于生成HTML结构。<br>
	 * 该函数只负责拼装html，不做转义处理<br>
	 * 同时该函数不做参数格式正确性验证，传错了可能会出现报错
	 * @param string $tag
	 * @param array $html_options
	 * @param string|false $text 若为false，则视为自封闭标签
	 */
	public static function tag($tag, $html_options, $text = false){
		$before = '';
		$after = '';
		$append = '';
		$prepend = '';
		
		//4个特殊属性
		foreach(array('before', 'after', 'append', 'prepend') as $v){
			if(!empty($html_options[$v])){
				if(is_array($html_options[$v]) && isset($html_options[$v]['tag'])){
					$tag2 = $html_options[$v]['tag'];
					$text2 = isset($html_options[$v]['text']) ? $html_options[$v]['text'] : false;
					unset($html_options[$v]['tag'], $html_options[$v]['text']);
					$$v = self::tag($tag2, $html_options[$v], $text2);
				}else{
					$$v = $html_options[$v];
				}
				unset($html_options[$v]);
			}
		}
		
		if(!empty($html_options['wrapper'])){
			$wrapper = $html_options['wrapper'];
			unset($html_options['wrapper']);
		}
		
		$html = "<{$tag}";
		foreach($html_options as $name => $value){
			if($value === false)continue;
			//一般是用于class这类可能有多个的属性
			if(is_array($value)){
				$value = implode(' ', $value);
			}
			$html .= ' ' . $name . '="' . $value . '"';
		}
		
		if($text === false){
			$html .= ' />';
		}else if(is_array($text)){
			if(isset($text['tag'])){
				$text_tag = $text['tag'];
				$text_text = isset($text['text']) ? $text['text'] : false;
				unset($text['tag'], $text['text']);
				$html = $html . '>' . $prepend . self::tag($text_tag, $text, $text_text) . $append . "</{$tag}>";
			}else{
				$elements = array();
				foreach($text as $t){
					if(empty($t['tag'])){
						continue;
					}
					$t_tag = $t['tag'];
					$t_text = isset($t['text']) ? $t['text'] : false;
					unset($t['tag'], $t['text']);
					$elements[] = self::tag($t_tag, $t, $t_text);
				}
				$html = $html . '>' . $prepend . implode("\r\n", $elements) . $append . "</{$tag}>";
			}
		}else{
			$html = $html . '>' . $prepend . $text . $append . "</{$tag}>";
		}
		
		if(isset($wrapper)){
			if(is_array($wrapper) && isset($wrapper['tag'])){
				$wrapper_tag = $wrapper['tag'];
				unset($wrapper['tag']);
				return self::tag($wrapper_tag, $wrapper, $before . $html . $after);
			}else{
				return self::tag($wrapper, array(), $before . $html . $after);
			}
		}
		return $before . $html . $after;
	}
}