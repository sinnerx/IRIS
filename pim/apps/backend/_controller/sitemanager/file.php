<?php
class Controller_File
{
	public function index()
	{
		if(form::submitted())
		{
			$file	= input::file("fileUpload");

			$rules	= Array(
				"filePrivacy"=>"required:Please set a privacy.",
				"fileUpload"=>Array(
					"callback"=>Array(!$file?false:true,"Please input an upload file.")
					)
							);

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Error in your form's field","error");
			}


			$data	= input::get();
			$data['fileType']	= $file->get("type");
			$data['fileName']	= $file->get("name");
			$data['fileSize']	= $file->get("size");
			$data['fileExt']	= $file->get("ext");

			$fileID	= model::load("file/file")->addFile(authData("site.siteID"),$data['fileFolderID'],$data);
			$path	= path::files("site_files/".authData("site.siteID"));

			if(!is_dir($path))
			{
				$mkdir = mkdir($path,0775,true);

				if(!$mkdir)
				{
					die;
				}
			}

			$file->move($path."/".$fileID);
			redirect::to("","Successfully uploaded new file.");
		}

		view::render("sitemanager/file/index");
	}

	public function addFile()
	{
	}
}
?>