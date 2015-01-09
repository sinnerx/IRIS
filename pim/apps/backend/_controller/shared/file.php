<?php
class Controller_File
{
	public function __construct()
	{
		$this->siteID = authData('user.userLevel') != 99 ? authData('site.siteID') : 0;
		$this->maxsize = 5000000;
	}

	public function index()
	{
		$serverFileMaxSize = ini_get("upload_max_filesize");
		$serverFileMaxSize = substr($serverFileMaxSize, 0, strlen($serverFileMaxSize)-1) * 1000000;

		if($serverFileMaxSize < $this->maxsize)
		{
			$data['maxSize'] = ($serverFileMaxSize/1000000)."MB (Server setting)";
		}
		else
		{
			$data['maxSize'] = ($this->maxsize/1000000)."MB";
		}

		if(form::submitted())
		{
			if(input::get("type") == "file")
			{
				$this->addFile();
			}
			else
			{
				$this->addFolder();
			}
		}

		view::render("shared/file/index", $data);
	}

	public function addFolder($folderID = 0)
	{
		if(form::submitted())
		{
			$rules	= Array(
				"fileFolderName,fileFolderPrivacy"=>"required:This field is required."
				);

			if($error = input::validate($rules))
			{
				// return response::json(Array("error"=>"Name and privacy are required"));
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Error in your form's field","error");
			}

			$folderID	= input::get("fileFolderID");
			model::load("file/folder")->addFolder($this->siteID ,$folderID,input::get());

			// return response::json(Array("succes"=>"success"));
			redirect::to("","Added new folder.");
		}
	}

	private function addFile()
	{
		// max size
		$maxsize = $this->maxsize;

		$exts = array("xls","xlsx",
				"doc","docx","ppt","pptx",
				"pps","ppsx","odt","pdf",
				"png","jpeg","jpg","bmp",
				"zip","rar","mp3","m4a",
				"ogg","wav","mp4","m4v",
				"mov","wmv","avi","mpg",
				"ogv","3gp","3g2");

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

		if(!$file->isExt($exts))
		{
			redirect::to("","List of allowed file extension : ". implode(", ", $exts), "error");
		}

		if($file->get('size') > $maxsize)
		{
			redirect::to("", "File size cannot be bigger than ". ($maxsize/1000) . "kb ", "error");
		}

		## use file name.
		if($data['fileName'] == "")
		{
			$fn	= explode(".",$file->get("name"));
			array_pop($fn);
			$data['fileName']	= implode(".",$fn);
		}

		$data['fileSize']	= $file->get("size");
		$data['fileExt']	= $file->get("ext");

		$fileID	= model::load("file/file")->addFile($this->siteID,$data['fileFolderID'],$data);
		$path	= path::files("site_files/".$this->siteID);

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
}
?>