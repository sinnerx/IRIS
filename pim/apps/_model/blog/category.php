<?php 
namespace model\blog;
use db, session, model, pagination, url;

class Category
{
	public function getCategoryList(){
		db::from("category");
		db::where("categoryParentID",0);
		db::order_by("categoryName ASC");
		$parentCategory = db::get()->result();
		$categoryList = array();

		foreach($parentCategory as $parent){
			db::from("category");
			db::where("categoryParentID",$parent['categoryID']);
			db::order_by("categoryName ASC");
			array_push($categoryList, $parent);

			if($result=db::get()->result()){
				$categoryList[count($categoryList)-1]['child'] = $result;
			}
		}

		return $categoryList;
	}

	public function getArticleCategoryList($articleID){
		db::from("category");
		db::where("categoryParentID",0);
		db::order_by("categoryName ASC");
		$parentCategory = db::get()->result();
		$categoryList = array();

		foreach($parentCategory as $parent){
			array_push($categoryList, $parent);

			db::from("category");
			db::where("categoryParentID",$parent['categoryID']);
			db::order_by("categoryName ASC");

			if($result=db::get()->result("categoryID")){
				$categoryList[count($categoryList)-1]['child'] = $result;
			}
			
			db::from("article_category");
			db::where("articleID=".$articleID." AND categoryID=".$parent['categoryID']);

			if(db::get()->row()){
				$categoryList[count($categoryList)-1]['checked'] = true;
			}	
			
			foreach($result as $res){
				db::from("article_category");
				db::where("articleID=".$articleID." AND categoryID=".$res['categoryID']);

				if(db::get()->row()){
					$categoryList[count($categoryList)-1]['child'][$res[categoryID]]['checked'] = true;
				}	
			}
		}//echo '<pre>';print_r($categoryList);die;

		return $categoryList;
	}
}
?>