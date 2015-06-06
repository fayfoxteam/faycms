<?php
namespace ncp\models;

use fay\core\Model;
use fay\core\Sql;
use fay\models\Category;
use fay\models\tables\Posts;

class Recommend extends Model{
	/**
	 * @return Recommend
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 随机获取指定天数内有访问的文章作为推荐文章
	 * 若指定时间内的文章数不足，则会去获取更早的文章补充
	 * @param int|string|array $cat 分类（可以是ID, alias或者包含left_value和right_value值的数组），若为null，则不指定分类
	 * @param int $limit 数量
	 * @param int $days
	 * @param int|string $area id或者别名
	 * @param int $not 不等于此id
	 */
	public function getByCatAndArea($cat = null, $limit = 9, $days = 7, $area = 0, $not = null){
		$sql = new Sql();
		
		$sql->from('posts', 'p', 'id,title,thumbnail,abstract,publish_time,views')
			->joinLeft('categories', 'c', 'p.cat_id = c.id', 'title AS cat_title')
			->where(array(
				'p.deleted = 0',
				'p.publish_time < '.\F::app()->current_time,
				'p.status = '.Posts::STATUS_PUBLISHED,
			))
			->order('RAND()')
			->limit($limit);
		
		if($cat != null){
			if(is_array($cat)){
				//无操作
			}else if(is_numeric($cat)){
				$cat = Category::model()->get($cat);
			}else{
				$cat = Category::model()->getByAlias($cat);
			}
			$sql->where(array(
				'c.left_value >= '.$cat['left_value'],
				'c.right_value <= '.$cat['right_value'],
			));
		}
		
		if($area){
			$sql->joinLeft('post_prop_int', 'pi', array(
				'pi.prop_id = '.$area,
				'pi.post_id = p.id',
			))->where('pi.content = '.$area);
		}
		
		if($days){
			$start_time = \F::app()->current_time - (86400 * $days);
			$sql->where('p.last_view_time > '.$start_time);
		}
		
		if($not){
			if(is_array($not)){
				$sql->where(array('p.id NOT IN (?) '=>$not));
			}else{
				$sql->where('p.id != '.$not);
			}
		}
		
		$result = $sql->fetchAll();
		$result_count = count($result);
		//指定时间内的文章不足
		if($result_count < $limit && $days){
			$sql->from('posts', 'p', 'id,title,thumbnail,abstract,publish_time,views')
				->joinLeft('categories', 'c', 'p.cat_id = c.id', 'title AS cat_title')
				->where(array(
					'p.deleted = 0',
					'p.publish_time < '.\F::app()->current_time,
					'p.status = '.Posts::STATUS_PUBLISHED,
				))
				->order('RAND()')
				->limit($limit - $result_count);
			
			if($cat != null){
				if(is_array($cat)){
					//无操作
				}else if(is_numeric($cat)){
					$cat = Category::model()->get($cat);
				}else{
					$cat = Category::model()->getByAlias($cat);
				}
				$sql->where(array(
					'c.left_value >= '.$cat['left_value'],
					'c.right_value <= '.$cat['right_value'],
				));
			}
			
			if($area){
				$sql->joinLeft('post_prop_int', 'pi', array(
					'pi.prop_id = '.$area,
					'pi.post_id = p.id',
				))->where('pi.content = '.$area);
			}
			
			if($days){
				$sql->where('p.last_view_time < '.$start_time);
			}
			
			if($not){
				if(is_array($not)){
					$sql->where(array('p.id NOT IN (?)'=>$not));
				}else{
					$sql->where('p.id != '.$not);
				}
			}
			
			$result = array_merge($result, $sql->fetchAll());
		}
		return $result;
	}
}