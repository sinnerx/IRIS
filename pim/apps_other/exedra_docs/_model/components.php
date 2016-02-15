<?php

class Model_Components
{
	public function lists($type = Null)
	{
		$component	= $this->data();

		return $type?$component[$type]:$component;
	}

	private function data()
	{
		$component	= Array(
					"mvc|M.V.C."=>Array(
							"Apps"=>"apps:run,config,environment,register_autoload_path",
							"Router"=>"router:add,dispatch",
							"Controller"=>"controller:init,load,hook",
							"Model"=>"model:load",
							"View"=>"view:render",
							"Template"=>"template:showContent"
								),
					"core|Core Components"=>Array(
							"Session Management"=>"session:set,get,destroy",
							"Flash Message"=>"flash:set,data,has",
							"Form Helper"=>"form:text,textarea,password,select,file,submit,submitted",
							"Pagination"=>"pagination:setFormat,init,link",
							"Request"=>"request:get,post,named,isAjax,method",
							"Input"=>"input:get,repopulate,file,validate",
							"Validator"=>"validator:validate,setRuleMessage"
								),
					"db|Query Builder"=>Array(
							"Querying"=>"query:select,from,where,or_where,order_by,limit,join,query",
							"Result"=>"result:get,result,getLastSQL",
							"Insert"=>"insert:insert",
							"Update"=>"update:where,or_where,update",
							"Delete"=>"delete:where,or_where,delete"
								),
					"pathurl|Path and URL"=>Array(
							"Path"=>"path:asset,files",
							"URL"=>"url:base,asset"
									)
							);

		return $component;
	}
}


?>