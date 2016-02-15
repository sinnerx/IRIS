<?php
Class Controller_Example
{
	## set template to false, so we did't have to load/check template while rendering view.
	var $template	= "templateforexample";
	public function index()
	{
		## it can be accessed locally. so it wont load a template when rendering.
		$this->template	= false;

		$data['listtest']	= Array(
				"inputValidation"=>"Input validation",
				"BaseUrlAndAsset"=>"Base Url And Asset",
				"session"=>"Session",
				"multiDimensionalSession"=>"Multi Dimensional Session",
				"POSTandGET"=>"_POST And _GET",
				"paginateList"=>"Pagination"
							);
		view::render("example/index",$data);
	}

	public function inputValidation()
	{
		## if form is submitted. alias to  
		if(form::submitted())
		{
			$emailcheck	= model::load("user/services")->checkEmail(input::get("secondEmail"));

			$rules	= Array(
					"_all"=>"required:this field is required.",
					"myEmail"=>"email:this one must be email",
					"secondEmail"=>Array(
							"email:this also must be email",
							"callback"=>Array(!$emailcheck,"This email already exists..")
										)
			);
			## if got error. error, will be passed at $error. 
			# Format : field name as a key and error message as value.
			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash($error); ## an alias to flash::set($error);
				redirect::to("","Form problems.","error");
			}

			redirect::to("","Form success.","success");
		}

		view::render("example/inputValidation");
	}

	## base url and asset url example.
	public function BaseUrlAndAsset()
	{
		controller::load("example","index");

		echo "<pre>";
		echo "Base url : ".url::base()."\n";
		echo "Base url with value : ".url::base("this-is-some")."\n";
		echo "Base url with named parameter : ".url::base("{method}")."\n";
		echo "Asset url : ".url::asset("some-asset.js");
	}

	## session example.
	public function session()
	{
		controller::load("example","index");

		if(!session::has("userID"))
		{
			echo "There's no session 'userid'.";
		}
		else
		{
			echo "There's a session 'userid'. The value is : ".session::get("userID");
		}
	}

	public function urltest()
	{
		echo "<a href='".url::base()."'>abc</a>";
	}

	## multidimensional session example.
	public function multiDimensionalSession()
	{
		## get request handling.
		if(request::get("setmulti"))
		{
			session::set("first.second","some key");
			redirect::to("{controller}/{method}");
		}

		## destroy first.second, leaving but the element 'first' still exists.
		if(request::get("killsecond"))
		{
			session::destroy("first.second");
			redirect::to("{controller}/{method}");
		}

		## destroy element 'first' along with his sub-element 'second'
		if(request::get("killfirst"))
		{
			session::destroy("first");
			redirect::to("{controller}/{method}");
		}


		$data['text']		= "";

		## testing
		if(session::has("first"))
		{
			$data['text']	.= "Got first level key<br>";
		}

		if(session::has("first.second"))
		{
			$data['text']	.= "Got second level key";
		}

		view::render("example/multiDimensionalSession",$data);
	}

	## post and get
	public function POSTandGET()
	{
		controller::load("example","index");

		echo "_GET[hello] : ".request::get("hello")."<br>"; 	## _GET[hello]
		echo "_GET[world] : ".request::get("world")."<br>";	## _GET[world]
		echo "_POST[hai] : ".request::post("hai")."<br>";	## _POST[hai]
		echo "_POST[dunia] : ".request::post("dunia")."<br>"; ## _POST[dunia]
	}

	## pagination example.
	public function paginateList($page = 1)
	{
		controller::load("example","index");
		db::from("site");

		## paginate based on current query built.
		pagination::initiate(Array(
							"totalRow"=>db::num_rows(), ## 1. num_rows() can return early total based on current built query.
							"limit"=>10,				## 2. optional
							"urlFormat"=>url::base("example/paginateList/{page}"), ## 3. url format.
							"currentPage"=>$page
									));

		## limit, and offset.
		db::limit(pagination::get("limit"),pagination::recordNo()-1); 

		## get list
		$result	= db::get()->result();

		## echo the pagination link
		echo pagination::link();


		echo "<pre>";
		print_r($result);
	}

	public function testdb()
	{
	}
}
?>