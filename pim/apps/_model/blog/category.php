<?php 
namespace model\blog;
use db, session, model, pagination, url;

class Category
{
	## re-written into on 2 query max.
	public function getCategoryList(){
		db::from("category");
		db::where("categoryParentID",0);
		db::order_by("categoryName ASC");
		$parentCategory = db::get()->result("categoryID");
		$categoryList = array();

		## select child based on list of parentID (categoryID)
		$result	= Array();
		if($parentCategory)
		{
			db::from("category");
			db::where("categoryParentID",array_keys($parentCategory));
			db::order_by("categoryName ASC");

			$result	= db::get()->result('categoryParentID',true);	
		}

		foreach($parentCategory as $parent)
		{
			array_push($categoryList, $parent);

			if(isset($result[$parent['categoryID']]))
			{
				$categoryList[count($categoryList)-1]['child'] = $result[$parent['categoryID']];
			}
		}

		/*foreach($parentCategory as $parent){
			db::from("category");
			db::where("categoryParentID",$parent['categoryID']);
			db::order_by("categoryName ASC");
			array_push($categoryList, $parent);

			if($result=db::get()->result()){
				$categoryList[count($categoryList)-1]['child'] = $result;
			}
		}*/

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

	public function getArticleCategoryList2($articleID)
	{
		db::select("articleID,category.categoryID,categoryName,categoryParentID");
		db::where("articleID",$articleID);
		db::order_by("categoryName ASC");
		db::join("category","category.categoryID = article_category.categoryID");

		if(is_array($articleID))
		{
			return db::get("article_category")->result("articleID",true);
		}
		else
		{
			return db::get("article_category")->result("categoryID");
		}
	}

	public function getCategory($id,$cols = null)
	{
		db::select($cols);
		db::where("categoryID",$id);
		return db::get("category")->row($cols);
	}

	public function getCategoryByName($name)
	{
		db::select($cols);
		db::where("categoryName",$name);
		return db::get("category")->row($cols);
	}

	public function categoryRename($id,$name)
	{
		if($name == "")
		{
			return false;
		}

		if($this->checkName($name,$id))
		{
			return false;
		}

		db::where("categoryID",$id)->update("category",Array("categoryName"=>$name));

		return true;
	}

	public function add($name)
	{
		if($this->checkName($name))
			return false;

		if($name == "")
			return false;

		db::insert("category",Array(
							"categoryName"=>$name,
							"categoryParentID"=>0,
							"categoryCreatedDate"=>now(),
							"categoryCreatedUser"=>session::get("userID")
									));

		return true;
	}

	public function addChild($parentID,$name)
	{
		if($name == "")
			return false;

		## check if no duplidate name.
		if($this->checkName($name))
			return false;

		db::insert("category",Array(
							"categoryName"=>$name,
							"categoryParentID"=>$parentID,
							"categoryCreatedDate"=>now(),
							"categoryCreatedUser"=>session::get("userID")
									));

		return true;
	}

	public function checkName($name,$exceptionID = null)
	{
		if($exceptionID)
			db::where("categoryID !=",$exceptionID);

		return db::where("categoryName",$name)->get("category")->row();
	}

	public function delete($catID)
	{
		## check if got no relation with any article.
		$res_article	= db::select("categoryID")->where("categoryID",$catID)->get("article_category")->result();

		if(!$res_article)
		{
			## check if it got child.
			$res_child	= db::where("categoryParentID",$catID)->get("category")->result("categoryID");

			if($res_child)
			{
				return Array(false,"Unable to delete. Still got sub-category under this category.");
			}

			db::delete("category",Array("categoryID"=>$catID));

			return Array(true);
		}
		else
		{
			return Array(false,"Unable to delete. Got related article to this category.");
		}

	}
}
?>